<?php

namespace Work\Page;

/**
 * A class which describes the actions and properties of the year details page.
 *
 * @author  Gaetan Dumortier
 * @since   28 April 2017
 */

use \Carbon\Application\Application;
use \Work\Page\AbstractAuthorizedPage;
use \Work\Month\Month;
use \Work\UI\ViewHeader;
use \Work\UI\ViewFooter;
use \PDO;

class PageYearDetails extends AbstractAuthorizedPage {

    const PATH = "/years/details/[0-9]+$";
    private $mTitle = "Year Details";

    private $mHeader;
    private $mFooter;

    private $mDbHandle;

    private $mNonExistent = false;
    private $mYear;

    private $mMonths = array();

    private function initializeViewElements() {
        $this->mHeader = new ViewHeader($this->mTitle);
        $this->mFooter = new ViewFooter();
    }

    private function initializeDatabaseConnection() {
        $app = Application::getInstance();
        $app->connectToDatabase();
        $this->mDbHandle = $app->getDatabaseConnection();
    }

    // Make sure the requested year is actually booked by this user
    private function checkYear() {
        $app = Application::getInstance();
        $id = (int) $app->getRouter()->getSegment(2);
        $user = $app->getUser();

        $sql = "SELECT months.year
                FROM months
                WHERE userid = :userid AND year = :year";
        $statement = $this->mDbHandle->prepare($sql);
        $statement->bindParam(':userid', $user->getId());
        $statement->bindParam(':year', $id);
        $statement->execute();
        $result = $statement->fetchAll(PDO::FETCH_ASSOC);
        $count = count($result);
        if($count == 0) {
            $this->mNonExistent = true;
            return;
        }else{
            $this->fetchAllMonths();
        }
    }

    // Fetch all the months worked in the requested year
    private function fetchAllMonths() {
        $app = Application::getInstance();
        $id = (int) $app->getRouter()->getSegment(2);
        $user = $app->getUser();

        $sql = "SELECT
                months.month,
                months_data.*
            FROM
                months_data
                INNER JOIN months ON months.id = months_data.month_id
            WHERE userid = :userid
            AND year = :year";
        $statement = $this->mDbHandle->prepare($sql);
        $statement->bindParam(':userid', $user->getId());
        $statement->bindParam(':year', $id);
        $statement->execute();
        $months = $statement->fetchAll(PDO::FETCH_ASSOC);
        foreach($months as $month) {
            $monthId = $month['id'];
            $monthMonth = $month['month'];
            $monthHours = $month['hoursWorked'];
			$daysWorked = $month['daysWorked'];
            $monthSundays = $month['sundaysWorked'];
            $monthEarnings = $month['earnings'];
            // Allocate a new month instance
            array_push($this->mMonths, new Month($monthId, $monthMonth, $monthHours, $daysWorked, $monthEarnings, $monthSundays));
        }
    }

    public function __construct() {
    	parent::__construct(parent::DEFAULT_LOGIN_DIR);
        $this->setTitle($this->mTitle);
        $this->initializeViewElements();
        $this->initializeDatabaseConnection();

        $this->checkYear();
    }

    public function getMonths() {
        return $this->mMonths;
    }

    public function getYear() {
        return (int) Application::getInstance()->getRouter()->getSegment(2);
    }

    public function yearNotFound() {
        return ($this->mNonExistent);
    }

    public function draw() {
        $this->mHeader->draw();
        include getTheme("inc.year-details.php");
        $this->mFooter->draw();
    }

}
