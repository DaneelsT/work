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

    // Fetch all the months from the database
    private function fetchAllMonths() {
        $app = Application::getInstance();
        $user = $app->getUser();

        $sql = "SELECT
                    COUNT(months.month) AS totalmonths,
                    SUM(months.hoursWorked) AS hoursworked,
                    SUM(months.daysWorked) AS daysworked,
                    SUM(months.earnings) AS earnings,
                    SUM(months.sundaysWorked) AS sundaysworked
                FROM months
                WHERE userid = :userid";
        $statement = $this->mDbHandle->prepare($sql);
        $statement->bindParam(':userid', $user->getId());
        $statement->execute();
        $months = $statement->fetchAll(PDO::FETCH_ASSOC);
        foreach($months as $month) {
            $this->mMonthsWorked = $month['totalmonths'];
            $this->mHoursWorked = $month['hoursworked'];
            $this->mDaysWorked = $month['daysworked'];
            $this->mEarnings = $month['earnings'];
            $this->mSundaysWorked = $month['sundaysworked'];
        }

        $this->bookYear();
    }

    // Check if the current year hasnt been booked yet
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
            $this->fetchAllMonths();
        }
    }

    // Insert the fetched data
    private function bookYear() {
        $app = Application::getInstance();
        $user = $app->getUser();

        $sql = "INSERT INTO
                years(year, hoursWorked, daysWorked, earnings, sundaysWorked, userid)
                VALUES(:year, :hoursWorked, :daysWorked, :earnings, :sundaysWorked, :userid)";
        $statement = $this->mDbHandle->prepare($sql);
        $statement->bindParam(':year', date('Y'));
        $statement->bindParam(':hoursWorked', $this->mHoursWorked);
        $statement->bindParam(':daysWorked', $this->mDaysWorked);
        $statement->bindParam(':earnings', $this->mEarnings);
        $statement->bindParam(':sundaysWorked', $this->mSundaysWorked);
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
        // $statement->execute();
    }

    public function __construct() {
    	parent::__construct(parent::DEFAULT_LOGIN_DIR);
        $this->setTitle($this->mTitle);
        $this->initializeViewElements();
        $this->initializeDatabaseConnection();

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

    public function getEarningsWithFees() {
        return $this->mEarnings + ($this->mSundaysWorked * Application::getInstance()->getConfiguration("sunday_fee"));
    }

    public function getSundaysWorked() {
        return $this->mSundaysWorked;
    }

    public function draw() {
        $this->mHeader->draw();
        include getTheme("inc.year-close.php");
        $this->mFooter->draw();
    }

}
