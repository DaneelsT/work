<?php

namespace Work\Page;

/**
 * A class which is responsible for displaying the properties of the logged in user.
 *
 * @author  Gaetan Dumortier
 * @since   2 November 2016
 */

use \Carbon\Application\Application;
use \Work\Application\WorkApplication;
use \Work\Page\AbstractAuthorizedPage;
use \Work\UI\ViewHeader;
use \Work\UI\ViewFooter;
use \Work\User\User;
use \PDO;

class PageProfile extends AbstractAuthorizedPage
{

    const PATH = "/profile$";
	const TITLE = "My Profile";

    private $mHeader;
    private $mFooter;
	
	private $mDbHandle;

    private $mUser = null;
    private $mUserUpdated = false;
    private $mInvalidInput = false;

    private function initializeViews()
    {		
		$this->mHeader = new ViewHeader(self::TITLE);
		$this->mFooter = new ViewFooter();
    }
	
    private function initializeDatabaseConnection() {
        $app = Application::getInstance();
        $app->connectToDatabase();
        $this->mDbHandle = $app->getDatabaseConnection();
    }

    private function fetchUser() {
    	$id = Application::getInstance()->getUser()->getId();
        $sql = "SELECT *
                FROM
                    users,
                    users_pay,
                    users_language
                WHERE users.id = :user_id AND users_pay.userid = :user_id";
        $statement = $this->mDbHandle->prepare($sql);
        $statement->bindParam(':user_id', $id);
        $statement->execute();
        $user = $statement->fetch();
        if( $user != null ) {
            $id = $user['id'];
            $username = $user['username'];
            $email = $user['email'];
            $name = $user['name'];
            $surname = $user['surname'];
            $gender = $user['gender'];
            $disabled = (int) $user['disabled'];
			$admin = (int) $user['admin'];
			$pay = $user['hourly_pay'];
			$sundayfee = $user['sunday_fee'];
            $lang = $user['lang'];
            $this->mUser = new User($id, $username, $email, $name, $surname, $gender, $pay, $sundayfee, $disabled, $admin, $lang);
        }
    }

	private function editUser($name, $surname, $email, $gender, $pay, $fee, $lang)
    {
    	$id = Application::getInstance()->getUser()->getId();
        $app = Application::getInstance();
        $user = $app->getUser();
        
		$sql = "UPDATE users
				SET
				    email = :email,
    				name = :name,
    				surname = :surname,
    				gender = :gender
				WHERE id = :userid";
        $statement = $this->mDbHandle->prepare($sql);
        $statement->bindParam(':email', $email);
        $statement->bindParam(':name', $name);
        $statement->bindParam(':surname', $surname);
        $statement->bindParam(':gender', $gender);
        $statement->bindParam(':userid', $id);
        $statement->execute();
		// Update payment info and language if needed
		$sql = "UPDATE
		          users_pay,
		          users_language
				SET
					hourly_pay = :hourly_pay,
					sunday_fee = :sunday_fee,
					lang = :lang
				WHERE users_pay.userid = :userid";
		$statement = $this->mDbHandle->prepare($sql);
		$statement->bindParam(":hourly_pay", $pay);
		$statement->bindParam(":sunday_fee", $fee);
        $statement->bindParam(":lang", $lang);
		$statement->bindParam(":userid", $id);
		$statement->execute();
        // Assign values to the user
        $user->setName($name);
        $user->setSurName($surname);
        $user->setEmail($email);
        $user->setGender($gender);
		$user->setHourlyPay($pay);
		$user->setSundayFee($fee);
        $user->setLanguage($lang);
		
        $this->fetchUser();
        $this->mUserUpdated = true;
    }
    
    private function verifyInput() {
        $name = $_POST['user_name'];
        $surname = $_POST['user_surname'];
        $email = $_POST['user_email'];
        $gender = $_POST['user_gender'];
        $pay = $_POST['hourly_pay'];
        $fee = $_POST['sunday_fee'];
        // Work out the language code in a very noob-ish way, lol
        if($_POST['language'] == 'Nederlands') {
            $lang = 'nl_BE';
        }elseif($_POST['language'] == 'English') {
            $lang = 'en_US';
        }else{
            $lang = Application::getInstance()->getConfiguration("default_lang");
        }
        
        // Verify that all fields are entered
        if(strlen($name) == 0 || strlen($surname) == 0 || strlen($email) == 0 || strlen($pay) == 0 || strlen($fee) == 0 || !isset($lang)) {
            $this->mInvalidInput = true;
            return;
        }else{
            // Verify that the email is a correctly formatted email address
            if(validEmail($email))
                $this->editUser($name, $surname, $email, $gender, $pay, $fee, $lang);
        }
    }

    private function hasUser()
    {
        return ( $this->mUser != null );
    }

    private function getUser()
    {
        return $this->mUser;
    }
	
	private function userUpdated()
    {
        return $this->mUserUpdated;
    }
    
    private function invalidInput() {
        return $this->mInvalidInput;
    }

    public function __construct()
    {
        parent::__construct(parent::DEFAULT_LOGIN_DIR);
        $this->initializeViews();
		$this->initializeDatabaseConnection();
		
		if(isset($_POST['edit_user'])) {
			$this->verifyInput();
		}else{
        	$this->fetchUser();
        }
    }

    public function draw()
    {
        $this->mHeader->draw();
        include "theme/inc.profile.php";
        $this->mFooter->draw();
    }

}