<?php

namespace Work\Page;

/**
 * A class which describes the properties of all users in the work webinterface.
 *
 * @author  Gaetan Dumortier
 * @since   7 December 2016
 */

use \Carbon\Application\Application;
use \Work\Page\AbstractApiPage;
use \Work\Application\WorkApplication;
use \PDO;

class PageApiUsers extends AbstractApiPage {

    const PATH = "/api/users$";

    private $mDbHandle;

    private function initializeDatabaseConnection() {
        $app = Application::getInstance();
        $app->connectToDatabase();
        $this->mDbHandle = $app->getDatabaseConnection();
    }

    private function returnData()
    {
        $this->fetchAllUsers();
    }

    private function fetchAllUsers() {
        $sql = "SELECT DISTINCT id, username, email, name, surname, gender, disabled, admin, lang
                FROM
                    users,
                    users_language;";
        $statement = $this->mDbHandle->prepare($sql);
        $statement->execute();
        $result = $statement->fetchAll(PDO::FETCH_ASSOC);

        echo $this->encodeJSON($result, JSON_PRETTY_PRINT);
    }

    public function __construct()
    {
        parent::__construct();
        $this->initializeDatabaseConnection();

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
                $this->returnData();
                break;
            default:
                http_response_code(400);
                break;
        }
    }

}
?>
