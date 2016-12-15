<?php

namespace Work\UI;

/**
 * A class which describes the properties and actions of a footer
 * without the sticky footer.
 *
 * @author  Gaetan Dumortier
 * @since   15 December 2016
 */

use \Carbon\UI\AbstractUIElement;

class ViewFooterNoFooter extends AbstractUIelement {

    private $mScripts = array();

    private function placeScripts() {
        foreach ($this->mScripts as $script)
            placeScript($script);
    }

    private function addDefaultScripts() {
    }

    public function addScript($script) {
        array_push($this->mScripts, $script);
    }

    public function draw() {
        $this->addDefaultScripts();
        include "theme/inc.footer-no-footer.php";
    }

}
