<?php

namespace Work\Page;

/**
 * A class describing the actions of a logout page. This page will destroy
 * the User's session and redirect him to the login page.
 *
 * @author  Joeri Hermans
 * @since   15 February 2016
 */

use \Carbon\Application\Application;
use \Work\Application\WorkApplication;
use \Work\Page\AbstractAuthorizedPage;

class PageLogout extends AbstractAuthorizedPage
{

    const PATH = "/logout$";

    public function __construct()
    {
        parent::__construct(parent::DEFAULT_LOGIN_DIR);
    }

    public function draw()
    {
        $application = Application::getInstance();
        $application->logOut();
        redirectInternally("/login/");
    }

}
