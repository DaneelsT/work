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

    // Fetch all the months from the database
    private function fetchAllMonths() {
        $app = Appliation::getInstance();
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
        $months = $statement->fetchAll(PDO::FETCH_ASS0C);
        foreach($result as $month) {
            $this->mMonthsWorked = $month['totalmonths'];
            $this->mHoursWorked = $month['hoursworked'];
            $this->mDaysWorked = $month['daysworked'];
            $this->mEarnings = $month['earnings'];
            $this->mSundaysWorked = $month['sundaysworked'];
        }
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
            $this->bookYear();
        }
    }

    // Insert the fetched data
    private function bookYear() {
        $app = Application::getInstance();
        $user = $app->getUser();

        try{
            $sql = "INSERT INTO
                        years(year, hoursWorked, daysWorked, earnings, sundaysWorked, userid),
                        VALUES(:year, :hoursworked, :daysworked, :earnings, :sundaysworked, :userid)";
            $statement = $this->mDbHandle->prepare($sql);
            $statement->bindParam(':year', date('Y'));
            $statement->bindParam(':hoursworked', $this->mHoursWorked);
            $statement->bindParam(':daysworked', $this->mDaysWorked);
            $statement->bindParam(':earnings', $this->mEarnings);
            $statement->bindParam(':sundaysworked', $this->mSundaysWorked);
            $statement->bindParam(':userid', $user->getId());
            $statement->execute();
        }catch(Exception $e) {
            die("Error executing bookYear()");
        }finally{
            $this->closeYear();
        }
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
