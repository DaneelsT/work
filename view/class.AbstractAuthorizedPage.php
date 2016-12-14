<?php

namespace Work\Page;

/**
 * A class which describes the properties and actions of an authorized page.
 * If a user is not logged in, he or she will be directed to the login page,
 * located at a specified location.
 * 
 * @author  Joeri Hermans
 * @since   14 February 2016
 */
 
use \Carbon\Application\Application;
use \Carbon\Page\AbstractPage;
use \Work\Application\WorkApplication;

abstract class AbstractAuthorizedPage extends AbstractPage
{
    
    const DEFAULT_LOGIN_DIR = "/login/";
    
    /**
     * Holds the login directory.
     */
    private $mLoginDirectory = null;
    
    private function setLoginDirectory($loginDirectory)
    {
        $this->mLoginDirectory = $loginDirectory;
    }
    
    private function redirect()
    {
		if( !$this->isLoggedIn() )
			redirectInternally($this->mLoginDirectory);
    }
    
    private function isLoggedIn()
    {
        return Application::getInstance()->isLoggedIn();
    }
    
    public function __construct($loginDirectory)
    {
        $this->setLoginDirectory($loginDirectory);
        $this->redirect();
    }
	
}