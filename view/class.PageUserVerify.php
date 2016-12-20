<?php

namespace Work\Page;

/**
 * A class which describes the actions and properties of the user verify page.
 *
 * @author  Gaetan Dumortier
 * @since   2 November 2016
 */

use \Carbon\Application\Application;
use \Carbon\Page\AbstractPage;
use \Work\User\User;
use \Work\UI\ViewHeaderNoMenu;
use \Work\UI\ViewFooter;
use \PDO;

class PageUserVerify extends AbstractPage {

    const PATH = "/user/verify/[0-z]+$";
    private $mTitle = "Verify Account";

    private $mHeader;
    private $mFooter;

    private $mDbHandle;

    private $mToken;

    private $mUsername;
    private $mEmail;
    private $mPay;
    private $mFee;
    private $mLanguage;

    private $mInvalidToken = false;
    private $mVerified = false;
    private $mUserAdded = false;
    private $mExistingUser = false;
    private $mInvalidInput = false;

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

    private function verifyToken() {
        $sql = "SELECT token
                FROM tokens
                WHERE token = :token";
        $statement = $this->mDbHandle->prepare($sql);
        $statement->bindParam(":token", $this->mToken);
        $statement->execute();
        $result = $statement->fetch();
        if($result) {
            $this->mVerified = true;
        }else{
            $this->mInvalidToken = true;
            return;
        }
    }

    private function fetchEmail() {
        $sql = "SELECT email
                FROM tokens
                WHERE token = :token";
        $statement = $this->mDbHandle->prepare($sql);
        $statement->bindParam(":token", $this->mToken);
        $statement->execute();
        $result = $statement->fetch();
        if($result)
            return $result['email'];
    }

    private function deleteToken() {
        $sql = "DELETE FROM tokens
                WHERE token = :token";
        $statement = $this->mDbHandle->prepare($sql);
        $statement->bindParam(":token", $this->mToken);
        $statement->execute();
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

    private function addUser($username, $password, $email, $name, $surname, $gender, $lastip) {
        if(isset($_POST['hourly_pay'])) {
            $this->mPay = $_POST['hourly_pay'];
        }else{
            $this->mPay = Application::getInstance()->getConfiguration("hourly_pay");
        }
        if(isset($_POST['sunday_fee'])) {
            $this->mFee = $_POST['sunday_fee'];
        }else{
            $this->mFee = Application::getInstance()->getConfiguration("sunday_fee");
        }
        if(isset($_POST['language'])) {
            $this->mLanguage = $_POST['language'];
        }else{
            $this->mLanguage = Application::getInstance()->getConfiguration("default_lang");
        }

        $sql = "INSERT INTO
                users (username, password, email, name, surname, gender, last_ip)
                VALUES (:username, :password, :email, :name, :surname, :gender, :lastip)";
        $statement = $this->mDbHandle->prepare($sql);
        $statement->bindParam(":username", $username);
        $statement->bindParam(":password", $password);
        $statement->bindParam(":email", $email);
        $statement->bindParam(":name", $name);
        $statement->bindParam(":surname", $surname);
        $statement->bindParam(":gender", $gender);
        $statement->bindParam(":lastip", $lastip);
        $statement->execute();

        $this->addPaymentInfo();
        $this->addLanguage();
        $this->deleteToken();
        $this->mUserAdded = true;
    }

    private function addPaymentInfo() {
        $id = $this->fetchUserId();
        $sql = "INSERT INTO
                users_pay (userid, hourly_pay, sunday_fee)
                VALUES (:userid, :hourlypay, :sundayfee)";
        $statement = $this->mDbHandle->prepare($sql);
        $statement->bindParam(":userid", $id);
        $statement->bindParam(":hourlypay", $this->mPay);
        $statement->bindParam(":sundayfee", $this->mFee);
        $statement->execute();
    }

    private function addLanguage() {
        $id = $this->fetchUserId();
        $sql = "INSERT INTO
                    users_language (userid, lang)
                VALUES (:userid, :lang)";
        $statement = $this->mDbHandle->prepare($sql);
        $statement->bindParam(":userid", $id);
        $statement->bindParam(":lang", $this->mLanguage);
        $statement->execute();
    }

    private function fetchUserId() {
        $sql = "SELECT id
                FROM users
                WHERE username = :username";
        $statement = $this->mDbHandle->prepare($sql);
        $statement->bindParam(":username", $this->mUsername);
        $statement->execute();
        $result = $statement->fetch();
        if($result)
            return $result['id'];
    }

    private function verifyInput() {
        $this->mUsername = $_POST['user_username'];
        $password = hashString($_POST['user_password']);
        $passwordR = hashString($_POST['user_password_repeat']);
        $email = $this->mEmail;
        $name = $_POST['user_name'];
        $surname = $_POST['user_surname'];
        $gender = $_POST['user_gender'];
        $lastip = $_POST['user_ip'];

        // Make sure passwords match
        if($password != $passwordR)
            return;

        // Make sure all fields are entered
        if(strlen($this->mUsername) == 0 || strlen($password) == 0 || strlen($passwordR) == 0 || strlen($email) == 0 || strlen($name) == 0 || strlen($surname) == 0 || !isset($_POST['language'])) {
            $this->mInvalidInput = true;
            return;
        }else{
            // Verify that the email is a correctly formatted one
            if(validEmail($email)) {
                $this->addUser($this->mUsername, $password, $email, $name, $surname, $gender, $lastip);
            }else{
                $this->mInvalidInput = true;
                return;
            }
        }
    }

    public function __construct() {
        if(!Application::getInstance()->isLoggedIn()) {
            $this->setTitle($this->mTitle);
            $this->initializeViewElements();
            $this->initializeDatabaseConnection();
            $this->addScripts();

            $this->mToken = Application::getInstance()->getRouter()->getSegment(2);
            $this->mEmail = $this->fetchEmail();

            if(isset($_POST['add_user'])) {
                $this->verifyInput();
            }else{
                $this->verifyToken();
            }
        }else{
            redirectInternally("/");
        }
    }

    public function invalidToken() {
        return $this->mInvalidToken;
    }

    public function invalidInput() {
        return $this->mInvalidInput;
    }

    public function isVerified() {
        return $this->mVerified;
    }

    public function getEmail() {
        return $this->mEmail;
    }

    public function userAdded() {
        return $this->mUserAdded;
    }

    public function userExists() {
        return $this->mExistingUser;
    }

    public function draw() {
        $this->mHeader->draw();
        include "theme/inc.user-verify.php";
        $this->mFooter->draw();
    }

}
