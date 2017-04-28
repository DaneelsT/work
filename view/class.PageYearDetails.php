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
    private $mTitle = "Test";

    private $mHeader;
    private $mFooter;

    private $mDbHandle;

    private $mYear = null;
    private $mMonths = array();

    private $mYearNotFound = false;

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
    }

    private function fetchMonths() {
        $app = Application::getInstance();
        $id = $app->getRouter()->getSegment(2);
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
        if(count($months) == 0) {
            $this->mYearNotFound = true;
            return;
        }else{
            foreach($months as $m) {
                $id = $m['id'];
                $month = $m['month'];
                $hoursWorked = $m['hoursWorked'];
                $daysWorked = $m['daysWorked'];
                $sundaysWorked = $m['sundaysWorked'];
                $earnings = $m['earnings'];
                // Allocate a new month instance
                array_push($this->mMonths, new Month($id, $month, $hoursWorked, $daysWorked, $earnings, $sundaysWorked));
            }
        }
    }

    public function __construct() {
        parent::__construct(parent::DEFAULT_LOGIN_DIR);
        $this->setTitle($this->mTitle);
        $this->initializeViewElements();
        $this->initializeDatabaseConnection();
        $this->addScripts();

        $this->mYear = Application::getInstance()->getRouter()->getSegment(2);
        $this->fetchMonths();
    }

    public function yearNotFound() {
        return $this->mYearNotFound;
    }

    public function getYear() {
        return $this->mYear;
    }

    public function getMonths() {
        return $this->mMonths;
    }

    public function draw() {
        $this->mHeader->draw();
        include getTheme("inc.year-details.php");
        $this->mFooter->draw();
    }

}
