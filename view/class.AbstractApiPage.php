<?php
namespace Work\Page;
/**
 * A class describing the actions and properties of an abstract API page. This abstract page will be responsible
 * for checking the validity of the specified API key in the authorization header.
 *
 * @author  Gaetan Dumortier
 * @since   7 December 2016
 */
use \Carbon\Page\AbstractPage;
use \Carbon\Application\Application;
use \Work\Application\WorkApplication;
use \PDO;

abstract class AbstractApiPage extends AbstractPage {
    
    const MAX_API_KEY_LENGTH = 50;
    private $mApiKey = null;
    private $mDbHandle;
    
    private function initializeDatabaseConnection() {
        $app = Application::getInstance();
        $app->connectToDatabase();
        $this->mDbHandle = $app->getDatabaseConnection();
    }
    
    protected function generateError($httpCode)
    {
        http_response_code($httpCode);
    }
    
    protected function getApiKey()
    {
        return $this->mApiKey;
    }
    
    private function extractApiKey()
    {
        if( isset($_SERVER['HTTP_AUTHORIZATION']) ) {
            $apikey = $_SERVER['HTTP_AUTHORIZATION'];
            // Check if the API key has a safe length.
            if( strlen($apikey) <= static::MAX_API_KEY_LENGTH )
                $this->mApiKey = $apikey;
        }
    }
    
    private function hasValidApiKey()
    {
        $validKey = false;
        if( $this->mApiKey != null ) {
            $application = Application::getInstance();
            $sql = "SELECT id
                    FROM api_keys
                    WHERE apikey = :key
                    LIMIT 1;";
            $statement = $this->mDbHandle->prepare($sql);
            // $hashedKey = hashString($this->mApiKey);
            $hashedKey = $this->mApiKey; // for debugging
            $statement->bindParam(':key', $hashedKey);
            $statement->execute();
            $rows = count($statement->fetchAll(PDO::FETCH_NUM));
            $validKey = ( $rows > 0 );
        }
        return $validKey;
    }
    
    protected function getRequestMethod()
    {
        return $_SERVER['REQUEST_METHOD'];
    }
    
    protected function isHttpGet()
    {
        return ( $this->getRequestMethod() == "GET" );
    }
    
    protected function isHttpPost()
    {
        return ( $this->getRequestMethod() == "POST" );
    }
    
    protected function isHttpDelete()
    {
        return ( $this->getRequestMethod() == "DELETE" );
    }
    
    protected function isHttpUpdate()
    {
        return ( $this->getRequestMethod() == "UPDATE" );
    }
    
    protected function isHttpPut()
    {
        return ( $this->getRequestMethod() == "PUT" );
    }
    
    protected function encodeJSON($encodeData, $options = null)
    {
        if( $options ) {
            return json_encode($encodeData, $options);
        } else {
            return json_encode($encodeData);
        }
    }
    protected function decodeJSON($decodeData, $options = null)
    {
        if( $options ) {
            return json_decode($decodeData, $options);
        } else {
            return json_decode($decodeData);
        }
    }
    
    public function __construct()
    {
        $this->initializeDatabaseConnection();
        
        $this->extractApiKey();
        $this->validateApiKey();
    }
    
    public function validateApiKey()
    {
        // Check if an API-key has been specified
        if( !$this->hasValidApiKey() ) {
            $this->generateError(403);
            exit;
        }
    }
}