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
    private $mTitle = "Monthly Overview";

    private $mHeader;
    private $mFooter;

    private $mDbHandle;

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

    private function addScripts() {
        $this->mFooter->addScript("jquery.min.js");
        $this->mFooter->addScript("main.js");
    }

    // Fetch all months and the earnings of that month
    private function fetchMonths() {
    	$app = Application::getInstance();
		$user = $app->getUser();

        $sql = "SELECT
                    months_data.*,
                    months.month,
                    months.userid
                FROM
                    months_data
                    INNER JOIN months ON months_data.month_id = months.id
                WHERE
                    userid = :userid AND year = :year
                ORDER BY month DESC";
        $statement = $this->mDbHandle->prepare($sql);
		$statement->bindParam(':userid', $user->getId());
        $statement->bindParam(':year', date('Y'));
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
        $this->addScripts();

        $this->fetchMonths();
    }

    public function getMonths() {
        return $this->mMonths;
    }

    public function draw() {
        $this->mHeader->draw();
        include getTheme("inc.month-overview.php");
        $this->mFooter->draw();
    }

}
