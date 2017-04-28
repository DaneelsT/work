<?php

/**
 * A collection of application wide utility functions.
 *
 * @author  Joeri Hermans
 * @since   16 February 2016
 */

use \Carbon\Application\Application;
use \Work\Shift\Shift;

function getTheme($page) {
    $path = null;
    if(Application::getInstance()->isMobile()) {
        $path = "./theme/mobile/". $page;
    }else{
        $path = "./theme/". $page;
    }

    return $path;
}

function loadClass($className) {
    $clearedFromNamespace = strrchr($className, '\\');
    if ($clearedFromNamespace != null)
        $className = substr($clearedFromNamespace, 1);
    $path = "classes/class." . $className . ".php";
    if (!file_exists($path)) {
        $path = "view/class." . $className . ".php";
        if (!file_exists($path)) {
            $path = "core/application/class." . $className . ".php";
        }
    }

    require_once $path;
}

function parseConfiguration($config) {
    require_once "config/conf." . $config . ".php";
}

function getProtocol() {
    $protocol;

    if (isset($_SERVER['HTTPS']))
        $protocol = "https://";
    else
        $protocol = "http://";

    return $protocol;
}

function getHost() {
    return Application::getInstance()->getHost();
}

function getBase() {
    return Application::getInstance()->getBase();
}

function redirectInternally($uri) {
    $app = Application::getInstance();
    $protocol = getProtocol();
    $host = $app->getHost();
    $base = $app->getBase();
    redirect($protocol . $host . $base . $uri);
}

function redirect($url) {
    header("Location: " . $url);
    exit ;
}

function placeResource($file) {
    echo getHttpRoot() . $file;
}

function datetimeOrdinalSuffix($number) {
    $number = $number % 100;
    if ($number < 11 || $number > 13) {
        switch($number % 19) {
            case 1 :
                return 'st';
            case 2 :
                return 'nd';
            case 3 :
                return 'rd';
        }
    }

    return 'th';
}

function getMonthName($inp) {
    return date("F", strtotime(date("d-$inp-y")));
}

function getLastDayInMonth($inp) {
    return date("t", strtotime(date("d-$inp-y")));
}

function dayIsSunday($inp) {
    if(date("w", strtotime($inp)) == 0) {
        return true;
    }
}

function placeStyleSheet($stylesheet) {
    if(Application::getInstance()->isMobile()) {
        $source = getHttpRoot() . "theme/mobile/css/" . $stylesheet;
    }else{
        $source = getHttpRoot() . "theme/css/" . $stylesheet;
    }

    echo '<link rel="stylesheet" media="screen" href="' . $source . '">';
}

function placeScript($script) {
    // Check if the specified script is an absolute script.
    if (substr($script, 0, 4) === "http") {
        $source = $script;
    }else{
        if(Application::getInstance()->isMobile()) {
            $source = getHttpRoot() . "theme/mobile/js/" . $script;
        }else{
            $source = getHttpRoot() . "theme/js/" . $script;
        }
    }

    echo '<script type="text/javascript" src="' . $source . '"></script>';
}

function getHttpRoot() {
    $app = Application::getInstance();
    $protocol = getProtocol();
    $host = $app->getHost();
    $base = $app->getBase();

    return $protocol . $host . $base . "/";
}

function placeURI($uri) {
    echo getHttpRoot() . $uri;
}

function placeHttpRoot() {
    echo getHttpRoot();
}

function placeMenuItem($url, $str, $style = "", $class = "") {
	echo '<li><a href=' . getHttpRoot() . $url . ' style=' . $style . ' class=' . $class . '>' . strtoupper($str) . '</a></li>';
}

function drawMenu() {
    $app = Application::getInstance();

    placeMenuItem("", "Dashboard");
    placeMenuItem("month", translate("Monthly Overview"));
    placeMenuItem("year", translate("Yearly Overview"));
    placeMenuItem("profile", $app->getUser()->getFullName());

    if($app->getUser()->isAdmin())
        placeMenuItem("admin", translate("Admin"), "color:red");

    placeMenuItem("logout", translate("Logout"), "color:red");
}

