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
                    COUNT(months_data.id) AS totalmonths,
                    SUM(months_data.hoursWorked) AS hoursworked,
                    SUM(months_data.daysWorked) AS daysworked,
                    SUM(months_data.earnings) AS earnings,
                    SUM(months_data.sundaysWorked) AS sundaysworked
                FROM
                    months_data
                	INNER JOIN months ON months_data.month_id = months.id
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

        $sql = "SELECT year
                FROM years
                WHERE
                    year = :year
                AND
                    userid = :userid";
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
            $this->fetchAllMonths();
        }
    }

    // Insert the fetched data
    private function bookYear() {
        $app = Application::getInstance();
        $user = $app->getUser();

        $sql = "INSERT INTO years (year, userid) VALUES (:year, :userid)";
        $stmt1 = $this->mDbHandle->prepare($sql);
        $stmt1->bindParam(':year', date('Y'));
        $stmt1->bindParam(':userid', $user->getId());
        $stmt1->execute();
        $id = $this->mDbHandle->lastInsertId();

        $sql2 = "INSERT INTO years_data (year_id, monthsWorked, hoursWorked, daysWorked, sundaysWorked, earnings)
                VALUES (:year_id, :monthsWorked, :hoursWorked, :daysWorked, :sundaysWorked, :earnings)";
        $stmt2 = $this->mDbHandle->prepare($sql2);
        $stmt2->bindParam(':year_id', $id);
        $stmt2->bindParam(':monthsWorked', $this->mMonthsWorked);
        $stmt2->bindParam(':hoursWorked', $this->mHoursWorked);
        $stmt2->bindParam(':daysWorked', $this->mDaysWorked);
        $stmt2->bindParam(':sundaysWorked', $this->mSundaysWorked);
        $stmt2->bindParam(':earnings', $this->mEarnings);
        $stmt2->execute();

        // $this->closeYear();
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
