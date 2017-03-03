<?php

namespace Work\Page;

/**
 * A class which describes the actions and properties of the year close page.
 *
 * @author  Gaetan Dumortier
 * @since   3 Februari 2017
 */

use \Carbon\Application\Application;
use \Work\Page\AbstractAuthorizedPage;
use \Work\Month\Month;
use \Work\Year\Year;
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

    private $mMonths = array();

    private $mMonth;
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

    // Fetch all the current worked months from the database
    private function fetchAllMonths() {
        $app = Application::getInstance();
        $user = $app->getUser();

        $sql = "SELECT *
                FROM months
                WHERE userid = :userid
                ORDER BY date DESC";
        $statement = $this->mDbHandle->prepare($sql);
        $statement->bindParam(":userid", $user->getId());
        $statement->execute();
        $months = $statement->fetchAll(PDO::FETCH_ASSOC);
        // Convert the months associative array to month object
        foreach ($months as $month) {
            $id = $month['id'];
            $this->mMonth = $month['month'];
            $this->mHoursWorked += $month['hoursWorked'];
            $this->mDaysWorked += $month['daysWorked'];
            $this->mEarnings += $month['earnings'];
            $this->mSundaysWorked += $month['sundaysWorked'];
            // Allocate a new month instance
            array_push($this->mMonths, new Month($id, $this->mMonth, $this->mHoursWorked, $this->mDaysWorked, $this->mEarnings, $this->mSundaysWorked));
        }
    }

    // Check if the current year has been booked already
    private function checkYear() {
    	$app = Application::getInstance();
		$user = $app->getUser();

        $sql = "SELECT year
                FROM years
                WHERE year = :year AND userid = :userid";
        $statement = $this->mDbHandle->prepare($sql);
        $statement->bindParam(':year', date('Y'));
		$statement->bindParam(':userid', $user->getId());
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

    // Book the details of the current year and insert them into the database
    private function bookYear() {
        $app = Application::getInstance();
        $user = $app->getUser();

        $currentYear = date("Y");
        $sql = "INSERT INTO years (year, hoursWorked, daysWorked, earnings, sundaysWorked, userid)
                VALUES(:year, :hoursworked, :daysworked, :earnings, :sundaysworked, :userid)";
        $statement = $this->mDbHandle->prepare($sql);
        $statement->bindParam(':year', $currentYear);
        $statement->bindParam(':hoursworked', round($this->mHoursWorked, 1));
		$statement->bindParam(':daysworked', $this->mDaysWorked);
        $statement->bindParam(':earnings', $this->mEarnings);
        $statement->bindParam(':sundaysworked', $this->mSundaysWorked);
		$statement->bindParam(':userid', $user->getId());
        $statement->execute();

        $this->closeYear();
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

    public function getMonths() {
        return $this->mMonths;
    }

    public function getEarnings() {
        return $this->mEarnings;
    }

    public function getTotalPayWithFees() {
        return $this->mEarnings + Application::getInstance()->getConfiguration("sunday_fee");
    }

    public function getTotalHours() {
        return $this->mHoursWorked;
    }

	public function getDaysWorked() {
		return $this->mDaysWorked;
	}

    public function getSundaysWorked() {
        return $this->mSundaysWorked;
    }

    public function draw() {
        $this->mHeader->draw();
        include "theme/inc.year-close.php";
        $this->mFooter->draw();
    }

}