function validEmail( $email ) {
    return ( filter_var($email, FILTER_VALIDATE_EMAIL) );
}

function generateToken() {
    return uniqid(mt_srand());
}

function mailToken($email, $name, $token) {
    $from = Application::getInstance()->getConfiguration("mail_sentas");
    $subject = "[Work] New account";
    $message = "Hello " . $name . "<br /><br />";
    $message .= "Your account can be created by following the link below:<br />";
	$message .= "<a href=" . getHttpRoot() ."user/verify/" . $token .">" . getHttpRoot() . "user/verify/" . $token ."</a><br />";
    $headers = "MIME-Version: 1.0\r\n";
    $headers .= "Content-type: text/html; charset=utf-8\r\n";
    $headers .= "From: Work Webclient <" . $email . ">";
    mail($email,$subject,$message,$headers,"-f " . $from);
}

function hashString($string) {
    $app = Application::getInstance();
    $presalt = $app->getConfiguration("sec_presalt");
    $postsalt = $app->getConfiguration("sec_postsalt");

    return sha1($presalt . $string . $postsalt);
}

function registerPages($router) {
    // Register regular pages
    $router->registerPage(\Work\Page\PageLogin::PATH, "\Work\Page\PageLogin");
    $router->registerPage(\Work\Page\PageLogout::PATH, "\Work\Page\PageLogout");
    $router->registerPage(\Work\Page\PageDashboard::PATH, "\Work\Page\PageDashboard");
    $router->registerPage(\Work\Page\PageShiftEdit::PATH, "\Work\Page\PageShiftEdit");
    $router->registerPage(\Work\Page\PageShiftRemove::PATH, "\Work\Page\PageShiftRemove");
    $router->registerPage(\Work\Page\PageMonth::PATH, "\Work\Page\PageMonth");
    $router->registerPage(\Work\Page\PageMonthClose::PATH, "\Work\Page\PageMonthClose");
	$router->registerPage(\Work\Page\PageProfile::PATH, "\Work\Page\PageProfile");
    $router->registerPage(\Work\Page\PageUserVerify::PATH, "\Work\Page\PageUserVerify");
    $router->registerPage(\Work\Page\PageYearClose::PATH, "Work\Page\PageYearClose");
    $router->registerPage(\Work\Page\PageYear::PATH, "Work\Page\PageYear");
    $router->registerPage(\Work\Page\PageYearDetails::PATH, "Work\Page\PageYearDetails");
	// Register admin pages
	$router->registerPage(\Work\Page\PageAdmin::PATH, "\Work\Page\PageAdmin");
	$router->registerPage(\Work\Page\PageAdminUsers::PATH, "\Work\Page\PageAdminUsers");
	$router->registerPage(\Work\Page\PageUserAdd::PATH, "\Work\Page\PageUserAdd");
	$router->registerPage(\Work\Page\PageUserEdit::PATH, "\Work\Page\PageUserEdit");
    $router->registerPage(\Work\Page\PageAdminShifts::PATH, "\Work\page\PageAdminShifts");
    $router->registerPage(\Work\Page\PageAdminShift::PATH, "\Work\Page\PageAdminShift");
    $router->registerPage(\Work\Page\PageAdminInviteUser::PATH, "\Work\Page\PageAdminInviteUser");
    // Register API pages
    $router->registerPage(\Work\Page\PageApiUser::PATH, "Work\Page\PageApiUser");
    $router->registerPage(\Work\Page\PageApiUsers::PATH, "Work\Page\PageApiUsers");
    $router->registerpage(\Work\Page\PageApiShift::PATH, "Work\Page\PageApiShift");
    $router->registerpage(\Work\Page\PageApiShifts::PATH, "Work\Page\PageApiShifts");
}
