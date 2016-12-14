<?php

namespace Work\Page;

/**
 * A class which describes the actions and properties of the view shifts page.
 *
 * @author  Gaetan Dumortier
 * @since   2 November 2016
 */

use \Carbon\Application\Application;
use \Work\Page\AbstractAuthorizedPage;
use \Work\User\User;
use \Work\Shift\Shift;
use \Work\UI\ViewHeader;
use \Work\UI\ViewFooter;
use \PDO;

class PageAdminShift extends AbstractAuthorizedPage {

    const PATH = "/admin/shift/[0-9]+$";
    const TITLE = "View Shifts";

    private $mHeader;
    private $mFooter;
    
    private $mDbHandle;
    
    private $mUser = null;
    private $mShifts = array();
    
    private $mWorkedTime = array();
    private $mCurrentEarnings;
    private $mHoursWorked;
    private $mMinutesWorked;
    private $mTotalPay;
    private $mTotalHours;
    private $mSundayExtra;
    
    private $mUserNotFound = false;
    private $mNoShifts = false;
    
    private function initializeViewElements() {
        $this->mHeader = new ViewHeader(self::TITLE);
        $this->mFooter = new ViewFooter();
    }

    private function initializeDatabaseConnection() {
        $app = Application::getInstance();
        $app->connectToDatabase();
        $this->mDbHandle = $app->getDatabaseConnection();
    }

    private function addScripts() {
        $this->mFooter->addScript("jquery.min.js");
    }
    
    private function fetchUser() {
        $app = Application::getInstance();
        $id = $app->getRouter()->getSegment(2);
		$sql = "SELECT *,
				users_pay.hourly_pay,
				users_pay.sunday_fee
				FROM users
					INNER JOIN users_pay
						ON users.id = users_pay.userid
				WHERE users.id = :userid";
        $statement = $this->mDbHandle->prepare($sql);
        $statement->bindParam(":userid", $id);
        $statement->execute();
        $user = $statement->fetch();
        if($user) {
            $id = $user['id'];
            $username = $user['username'];
            $email = $user['email'];
            $name = $user['name'];
            $surname = $user['surname'];
            $gender = $user['gender'];
			$pay = $user['hourly_pay'];
			$fee = $user['sunday_fee'];
            // Allocate a new user instance
            $this->mUser = new User($id, $username, $email, $name, $surname, $gender, $pay, $fee);
            $this->fetchShifts();
            $this->calculateEarnings();
        }else{
            $this->mUserNotFound = true;
            return;
        }
    }

    private function fetchShifts() {
        /*
        $app = Application::getInstance();
        $id = $app->getRouter()->getSegment(2);
        */
        $sql = "SELECT id, date, startTime, endTime, isSunday
                FROM shifts
                WHERE userid = :userid
                ORDER BY date DESC";
        $statement = $this->mDbHandle->prepare($sql);
        $statement->bindParam(':userid', $this->mUser->getId());
        $statement->execute();
        $shifts = $statement->fetchAll(PDO::FETCH_ASSOC);
        // Convert the shift associative array to shift objects
        foreach ($shifts as $shift) {
            $id = $shift['id'];
            $date = $shift['date'];
            $starttime = $shift['startTime'];
            $endtime = $shift['endTime'];
			$sunday = $shift['isSunday'];
            if(dayIsSunday($date))
                $this->addSunday();
            // Allocate a shift instance
            array_push($this->mShifts, new Shift($id, $date, $starttime, $endtime, $sunday));
        }
    }
    
    // Add the extra payment per sunday to the mSundayExtra member to add to the total pay.
    private function addSunday() {
        $this->mSundayExtra += $this->mUser->getSundayFee();
    }
    
    // Calculate the current earnings of the month, based on the hourly pay of the user and the amount of hours worked.
    public function calculateEarnings() {
        $shifts = $this->getShifts();
		$payPerHour = $this->mUser->getHourlyPay();
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

    public function __construct() {
        if(Application::getInstance()->getUser()->isAdmin()) {
            parent::__construct(parent::DEFAULT_LOGIN_DIR);
            $this->setTitle(self::TITLE);
            $this->initializeViewElements();
            $this->initializeDatabaseConnection();
            $this->addScripts();
            
            $this->fetchUser();
        }else{
            redirectInternally("/");
        }
    }
    
    public function getUser() {
        return $this->mUser;
    }
    
    public function getShifts() {
        return $this->mShifts;
    }
    
    public function userNotFound() {
        return $this->mUserNotFound;
    }
    
    public function noShifts() {
        return $this->mNoShifts;
    }
    
    public function getTotalPay() {
        return $this->mTotalPay;
    }
    
    public function getTotalPayWithFees() {
        return $this->mTotalPay + $this->mSundayExtra;
    }

    public function getPayPerHour() {
		return $this->mUser->getHourlyPay();
    }

    public function getTotalHours() {
        return $this->mTotalHours;
    }
    
    public function getSundayFee() {
		return $this->mUser->getSundayFee();
    }

    public function draw() {
        $this->mHeader->draw();
        include "theme/inc.admin-shift.php";
        $this->mFooter->draw();
    }

}
