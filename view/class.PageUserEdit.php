<?php

namespace Work\Page;

/**
 * A class which is responsible for editing the properties of the specified user.
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
use \PDOException;

class PageUserEdit extends AbstractAuthorizedPage
{

    const PATH = "/user/edit/[0-9]+$";

    private $mHeader;
    private $mFooter;

    private $mUser = null;
    private $mRoutedId = null;
    private $mEmail;
    private $mPassword;
    private $mName;
    private $mSurname;
    private $mGender;
    private $mPay;
    private $mFee;
    private $mLanguage;

    private $mUserUpdated = false;
    private $mUserNotFound = false;

    private function initializeViewElements() {
        if($this->mUser == null)
            $title = "Specified user not found";
        else
            $title = "Edit this user";
        $this->mHeader = new ViewHeader($title);
        $this->mFooter = new ViewFooter();
    }

    private function initializeDatabaseConnection() {
        $app = Application::getInstance();
        $app->connectToDatabase();
        $this->mDbHandle = $app->getDatabaseConnection();
    }

    /**
     * Set the routed id property to the requested user's id, using the application's router
    */
    private function setRoutedId() {
        $this->mRoutedId = (int) Application::getInstance()->getRouter()->getSegment(2);
    }

    /**
     * Fetch the requested user's data from the database
    */
    private function fetchUser() {
        $sql = "SELECT *
                FROM
                    users,
                    users_pay,
                    users_language
                WHERE users.id = :userid AND users_pay.userid = :userid";
        try{
            $statement = $this->mDbHandle->prepare($sql);
            $statement->bindParam(':userid', $this->mRoutedId);
            $statement->execute();
            $user = $statement->fetch();
            if($user != null) {
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
        }catch(PDOException $e) {
            die("Error executing fetchUser: " . $e);
        }
    }

    /**
     * Modify the user's data in the database based on submitted POST data
    */
    private function editUser() {
        $sql = "UPDATE
                    users,
                    users_pay,
                    users_language
                SET
                    email = :email,
                    password = :password,
                    name = :name,
                    surname = :surname,
                    gender = :gender,
                    hourly_pay = :pay,
                    sunday_fee = :fee,
                    lang = :lang
                WHERE
                    users.id = :userid AND users_pay.userid = :userid;";
        try{
            $statement = $this->mDbHandle->prepare($sql);
            $statement->bindParam(":email", $this->mEmail);
            $statement->bindParam(":password", $this->mPassword);
            $statement->bindParam(":name", $this->mName);
            $statement->bindParam(":surname", $this->mSurname);
            $statement->bindParam(":gender", $this->mGender);
            $statement->bindParam(":pay", $this->mPay);
            $statement->bindParam(":fee", $this->mFee);
            $statement->bindParam(":lang", $this->mLanguage);
            $statement->bindParam(":userid", $this->mRoutedId);
            $statement->execute();

            $this->mUserUpdated = true;
        }catch(PDOException $e) {
            die("Error executing editUser: " . $e);
        }
    }

    /**
     * Verify the retrieved input from the submitted form and make sure its valid
    */
    private function verifyInput() {
        // Set a default value for the gender, in case its not submitted (no box ticked)
        if(!isset($_POST['gender'])) {
            $gender = 1;
        }else{
            $gender = $_POST['gender'];
        }

        // Make sure no fields are left empty
        if(strlen($_POST['email']) == 0 || strlen($_POST['name']) == 0 || strlen($_POST['surname']) == 0 || !User::isValidGender($gender)
            || strlen($_POST['hourly_pay']) == 0 || strlen($_POST['sunday_fee']) == 0 || !isset($_POST['lang']))
            return;

        // Make sure the entered passwords match
        if(hashString($_POST['password'] != hashString($_POST['password_r'])))
            return;

        // When no return has been thrown by now, set our properties and proceed with editUser
        $this->mEmail = $_POST['email'];
        $this->mPassword = hashString($_POST['password']);
        $this->mName = $_POST['name'];
        $this->mSurname = $_POST['surname'];
        $this->mGender = (int) $_POST['gender'];
        $this->mPay = $_POST['hourly_pay'];
        $this->mFee = $_POST['sunday_fee'];
        $this->mLanguage = $_POST['language'];

        $this->editUser();
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

    private function userNotFound() {
        return $this->mUserNotFound;
    }

    public function __construct()
    {
        // Make sure only an admin can view and edit profiles with another id as their own
        if(Application::getInstance()->getUser()->isAdmin()) {
            parent::__construct(parent::DEFAULT_LOGIN_DIR);
            $this->initializeDatabaseConnection();

            $this->setRoutedId();

            if( isset($_POST['edit_user'] ) ) {
                $this->verifyInput();
            }else{
                $this->fetchUser();
            }

            $this->initializeViewElements();
        }else{
            redirectInternally("/profile");
        }
    }

    public function draw()
    {
        $this->mHeader->draw();
        include getTheme("inc.user-edit.php");
        $this->mFooter->draw();
    }

}
