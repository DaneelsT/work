<?php

namespace Work\Page;

/**
 * A class which is responsible for editing the properties of the specified shift.
 *
 * @author  Gaetan Dumortier
 * @since   24 October 2016
 */

use \Carbon\Application\Application;
use \Work\Page\AbstractAuthorizedPage;
use \Work\Shift\Shift;
use \Work\UI\ViewHeader;
use \Work\UI\ViewFooter;
use \PDO;

class PageShiftEdit extends AbstractAuthorizedPage {

    const PATH = "/shift/edit/[0-9]+$";

    private $mHeader;
    private $mFooter;

    private $mDbHandle;

    private $mShifts = array();
    private $mNumShifts;

    private $mShift;

    private function initializeViewElements() {
        // Check if a shift has been found.
        if ($this->mShift == null)
            $title = "Specified shift not found";
        else
            $title = "Edit shift from " . $this->mShift->getDate();
        $this->mHeader = new ViewHeader($title);
        $this->mFooter = new ViewFooter();
    }

    private function initializeDatabaseConnection() {
        $app = Application::getInstance();
        $app->connectToDatabase();
        $this->mDbHandle = $app->getDatabaseConnection();
    }

    private function addScripts() {
        $this->mFooter->addScript("jquery.min.js");
        $this->mFooter->addScript("jquery.mask.min.js");
        $this->mFooter->addScript("main.js");
    }

    private function fetchShift() {
        $id = (int)Application::getInstance()->getRouter()->getSegment(2);
        $sql = "SELECT *
				FROM shifts
				WHERE id = :id";
        $statement = $this->mDbHandle->prepare($sql);
        $statement->bindParam(":id", $id);
        $statement->execute();
        $shift = $statement->fetch();
        if ($shift != null) {
            $id = $shift['id'];
            $date = $shift['date'];
            $starttime = $shift['startTime'];
            $endtime = $shift['endTime'];
			$isSunday = $shift['isSunday'];
            $this->mShift = new Shift($id, $date, $starttime, $endtime, $isSunday);
        }
    }

    private function editShift() {
        $id = Application::getInstance()->getRouter()->getSegment(2);
        $date = $_POST['date'];
        $rawStartTime = explode(':', $_POST['startTime']);
        $rawEndTime = explode(':', $_POST['endTime']);
				
        $startTimeHour = $rawStartTime[0] * 60 * 60;
        $startTimeMinute = $rawStartTime[1] * 60;
        $startTime = $startTimeHour + $startTimeMinute;

        $endTimeHour = $rawEndTime[0] * 60 * 60;
        $endTimeMinute = $rawEndTime[1] * 60;
        $endTime = $endTimeHour + $endTimeMinute;
		
		if($_POST['holiday']) {
			$isHoliday = true;
		}else{
			$isHoliday = false;
		}

        // Make sure the fields are entered before proceeding
        if (strlen($startTimeHour) == 0 || strlen($startTimeMinute) == 0 || strlen($endTimeHour) == 0 || strlen($endTimeMinute) == 0)
            return;
        // TODO: add error reporting

        $sql = "UPDATE shifts
				SET
					date = :date,
					startTime = :starttime,
					endTime = :endtime,
					isSunday = :sunday
				WHERE id = :id";
        $statement = $this->mDbHandle->prepare($sql);
        $statement->bindParam(":date", $date);
        $statement->bindParam(":starttime", $startTime);
        $statement->bindParam(":endtime", $endTime);
		$statement->bindParam(":sunday", $isHoliday);
        $statement->bindParam(":id", $id);
        $statement->execute();

        redirectInternally("/"); // redirect back to dashboard after shift has been modified.
    }

    public function getShift() {
        return $this->mShift;
    }

    public function __construct() {
    	parent::__construct(parent::DEFAULT_LOGIN_DIR);    	
        $this->initializeDatabaseConnection();

        if (isset($_POST['edit_shift'])) {
            $this->editShift();
        } else {
            $this->fetchShift();
        }

        $this->initializeViewElements();
        $this->addScripts();
    }

    public function draw() {
        $this->mHeader->draw();
        include "theme/inc.shift-edit.php";
        $this->mFooter->draw();
    }

}
