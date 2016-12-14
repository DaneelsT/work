<?php

namespace Work\Page;
 
use \Carbon\Application\Application;
use \Carbon\Page\AbstractPage;
use \Work\UI\ViewHeader;
use \Work\UI\ViewFooter;

class PageApiTest extends AbstractPage
{
    
    const PATH = "/api-test$";
    const TITLE = "API Test";
    
    private $mHeader;
    private $mFooter;
    
    private $mDbHandle;
    
    private function initializeViewElements() {
        $this->mHeader = new ViewHeader(self::TITLE);
        $this->mFooter = new ViewFooter();
    }
    
    private function addScripts() {
        $this->mFooter->addScript("jquery.min.js");
        $this->mFooter->addScript("api-test.js");
    }
    
    public function __construct()
    {     
        $this->setTitle(static::TITLE);
        $this->initializeViewElements();
        $this->addScripts();
    }
    
    public function draw()
    {
        $this->mHeader->draw();
        include "theme/inc.api-test.php";
        $this->mFooter->draw();
    }
    
}