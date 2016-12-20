<?php

namespace Carbon\Page;

/**
 * A class which represents the properties and actions of the
 * page not found page.
 *
 * @author  Joeri Hermans
 * @since   17 February 2016
 */

use \Carbon\Page\AbstractPage;
use \Work\UI\ViewHeaderNoMenu;
use \Work\UI\ViewFooter;

class PageNotFound extends AbstractPage {

    const PATH = "/404$";
    private $mTitle = "Page not found";

    private $mHeader;

    private $mFooter;

    private function initializeViewElements() {
        $this->mHeader = new ViewHeaderNoMenu($this->getTitle());
        $this->mFooter = new ViewFooter();
    }

    public function __construct() {
        $this->setTitle($this->mTitle);
        $this->initializeViewElements();
        header('HTTP/1.0 404 Not Found');
    }

    public function draw() {
        $this->mHeader->draw();
        include "theme/inc.page-not-found.php";
        $this->mFooter->draw();
    }

}
