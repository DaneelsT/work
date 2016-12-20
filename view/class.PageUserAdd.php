<?php

namespace Work\Page;

/**
 * A class which is responsible for adding a new user to the Work Webinterface.
 *
 * @author  Joeri Hermans, Gaetan Dumortier
 * @since   1 November 2016
 */

use \Carbon\Application\Application;
use \Work\Application\WorkApplication;
use \Work\Page\AbstractAuthorizedPage;
use \Work\User\User;
use \Work\UI\ViewHeader;
use \Work\UI\ViewFooter;

class PageUserAdd extends AbstractAuthorizedPage
{
    const PATH = "/user/add$";
    private $mTitle = "Add a user";

    private $mHeader;
    private $mFooter;

	private $mDbHandle;

    private $mUsername = "";
    private $mName = "";
    private $mSurname = "";
    private $mGender;
    private $mEmail = "";
    private $mPassword = "";
    private $mRPassword = "";
	private $mIp;

    private $mUserAdded = false;
    private $mInvalidInput = false;
    private $mExistingUser = false;

    private function initializeViews() {
        $this->mHeader = new ViewHeader($this->mTitle);
        $this->mFooter = new ViewFooter();
    }

    private function initializeDatabaseConnection() {
        $app = Application::getInstance();
        $app->connectToDatabase();
        $this->mDbHandle = $app->getDatabaseConnection();
    }

    private function checkData()
    {
		$sql = "SELECT id
				FROM users
				WHERE username = :username
				OR email = :email";
		$statement = $this->mDbHandle->prepare($sql);
		$statement->bindParam(":username", $this->mUsername);
		$statement->bindParam(":email", $this->mEmail);
        $result = $statement->fetch();
        $count = count($result);
        // Check if a user with this username or email doesn't already exist
        if($count == 0) {
            $this->addUser();
        }else{
            $this->mExistingUser = true;
            return;
        }
    }

    private function addUser()
    {
        $sql = "INSERT INTO
                users (username, password, email, name, surname, gender, last_ip)
                VALUES (:username, :password, :email, :name, :surname, :gender, :lastip);";
		$statement = $this->mDbHandle->prepare($sql);
		$statement->bindParam(":username", $this->mUsername);
		$statement->bindParam(":password", $this->mPassword);
		$statement->bindParam(":email", $this->mEmail);
		$statement->bindParam(":name", $this->mName);
		$statement->bindParam(":surname", $this->mSurname);
		$statement->bindParam(":gender", $this->mGender);
		$statement->bindParam(":lastip", $this->mIp);
        $statement->execute();

        $this->mUserAdded = true;
        // TODO: add success message
    }

    private function verifyInput()
    {
        $this->mUsername = $_POST['user_username'];
        $this->mName = $_POST['user_name'];
        $this->mSurname = $_POST['user_surname'];
        $this->mGender = $_POST['user_gender'];
        $this->mEmail = $_POST['user_email'];
        $this->mPassword = hashString($_POST['user_password']);
        $this->mRPassword = hashString($_POST['user_password_repeat']);
        $this->mIp = $_POST['user_ip'];

        // Check if all fields have valid entries
        if( strlen($this->mUsername) == 0 || strlen($this->mName) == 0 || strlen($this->mSurname) == 0 || strlen($this->mEmail) == 0 || strlen($this->mPassword) == 0 )
            $this->mInvalidInput = true;

        // Check if entered passwords match
        if( $this->mPassword != $this->mRPassword )
            $this->mInvalidInput = true;

        // Check if the entered username contains spaces
        if( preg_match('/\s/', $this->mUsername) )
            $this->mInvalidInput = true;

        // Proceed to check the entered data when no invalid inputs were found
        if( !$this->invalidInput() )
            $this->checkData();
    }

    private function userAdded()
    {
        return $this->mUserAdded;
    }

    private function invalidInput()
    {
        return $this->mInvalidInput;
    }

    private function existingUser()
    {
        return $this->mExistingUser;
    }

    public function __construct()
    {
        parent::__construct(parent::DEFAULT_LOGIN_DIR);
        $this->setTitle($this->mTitle);
        $this->initializeViews();
		$this->initializeDatabaseConnection();

        if( isset($_POST['add_user']) ) {
            $this->verifyInput();
        }
    }

    public function draw()
    {
        $this->mHeader->draw();
        include "theme/inc.user-add.php";
        $this->mFooter->draw();
    }
}
