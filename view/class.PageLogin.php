<?php

namespace Work\Page;

/**
 * A class which describes the properties and actions of a login page.
 *
 * @author  Joeri Hermans
 * @since   14 February 2016
 */

use \Carbon\Application\Application;
use \Carbon\Page\AbstractPage;
use \Work\User\User;
use \Work\UI\ViewHeaderNoMenu;
use \Work\UI\ViewFooter;
use \PDO;

class PageLogin extends AbstractPage
{

    const PATH = "/login$";
    private $mTitle = "Log in";

    /**
     * Additional views that need to be rendered with the login page.
     */
    private $mHeader;
    private $mFooter;

	private $mDbHandle;

	private $mUserId;
	private $mUsername;
	private $mHourlyPay;
	private $mSundayFee;
    private $mLanguage;

    private function initializeViewElements() {
        $this->mHeader = new ViewHeaderNoMenu($this->mTitle);
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

	private function addStyleSheets() {
		$this->mHeader->addStyleSheet("login.css");
	}

    private function setUsername()
    {
        if( isset( $_POST['username']) )
            $this->mUsername = $_POST['username'];
    }

	private function userLogin() {
		$application = Application::getInstance();
		// Fetch the data
		$username = $_POST['username'];
		$password = hashString($_POST['password']);
		// Prepare the query
		$sql = "SELECT *
				FROM users
				WHERE username = :username
				AND password = :password
				OR email = :username
				AND password = :password";
		$statement = $this->mDbHandle->prepare($sql);
		$statement->bindParam(':username', $username);
		$statement->bindParam(':password', $password);
		$statement->execute();
		$row = $statement->fetch();
		// Check if a hit occurred.
		if( $row ) {
			$this->mUserId = $row['id'];
			$username = $row['username'];
			$email = $row['email'];
			$name = $row['name'];
			$surname = $row['surname'];
			$gender = (int) $row['gender'];
			$disabled = (int) $row['disabled'];
			$admin = (int) $row['admin'];
			// Check if the user isn't disabled.
			if( $disabled == 0 ) {
				// Get the payment info and the language of a user or set to default when none found
				$sql = "SELECT
				            users_pay.hourly_pay,
				            users_pay.sunday_fee,
				            users_language.lang
			            FROM
			                users_pay,
			                users_language
		                WHERE users_pay.userid = :userid";
				$statement = $this->mDbHandle->prepare($sql);
				$statement->bindParam(":userid", $this->mUserId);
				$statement->execute();
				$result = $statement->fetch();
				if($result) {
					$this->mHourlyPay = $result['hourly_pay'];
					$this->mSundayFee = $result['sunday_fee'];
                    $this->mLanguage = $result['lang'];
					$this->updateUserPays();
                    $this->updateUserLanguage();
				}else{
					$this->mHourlyPay = $application->getConfiguration("hourly_pay");
					$this->mSundayFee = $application->getConfiguration("sunday_fee");
                    $this->mLanguage = $application->getConfiguration("default_lang");
					$this->addUserPays();
                    $this->addUserLanguage();
				}
				$user = new User($this->mUserId, $username, $email, $name, $surname, $gender,
				                $this->mHourlyPay, $this->mSundayFee, $disabled, $admin, $this->mLanguage);
				$application->setUser($user);
				return true;
			}else{
				$application->setUser(null);
				return false;
			}
		}else{
			$application->setUser(null);
			return false;
		}
	}

	private function addUserPays() {
		$sql = "INSERT INTO
				    users_pay (userid, hourly_pay, sunday_fee)
				VALUES (:userid, :hourly_pay, :sunday_fee)";
		$statement = $this->mDbHandle->prepare($sql);
		$statement->bindParam(":userid", $this->mUserId);
		$statement->bindParam(":hourly_pay", $this->mHourlyPay);
		$statement->bindParam(":sunday_fee", $this->mSundayFee);
		$statement->execute();
	}

    private function addUserLanguage() {
        $sql = "INSERT INTO
                    users_language (userid, lang)
                VALUES (:userid, :lang)";
        $statement = $this->mDbHandle->prepare($sql);
        $statement->bindParam(":userid", $this->mUserId);
        $statement->bindParam(":lang", $this->mLanguage);
        $statement->execute();
    }

	private function updateUserPays() {
		$sql = "UPDATE users_pay
				SET hourly_pay = :hourly_pay, sunday_fee = :sunday_fee
				WHERE userid = :userid";
		$statement = $this->mDbHandle->prepare($sql);
		$statement->bindParam(":hourly_pay", $this->mHourlyPay);
		$statement->bindParam(":sunday_fee", $this->mSundayFee);
		$statement->bindParam(":userid", $this->mUserId);
		$statement->execute();
	}

    private function updateUserLanguage() {
        $sql = "UPDATE users_language
                    SET lang = :lang
                WHERE userid = :userid";
        $statement = $this->mDbHandle->prepare($sql);
        $statement->bindParam(":userid", $this->mUserId);
        $statement->bindParam(":lang", $this->mLanguage);
        $statement->execute();
    }

    public function __construct()
    {
    	$this->initializeDatabaseConnection();

    	// Check if a POST occured
    	if($this->loggingIn() && $this->userLogin())
			redirectInternally("/");

        $this->setTitle($this->mTitle);
        $this->initializeViewElements();
		$this->addStyleSheets();
		$this->addScripts();

        $this->setUsername();
    }

    public function loggingIn()
    {
        return ( isset($_POST['username']) && isset($_POST['password']) );
    }

    public function getUsername() {
        return $this->mUsername;
    }

    public function draw()
    {
        $this->mHeader->draw();
        include "theme/inc.login.php";
        $this->mFooter->draw();
    }

}
