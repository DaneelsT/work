<?php

namespace Work\Page;

/**
 * A class which describes the actions and properties of the year page.
 *
 * @author  Gaetan Dumortier
 * @since   3 February 2017
 */

use \Carbon\Application\Application;
use \Work\Page\AbstractAuthorizedPage;
use \Work\Year\Year;
use \Work\UI\ViewHeader;
use \Work\UI\ViewFooter;
use \PDO;

class PageYear extends AbstractAuthorizedPage {

    const PATH = "/year$";
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

    // Fetch all years and the earnings of these years
    private function fetchYears() {
    	$app = Application::getInstance();
		$user = $app->getUser();
        $sql = "SELECT *
                FROM years
                WHERE userid = :userid
                ORDER BY year DESC";
        $statement = $this->mDbHandle->prepare($sql);
		$statement->bindParam(':userid', $user->getId());
        $statement->execute();
        $months = $statement->fetchAll(PDO::FETCH_ASSOC);
        foreach($years as $year) {
            $id = $year['id'];
            $yearId = $year['year'];
            $hoursWorked = $year['hoursWorked'];
            $daysWorked = $year['daysWorked'];
            $earnings = $year['earnings'];
            $sundays = $year['sundaysWorked'];
            // Allocate a new year instance
            array_push($this->mYears, new Year($id, $yearId, $hoursWorked, $daysWorked, $earnings, $sundays));
        }
    }

    public function __construct() {
    	parent::__construct(parent::DEFAULT_LOGIN_DIR);
        $this->setTitle($this->mTitle);
        $this->initializeViewElements();
        $this->initializeDatabaseConnection();

        $this->fetchYears();
    }

    public function getYears() {
        return $this->mYears;
    }

    public function draw() {
        $this->mHeader->draw();
        include "theme/inc.year-overview.php";
        $this->mFooter->draw();
    }

}
