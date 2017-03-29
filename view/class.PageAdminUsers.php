<?php

namespace Work\Page;

/**
 * A class which describes the actions and properties of the users overview page.
 *
 * @author  Gaetan Dumortier
 * @since   2 November 2016
 */

use \Carbon\Application\Application;
use \Work\Page\AbstractAuthorizedPage;
use \Work\User\User;
use \Work\UI\ViewHeader;
use \Work\UI\ViewFooterNoFooter;
use \PDO;

class PageAdminUsers extends AbstractAuthorizedPage {

    const PATH = "/admin/users$";
    private $mTitle = "User Overview";

    private $mHeader;
    private $mFooter;

	private $mUsers = array();

    private $mDbHandle;

    private function initializeViewElements() {
        $this->mHeader = new ViewHeader($this->mTitle);
        $this->mFooter = new ViewFooterNoFooter();
    }

    private function initializeDatabaseConnection() {
        $app = Application::getInstance();
        $app->connectToDatabase();
        $this->mDbHandle = $app->getDatabaseConnection();
    }

    private function addScripts() {
        $this->mFooter->addScript("jquery.min.js");
	}

	private function fetchAllUsers() {
		$sql = "SELECT *
				FROM users";
		$statement = $this->mDbHandle->prepare($sql);
		$statement->execute();
		$users = $statement->fetchAll(PDO::FETCH_ASSOC);
		foreach($users as $user) {
			$id = $user['id'];
			$username = $user['username'];
			$email = $user['email'];
			$name = $user['name'];
			$surname = $user['surname'];
			$gender = (int) $user['gender'];
			$disabled = (int) $user['disabled'];
			$last_ip = $user['last_ip'];
			$admin = $user['admin'];
			// Allocate a new user instance.
			array_push($this->mUsers, new User($id, $username, $email, $name, $surname, $gender, $disabled, $admin));
		}
	}

    public function __construct() {
    	if(Application::getInstance()->getUser()->isAdmin()) {
	    	parent::__construct(parent::DEFAULT_LOGIN_DIR);
	        $this->setTitle($this->mTitle);
	        $this->initializeViewElements();
	        $this->initializeDatabaseConnection();
	        $this->addScripts();

			$this->fetchAllUsers();
		}else{
			redirectInternally("/");
		}
	}

	public function getUsers() {
		return $this->mUsers;
	}

    public function draw() {
        $this->mHeader->draw();
        include getTheme("inc.admin-users.php");
        $this->mFooter->draw();
    }

}
