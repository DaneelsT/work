<?php

namespace Work\UI;

/**
 * A class which describes the properties and actions of a footer.
 *
 * @author  Joeri Hermans
 * @since   21 June 2016
 */

use \Carbon\UI\AbstractUIElement;

class ViewFooter extends AbstractUIelement {

    private $mScripts = array();

    private function placeScripts() {
        foreach ($this->mScripts as $script)
            placeScript($script);
    }

    private function addDefaultScripts() {
        $this->addScript("jquery.min.js");
        $this->addScript("bootstrap.min.js");
        $this->addScript("jquery.mask.min.js");
    }

    public function addScript($script) {
        array_push($this->mScripts, $script);
    }

    public function draw() {
        $this->addDefaultScripts();
        include getTheme("inc.footer.php");
    }

}
