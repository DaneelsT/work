<?php

namespace Work\Page;

/**
 * A class which describes the actions and properties of the month page.
 *
 * @author  Gaetan Dumortier
 * @since   25 October 2016
 */

use \Carbon\Application\Application;
use \Work\Page\AbstractAuthorizedPage;
use \Work\Month\Month;
use \Work\UI\ViewHeader;
use \Work\UI\ViewFooter;
use \PDO;

class PageMonth extends AbstractAuthorizedPage {

    const PATH = "/month$";
    const TITLE = "Monthly Overview";

    private $mPageTitle = translate("Test");

    private $mHeader;
    private $mFooter;

    private $mDbHandle;

    private $mMonths = array();

    private function initializeViewElements() {
        $this->mHeader = new ViewHeader(self::TITLE);
        $this->mFooter = new ViewFooter();
    }

    private function initializeDatabaseConnection() {
        $app = Application::getInstance();
        $app->connectToDatabase();
        $this->mDbHandle = $app->getDatabaseConnection();
    }

    // Fetch all months and the earnings of that month
    private function fetchMonths() {
    	$app = Application::getInstance();
		$user = $app->getUser();
        $sql = "SELECT *
                FROM months
                WHERE userid = :userid
                ORDER BY month DESC";
        $statement = $this->mDbHandle->prepare($sql);
		$statement->bindParam(':userid', $user->getId());
        $statement->execute();
        $months = $statement->fetchAll(PDO::FETCH_ASSOC);
        foreach($months as $month) {
            $monthId = $month['id'];
            $monthMonth = $month['month']; // that variable naming tho.
            $monthHours = $month['hoursWorked'];
			$daysWorked = $month['daysWorked'];
            $monthEarnings = $month['earnings'];
            $monthSundays = $month['sundaysWorked'];
            // Allocate a new month instance
            array_push($this->mMonths, new Month($monthId, $monthMonth, $monthHours, $daysWorked, $monthEarnings, $monthSundays));
        }
    }

    public function __construct() {
    	parent::__construct(parent::DEFAULT_LOGIN_DIR);
        $this->setTitle(self::TITLE);
        $this->initializeViewElements();
        $this->initializeDatabaseConnection();

        $this->fetchMonths();
    }

    public function getMonths() {
        return $this->mMonths;
    }

    public function getTitle() {
        return $this->mPageTitle;
    }

    public function draw() {
        $this->mHeader->draw();
        include "theme/inc.month-overview.php";
        $this->mFooter->draw();
    }

}
