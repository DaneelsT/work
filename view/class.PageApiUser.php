<?php

namespace Work\Page;

/**
 * A class which describes the properties of the requested user per id.
 *
 * @author  Gaetan Dumortier
 * @since   7 December 2016
 */

use \Carbon\Application\Application;
use \Work\Page\AbstractApiPage;
use \Work\Application\WorkApplication;
use \PDO;

class PageApiUser extends AbstractApiPage {

    const PATH = "/api/user/[0-9]+$";
    
    private $mUsers;
    
    private $mId;
    private $mDbHandle;
    
    private function initializeDatabaseConnection() {
        $app = Application::getInstance();
        $app->connectToDatabase();
        $this->mDbHandle = $app->getDatabaseConnection();
    }
        
    private function returnUser() {
        $this->fetchUser();
        $data = $this->mUsers;
        
        echo $this->encodeJSON($data, JSON_PRETTY_PRINT);
    }
    
    private function fetchUser() {
        $sql = "SELECT *
                FROM
                    users,
                    user_languages
                WHERE id = :userid";
        $statement = $this->mDbHandle->prepare($sql);
        $statement->bindParam(":userid", $this->mId);
        $statement->execute();
        $users = $statement->fetchAll(PDO::FETCH_ASSOC);
        if(count($users) == 0) {
            http_response_code(204);
            exit();
        }
        
        foreach($users as $user) {
            $id = $user['id'];
            $username = $user['username'];
            $email = $user['email'];
            $name = $user['name'];
            $surname = $user['surname'];
            $gender = (bool) $user['gender'];
            $disabled = (bool) $user['disabled'];
            $last_ip = $user['last_ip'];
            $admin = $user['admin'];
            $lang = $user['lang'];
            // Push user details to array
            $this->mUsers = array(
                "id" => $id,
                "username" => $username,
                "email" => $email,
                "name" => $name,
                "surname" => $surname,
                "gender" => $gender,
                "disabled" => $disabled,
                "admin" => $admin,
                "lang" => $lang
            );
        }
    }

    public function __construct()
    {
        parent::__construct();
        $this->initializeDatabaseConnection();
        
        $this->mId = Application::getInstance()->getRouter()->getSegment(2);
        
        $apikey = $this->getApiKey();
        if( !isset($apikey) || strlen($apikey) == 0 ) {
            http_response_code(500);
            exit;
        }
    }

    public function draw()
    {
        $httpMethod = $this->getRequestMethod();
        switch($httpMethod) {
            case "GET":
                $this->returnUser();
                break;
            default:
                http_response_code(400);
                break;
        }
    }
    
}
?>
