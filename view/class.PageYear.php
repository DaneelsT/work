<?php

namespace Work\Page;

/**
 * A class which describes the actions and properties of the year page.
 *
 * @author  Gaetan Dumortier
 * @since   28 April 2017
 */

use \Carbon\Application\Application;
use \Work\Page\AbstractAuthorizedPage;
use \Work\Year\Year;
use \Work\UI\ViewHeader;
use \Work\UI\ViewFooter;
use \PDO;

class PageYear extends AbstractAuthorizedPage {

    const PATH = "/year";
    private $mTitle = "Yearly Overview";

    private $mHeader;
    private $mFooter;

    private $mDbHandle;

    private $mYears = array();

    private function initializeViewElements() {
        $this->mHeader = new ViewHeader($this->mTitle);
        $this->mFooter = new ViewFooter();
    }

    private function initializeDatabaseConnection() {
        $app = Application::getInstance();
        $app->connectToDatabase();
        $this->mDbHandle = $app->getDatabaseConnection();
    }

    // Fetch all the years and the earnings of that year
    private function fetchAllYears() {
        $app = Application::getInstance();
        $user = $app->getUser();

        $sql = "SELECT *
                FROM years
                WHERE userid = :userid
                ORDER BY year DESC";
        $statement = $this->mDbHandle->prepare($sql);
        $statement->bindParam(':userid', $user->getId());
        $statement->execute();
        $years = $statement->fetchAll(PDO::FETCH_ASSOC);
        foreach($years as $y) {
            $id = $y['id'];
            $year = $y['year'];
            $months = $y['mMonthsWorked'];
            $hours = $y['hoursWorked'];
            $days = $y['daysWorked'];
            $earnings = $y['earnings'];
            $sundays = $y['sundaysWorked'];
            // Allocate a new year instance
            array_push($this->mYears, new Year($id, $year, $months, $hours, $days, $earnings, $sundays));
        }
    }

    public function __construct() {
    	parent::__construct(parent::DEFAULT_LOGIN_DIR);
        $this->setTitle($this->mTitle);
        $this->initializeViewElements();
        $this->initializeDatabaseConnection();

        $this->fetchAllYears();
    }

    public function getYears() {
        return $this->mYears;
    }

    public function draw() {
        $this->mHeader->draw();
        include getTheme("inc.year-overview.php");
        $this->mFooter->draw();
    }

}
