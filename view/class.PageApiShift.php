<?php

namespace Work\Page;

/**
 * A class which describes the properties and actions of the current shift requested per id.
 *
 * @author  Gaetan Dumortier
 * @since   7 December 2016
 */

use \Carbon\Application\Application;
use \Work\Page\AbstractApiPage;
use \Work\Application\WorkApplication;
use \PDO;

class PageApiShift extends AbstractApiPage {

    const PATH = "/api/shift/[0-9]+$";

    private $mShift;

    private $mShiftId;
    private $mDbHandle;

    private function initializeDatabaseConnection() {
        $app = Application::getInstance();
        $app->connectToDatabase();
        $this->mDbHandle = $app->getDatabaseConnection();
    }

    private function returnShift() {
        $this->fetchShift();
        $data = $this->mShift;

        echo $this->encodeJSON($data, JSON_PRETTY_PRINT);
    }

    private function fetchShift() {
        $sql = "SELECT *
                FROM shifts
                WHERE id = :id;";
        $statement = $this->mDbHandle->prepare($sql);
        $statement->bindParam(":id", $this->mShiftId);
        $statement->execute();
        $shifts = $statement->fetchAll(PDO::FETCH_ASSOC);
        // Return 404 (not found) when no shifts for this user were found
        if(count($shifts) == 0) {
            http_response_code(404);
            exit();
        }

        foreach($shifts as $shift) {
            $id = $shift['id'];
            $date = $shift['date'];
            $startTime = $shift['startTime'];
            $endTime = $shift['endTime'];
            $isSunday = $shift['isSunday'];
            // Push shift details to array
            $this->mShift = array(
                "id" => $id,
                "date" => $date,
                "startTime" => $startTime,
                "endTime" => $endTime,
                "isSunday" => $isSunday
            );
        }
    }

    public function __construct()
    {
        parent::__construct();
        $this->initializeDatabaseConnection();

        $this->mShiftId = Application::getInstance()->getRouter()->getSegment(2);

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
                $this->returnShift();
                break;
            default:
                http_response_code(400);
                break;
        }
    }

}
?>
