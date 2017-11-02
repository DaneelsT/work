<?php

namespace Work\Page;

/**
 * A class which describes the actions and properties of the dashboard page.
 *
 * @author  Gaetan Dumortier
 * @since   21 June 2016
 */

use \Carbon\Application\Application;
use \Work\Page\AbstractAuthorizedPage;
use \Work\Shift\Shift;
use \Work\User\User;
use \Work\UI\ViewHeader;
use \Work\UI\ViewFooter;
use \PDO;

class PageDashboard extends AbstractAuthorizedPage {

    const PATH = "/$";
    private $mTitle = "Dashboard";

    private $mHeader;
    private $mFooter;

    private $mDbHandle;

    private $mShifts = array();

    private $mWorkedTime = array();
    private $mCurrentEarnings;
    private $mHoursWorked;
    private $mMinutesWorked;
    private $mTotalPay;
    private $mTotalHours;
    private $mSundayExtra;

    private function initializeViewElements() {
        $this->mHeader = new ViewHeader($this->mTitle);
        $this->mFooter = new ViewFooter();
    }

    private function initializeDatabaseConnection() {
        $app = Application::getInstance();
        $app->connectToDatabase();
        $this->mDbHandle = $app->getDatabaseConnection();
    }

    private function addScripts() {
        $this->mFooter->addScript("jquery.min.js");
        $this->mFooter->addScript("jquery.mask.min.js");
        $this->mFooter->addScript("main.js");

        $this->mFooter->addScript("dashboard.js");
    }

    // Insert a new shift into the database
    private function postShift() {
    	$app = Application::getInstance();
		$user = $app->getUser();

        $date = $_POST['date'];
        $rawStartTime = explode(':', $_POST['startTime']);
        $rawEndTime = explode(':', $_POST['endTime']);
        if(isset($_POST['holiday'])) {
            $isHoliday = true;
        }else{
            $isHoliday = false;
        }

        $startTimeHour = $rawStartTime[0] * 60 * 60;
        $startTimeMinute = $rawStartTime[1] * 60;
        $startTime = $startTimeHour + $startTimeMinute;

        $endTimeHour = $rawEndTime[0] * 60 * 60;
        $endTimeMinute = $rawEndTime[1] * 60;
        $endTime = $endTimeHour + $endTimeMinute;

        if($isHoliday || dayIsSunday($date)) {
            $isSunday = true;
        }else{
            $isSunday = false;
        }

        $sql = "INSERT INTO
                    shifts (date, startTime, endTime, isSunday, userid)
                    VALUES (:date, :startTime, :endTime, :isSunday, :userid)";
        $statement = $this->mDbHandle->prepare($sql);
        $statement->bindParam(':date', $date);
        $statement->bindParam(':startTime', $startTime);
        $statement->bindParam(':endTime', $endTime);
        $statement->bindParam(':isSunday', $isSunday);
        $statement->bindParam(':userid', $user->getId());
        $statement->execute();
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
        $this->mSundayExtra += Application::getInstance()->getUser()->getSundayFee();
    }

    // Calculate the current earnings of the month, based on HOURLY_PAY and the amount of hours worked.
    public function calculateEarnings() {
        $shifts = $this->getShifts();
		$payPerHour = Application::getInstance()->getUser()->getHourlyPay();
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
    	parent::__construct(parent::DEFAULT_LOGIN_DIR);
        $this->setTitle($this->mTitle);
        $this->initializeViewElements();
        $this->initializeDatabaseConnection();
        $this->addScripts();

        // Check if a new shift was added
        if (isset($_POST['add_shift']))
            $this->postShift();

        $this->fetchAllShifts();
        $this->calculateEarnings();
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

    public function getPayPerHour() {
		return Application::getInstance()->getUser()->getHourlyPay();
    }

    public function getTotalHours() {
        return $this->mTotalHours;
    }

    public function getSundayFee() {
		return Application::getInstance()->getUser()->getSundayFee();
    }

    public function draw() {
        $this->mHeader->draw();
        include getTheme("inc.dashboard.php");
        $this->mFooter->draw();
    }

}
