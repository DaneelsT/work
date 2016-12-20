<?php

namespace Work\Page;

/**
 * A class which is responsible for removing the specified shift.
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
use \PDOException;

class PageShiftRemove extends AbstractAuthorizedPage {

    const PATH = "/shift/remove/[0-9]+$";
    private $mTitle = "Remove this shift";

    private $mHeader;
    private $mFooter;

    private $mDbHandle;

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
    }

    private function removeShift() {
        $id = (int)Application::getInstance()->getRouter()->getSegment(2);
        $sql = "DELETE
                FROM shifts
                WHERE id = :id";
        $statement = $this->mDbHandle->prepare($sql);
        $statement->bindParam(":id", $id);
        $statement->execute();
    }

    public function __construct() {
    	parent::__construct(parent::DEFAULT_LOGIN_DIR);
        $this->initializeViewElements();
        $this->initializeDatabaseConnection();
        $this->addScripts();

        $this->removeShift();
        redirectInternally("/"); // redirect back to dashboard after deleting the shift
    }

    public function draw() {
    }

}
