<?php

use \Carbon\Application\Application;

/**
 * Main configuration file of the application. In this file you can add
 * additional application specific configuration key-values.
 *
 * @author  Joeri Hermans, Gaetan Dumortier
 * @since   16 February 2016
 */

// BEGIN Configuration. ////////////////////////////////////////////////////////

// Directory configuration.
// Don't forget to also modify your RewriteBase and RewriteRule in the .htaccess file
$conf['host']   = "example.com";
$conf['base']   = "/base-directory";

// Security configuration.
$conf['sec_presalt']        = "lAg6qR";
$conf['sec_postsalt']       = "Krp54B";

// Users payment configuration.
$conf['hourly_pay']			= 10.25;
$conf['sunday_fee']			= 12.00;

// Language configuration.
$conf['default_lang']   = 'en_US';

// Email configuration.
$conf['mail_sentas']        = "info@domain.com";

// END Configuration. //////////////////////////////////////////////////////////

// DO NOT EDIT BELOW THIS LINE. ////////////////////////////////////////////////

$application = Application::getInstance();
$application->addConfiguration($conf);
unset($conf);
