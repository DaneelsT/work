<?php

namespace Work\Page;

/**
 * A class which describes the actions and properties of the year close page.
 *
 * @author  Gaetan Dumortier
 * @since   09 April 2017
 */

use \Carbon\Application\Application;
use \Work\Page\AbstractAuthorizedPage;
use \Work\Month\Month;
use \Work\User\User;
use \Work\UI\ViewHeader;
use \Work\UI\ViewFooter;
use \PDO;

class PageYearClose extends AbstractAuthorizedPage {

    const PATH = "/year/close$";
    private $mTitle = "Close and book current year";

    private $mHeader;
    private $mFooter;

    private $mDbHandle;

    private $mAlreadyBooked = false;

    private $mMonthsWorked;
    private $mHoursWorked;
    private $mDaysWorked;
    private $mEarnings;
    private $mSundaysWorked;

    private function initializeViewElements() {
        $this->mHeader = new ViewHeader($this->mTitle);
        $this->mFooter = new ViewFooter();
    }

    private function initializeDatabaseConnection() {
        $app = Application::getInstance();
        $app->connectToDatabase();
        $this->mDbHandle = $app->getDatabaseConnection();
    }

    // Fetch all current months from the database
    private function fetchAllMonths() {
        $app = Application::getInstance();
        $user = $app->getUser();

        $sql = "SELECT *
                FROM months
                WHERE userid = :userid
                ORDER BY month DESC";
        $statement = $this->mDbHandle->prepare($sql);
        $statement->bindParam(":userid", $user->getId());
        $statement->execute();
        $months = $statement->fetchAll(PDO::FETCH_ASSOC);
        $this->mMonthsWorked = count($months);
        // Loop through all fetched months
        foreach($months as $m) {
            $id = $m['id'];
            $month = $m['month'];
            $this->hoursWorked += $m['hoursWorked'];
            $this->mDaysWorked += $m['daysWorked'];
            $this->mEarnings += $m['earnings'];
            $this->mSundaysWorked += $m['sundaysWorked'];
            // Allocate a new Month instance
            array_push($this->mMonths, new Month($id, $month, $this->mHoursWorked, $this->mDaysWorked, $this->mEarnings, $this->mSundaysWorked));
        }
    }

    private function checkYear() {
        $app = Application::getInstance();
        $user = $app->getUser();

        $sql = "SELECT year FROM years WHERE year = :year";
        $statement = $this->mDbHandle->prepare($sql);
        $statement->bindParam(':year', date('Y'));
        $statement->execute();
        $result = $statement->fetchAll(PDO::FETCH_ASSOC);
        $count = count($result);
        if($count == 1) {
            $this->mAlreadyBooked = true;
            return;
        }else{
            $this->bookYear();
        }
    }

    // Close the current year by emptying the months table
    private function closeYear() {
    	$app = Application::getInstance();
		$user = $app->getUser();

    	$sql = "DELETE FROM months WHERE userid = :userid";
        $statement = $this->mDbHandle->prepare($sql);
		$statement->bindParam(':userid', $user->getId());
        $statement->execute();
    }

    public function __construct() {
    	parent::__construct(parent::DEFAULT_LOGIN_DIR);
        $this->setTitle($this->mTitle);
        $this->initializeViewElements();
        $this->initializeDatabaseConnection();

        $this->fetchAllMonths();
        $this->checkYear();
    }

    public function alreadyBooked() {
        return $this->mAlreadyBooked;
    }

    public function getMonthsWorked() {
        return $this->mMonthsWorked;
    }

    public function getHoursWorked() {
        return $this->mHoursWorked;
    }

    public function getDaysWorked() {
        return $this->mDaysWorked;
    }

    public function getEarnings() {
        return $this->mEarnings;
    }

    public function getSundaysWorked() {
        return $this->mSundaysWorked;
    }

    public function getMonths() {
        return $this->mMonths;
    }

    public function draw() {
        $this->mHeader->draw();
        include getTheme("inc.year-close.php");
        $this->mFooter->draw();
    }

}
