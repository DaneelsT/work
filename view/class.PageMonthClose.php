<?php

namespace Work\Page;

/**
 * A class which describes the actions and properties of the month close page.
 *
 * @author  Gaetan Dumortier
 * @since   25 October 2016
 */

use \Carbon\Application\Application;
use \Work\Page\AbstractAuthorizedPage;
use \Work\Shift\Shift;
use \Work\Month\Month;
use \Work\User\User;
use \Work\UI\ViewHeader;
use \Work\UI\ViewFooter;
use \PDO;

class PageMonthClose extends AbstractAuthorizedPage {

    const PATH = "/month/close$";
    const TITLE = "Close and book current month";

    private $mHeader;
    private $mFooter;

    private $mDbHandle;
    
    private $mAlreadyBooked = false;
    
    private $mShifts = array();

    private $mWorkedTime = array();
    private $mCurrentEarnings;
    private $mHoursWorked;
    private $mMinutesWorked;
	private $mDaysWorked;
    private $mTotalPay;
    private $mTotalHours;
    private $mSunday = 0;
    private $mSundayExtra;

    private function initializeViewElements() {
        $this->mHeader = new ViewHeader(self::TITLE);
        $this->mFooter = new ViewFooter();
    }

    private function initializeDatabaseConnection() {
        $app = Application::getInstance();
        $app->connectToDatabase();
        $this->mDbHandle = $app->getDatabaseConnection();
    }
    
    // Fetch all the current worked shifts from the database, ordered DESC by date
    private function fetchAllShifts() {
    	$app = Application::getInstance();
		$user = $app->getUser();
		
        $sql = "SELECT *
                FROM shifts
                WHERE userid = :userid
                ORDER BY date DESC";
        $statement = $this->mDbHandle->prepare($sql);
		$statement->bindParam(':userid', $user->getId());
        $statement->execute();
        $shifts = $statement->fetchAll(PDO::FETCH_ASSOC);
		$this->mDaysWorked = count($shifts);
        // Convert the shift associative array to shift objects
        foreach ($shifts as $shift) {
            $id = $shift['id'];
            $date = $shift['date'];
            $starttime = $shift['startTime'];
            $endtime = $shift['endTime'];
            $isSunday = $shift['isSunday'];
            if($isSunday) {
                ++$this->mSunday;
				$this->mSundayExtra = $app->getConfiguration("sunday_fee");
            }
            // Allocate a shift instance
            array_push($this->mShifts, new Shift($id, $date, $starttime, $endtime, $this->mSunday));
        }
    }

    // Calculate the current earnings of the month, based on HOURLY_PAY and the amount of hours worked.
    public function calculateEarnings() {
    	$app = Application::getInstance();
    	$user = $app->getUser();
		
        $shifts = $this->getShifts();
        $payPerHour = $user->getHourlyPay();
        $i = 0;

        foreach ($shifts as $shift) {
            $dt = ($shift->getEndTime() - $shift->getStartTime());
            $this->mHoursWorked = (int)$dt / 60 / 60;
            $this->mMinutesWorked = (int)($dt - ($this->mHoursWorked * 60 * 60)) / 60;
            $this->mWorkedTime[$i]['hours'] = $this->mHoursWorked;
            $this->mWorkedTime[$i]['minutes'] = $this->mMinutesWorked;
            $this->mWorkedTime[$i]['totalPay'] = ($this->mWorkedTime[$i]['hours'] + ($this->mWorkedTime[$i]['minutes'] / 60)) * $payPerHour;
            ++$i;
        }

        $n = count($this->mWorkedTime);
        for ($i = 0; $i < $n; ++$i) {
            $this->mTotalPay += $this->mWorkedTime[$i]['totalPay'];
        }

        for ($i = 0; $i < $n; ++$i) {
            $this->mTotalHours += ($this->mWorkedTime[$i]['hours'] + ($this->mWorkedTime[$i]['minutes'] / 60));
        }
    }

    // Check if the current month has been booked already
    private function checkMonth() {
    	$app = Application::getInstance();
		$user = $app->getUser();
        $sql = "SELECT month
                FROM months
                WHERE month = :month AND userid = :userid";
        $statement = $this->mDbHandle->prepare($sql);
        $statement->bindParam(':month', date('n'));
		$statement->bindParam(':userid', $user->getId());
        $statement->execute();
        $result = $statement->fetchAll(PDO::FETCH_ASSOC);
        $count = count($result);
        if($count == 1) {
            $this->mAlreadyBooked = true;
            return;
        }else{
            $this->bookMonth();
        }
    }
    
    // Book the details of the current month and insert them into the database
    private function bookMonth() {
    	$app = Application::getInstance();
		$user = $app->getUser();
		
        $currentMonth = date("n");
        $sql = "INSERT INTO months (month, hoursWorked, daysWorked, earnings, sundaysWorked, userid)
                VALUES(:month, :hoursworked, :daysworked, :earnings, :sundaysworked, :userid)";
        $statement = $this->mDbHandle->prepare($sql);
        $statement->bindParam(':month', $currentMonth);
        $statement->bindParam(':hoursworked', round($this->mTotalHours, 1));
		$statement->bindParam(':daysworked', $this->mDaysWorked);
        $statement->bindParam(':earnings', $this->mTotalPay);
        $statement->bindParam(':sundaysworked', $this->mSunday);
		$statement->bindParam(':userid', $user->getId());
        $statement->execute();
		
        $this->closeMonth();
    }
    
    // Close the current month by emptying the shifts table
    private function closeMonth() {
    	$app = Application::getInstance();
		$user = $app->getUser();
		
    	$sql = "DELETE FROM shifts WHERE userid = :userid";
        $statement = $this->mDbHandle->prepare($sql);
		$statement->bindParam(':userid', $user->getId());
        $statement->execute();
    }
    
    public function __construct() {
    	parent::__construct(parent::DEFAULT_LOGIN_DIR);
        $this->setTitle(self::TITLE);
        $this->initializeViewElements();
        $this->initializeDatabaseConnection();
        
        $this->fetchAllShifts();
        $this->calculateEarnings();
        $this->checkMonth();
    }
    
    public function alreadyBooked() {
        return $this->mAlreadyBooked;
    }

    public function getShifts() {
        return $this->mShifts;
    }
    
    public function getTotalPay() {
        return $this->mTotalPay;
    }
    
    public function getTotalPayWithFees() {
        return $this->mTotalPay + $this->mSundayExtra;
    }
    
    public function getTotalHours() {
        return $this->mTotalHours;
    }
	
	public function getDaysWorked() {
		return $this->mDaysWorked;
	}
    
    public function getSundaysWorked() {
        return $this->mSunday;
    }

    public function draw() {
        $this->mHeader->draw();
        include "theme/inc.month-close.php";
        $this->mFooter->draw();
    }

}
