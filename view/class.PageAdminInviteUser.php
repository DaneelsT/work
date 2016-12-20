<?php

namespace Work\Page;

/**
 * A class which describes the actions and properties of the invite user page.
 *
 * @author  Gaetan Dumortier
 * @since   2 November 2016
 */

use \Carbon\Application\Application;
use \Work\Page\AbstractAuthorizedPage;
use \Work\User\User;
use \Work\UI\ViewHeader;
use \Work\UI\ViewFooter;
use \PDO;

class PageAdminInviteUser extends AbstractAuthorizedPage {

    const PATH = "/admin/invite$";
    private $mTitle = "Invite a user";

    private $mHeader;
    private $mFooter;

    private $mDbHandle;

    private $mInvalidEmail = false;
    private $mInviteSent = false;

    private $mToken;
    private $mEmail;

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

    private function verifyEmail() {
        if(strlen($_POST['user_name']) == 0 || strlen($_POST['user_email']) == 0)
            return;

        if(validEmail($_POST['user_email'])) {
            $this->inviteUser();
        }else{
            $this->mInvalidEmail = true;
            return;
        }
    }

    private function inviteUser() {
        $this->mEmail = $_POST['user_email'];
        $name = $_POST['user_name'];
        $this->mToken = generateToken();

        $this->addToken();
        mailToken($this->mEmail, $name, $this->mToken);
        $this->mInviteSent = true;
    }

    private function addToken() {
        $sql = "INSERT INTO
                tokens (token, email)
                VALUES (:token, :email)";
        $statement = $this->mDbHandle->prepare($sql);
        $statement->bindParam(":token", $this->mToken);
        $statement->bindParam(":email", $this->mEmail);
        $statement->bindParam(":lang", $this->mLanguage);
        $statement->execute();
    }

    public function __construct() {
        if(Application::getInstance()->getUser()->isAdmin()) {
            parent::__construct(parent::DEFAULT_LOGIN_DIR);
            $this->setTitle($this->mTitle);
            $this->initializeViewElements();
            $this->initializeDatabaseConnection();
            $this->addScripts();

            if(isset($_POST['send_invite']))
                $this->verifyEmail();
        }else{
            redirectInternally("/");
        }
    }

    public function getUsers() {
        return $this->mUsers;
    }

    public function invalidEmail() {
        return $this->mInvalidEmail;
    }

    public function inviteSent() {
        return $this->mInviteSent;
    }

    public function draw() {
        $this->mHeader->draw();
        include "theme/inc.admin-invite.php";
        $this->mFooter->draw();
    }

}
