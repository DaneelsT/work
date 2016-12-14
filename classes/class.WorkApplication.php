<?php

namespace Work\Application;

/**
 * A class which represents the main functionality of the work website.
 *
 * @author  Joeri Hermans, Gaetan Dumortier
 * @since   21 June 2016
 */

use \Carbon\Application\Application;
use \Work\User\User;
use \PDO;
use \PDOException;

class WorkApplication extends Application
{
	/**
	 * Holds the database handle.
	 */
    private $mDatabaseHandle = null;
	
	/**
     * Contains the logged-in user.
     */
    private $mUser = null;
	
    private function startSession()
    {
        session_start();
    }
    
    private function destroySession()
    {
		session_destroy();
		unset($this->mUser);
		$this->mUser = null;
    }
    
    private function loadSessionData()
    {
		if( isset($_SESSION['user']) )
			$this->mUser = unserialize($_SESSION['user']);
    }
    public function __construct()
    {
        $this->startSession();
        $this->loadSessionData();
    }
    
    public function __destruct()
    {
        $this->disconnectFromDatabase();
		// Check if the user is logged in.
		if( $this->isLoggedIn() ) {
			// Serialize the user information.
			$_SESSION['user'] = serialize($this->mUser);
        }
    }
	
    public function connectToDatabase() {
        // Check if a database connection is already available.
        if ($this->mDatabaseHandle == null) {
            // Fetch all required information.
            $dbhost = $this->getConfiguration("db_host");
            $dbuser = $this->getConfiguration("db_user");
            $dbpass = $this->getConfiguration("db_password");
            $dbdriver = $this->getConfiguration("db_driver");
            $dbschema = $this->getConfiguration("db_schema");
            $connectionString = $dbdriver . ":host=" . $dbhost . ";dbname=" . $dbschema;
            $this->mDatabaseHandle = new PDO($connectionString, $dbuser, $dbpass);
        }
    }

    public function disconnectFromDatabase() {
        unset($this->mDatabaseHandle);
        $this->mDatabaseHandle = null;
    }

    public function getDatabaseConnection() {
        return $this->mDatabaseHandle;
    }

    public function isConnectedToDatabase() {
        return $this->mDatabaseHandle != null;
    }
	
	public function setUser($user)
    {
		$this->mUser = $user;
		if( $user == null )
			$this->destroySession();
    }
    
    public function logOut()
    {
        $this->destroySession();
    }
    
    public function getUser()
    {
        return $this->mUser;
    }
    
    public function isLoggedIn()
    {
        return ( $this->mUser != null );
    }

}
