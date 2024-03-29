<?php

namespace Work\Page;

/**
 * A class which describes the actions and properties of the admin page.
 *
 * @author  Gaetan Dumortier
 * @since   2 November 2016
 */

use \Carbon\Application\Application;
use \Work\Page\AbstractAuthorizedPage;
use \Work\Shift\Shift;
use \Work\User\User;
use \Work\UI\ViewHeader;
use \Work\UI\ViewFooter;
use \PDO;

class PageAdmin extends AbstractAuthorizedPage {

    const PATH = "/admin$";
    private $mTitle = "Admin";

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
        $this->mFooter->addScript("jquery.min.js");
	}

    public function __construct() {
    	if(Application::getInstance()->getUser()->isAdmin()) {
	    	parent::__construct(parent::DEFAULT_LOGIN_DIR);
	        $this->setTitle($this->mTitle);
	        $this->initializeViewElements();
	        $this->initializeDatabaseConnection();
	        $this->addScripts();
		}else{
			redirectInternally("/");
		}
	}

    public function draw() {
        $this->mHeader->draw();
        include getTheme("inc.admin.php");
        $this->mFooter->draw();
    }

}
