<?php

namespace Work\Page;

/**
 * A class which describes the properties and actions of the current shifts of the requested user per id.
 *
 * @author  Gaetan Dumortier
 * @since   7 December 2016
 */

use \Carbon\Application\Application;
use \Work\Page\AbstractApiPage;
use \Work\Application\WorkApplication;
use \PDO;

class PageApiShifts extends AbstractApiPage {

    const PATH = "/api/shifts/[0-9]+$";

    private $mShifts;

    private $mId;
    private $mDbHandle;

    private function initializeDatabaseConnection() {
        $app = Application::getInstance();
        $app->connectToDatabase();
        $this->mDbHandle = $app->getDatabaseConnection();
    }

    private function returnData() {
        $this->fetchShifts();
    }

    private function fetchShifts() {
        $sql = "SELECT *
                FROM shifts
                WHERE userid = :userid;";
        $statement = $this->mDbHandle->prepare($sql);
        $statement->bindParam(":userid", $this->mId);
        $statement->execute();
        $result = $statement->fetchAll(PDO::FETCH_ASSOC);

        echo $this->encodeJSON($result, JSON_PRETTY_PRINT);
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
                $this->returnData();
                break;
            default:
                http_response_code(400);
                break;
        }
    }

}
?>
