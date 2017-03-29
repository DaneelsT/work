<?php

namespace Work\UI;

/**
 * A class which describes the properties and actions of
 * the header.
 *
 * @author  Joeri Hermans, Gaetan Dumortier
 * @since   21 June 2016
 */

use \Carbon\UI\AbstractUIElement;
use \Carbon\Application\Application;

class ViewHeader extends AbstractUIElement {

    private $mTitle;

    private $mStyleSheets = array();

    private function addDefaultStyleSheets() {
        if(Application::getInstance()->isMobile()) {
            $this->addStyleSheet("bootstrap.min.css");
            $this->addStyleSheet("main2.css");
        }else{
            $this->addStyleSheet("main.css");
        }
    }

    private function setTitle($title) {
        $this->mTitle = translate($title);
    }

    private function placeStyleSheets() {
        foreach ($this->mStyleSheets as $stylesheet)
            placeStyleSheet($stylesheet);
    }

    public function __construct($title) {
        $this->addDefaultStyleSheets();
        $this->setTitle($title);
    }

    public function addStyleSheet($stylesheet) {
        array_push($this->mStyleSheets, $stylesheet);
    }

    public function getTitle() {
        return $this->mTitle;
	}

    public function draw() {
        include getTheme("inc.header.php");
    }

}
