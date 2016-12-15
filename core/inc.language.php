<?php
/*
 * File responsible for translating the work webclient to the provided language.
 *
 * @author  Gaetan Dumortier
 * @since   7 December 2016
 */

    use \Carbon\Application\Application;
    use \Work\User\User;

    require_once('language/lib/gettext.php');
    require_once('language/lib/streams.php');

    $app = Application::getInstance();

    /**
     * Work out what language to display, based on the logged in user's language.
     * If the user is currently verifying their account on the verification page, the language provided by the inviting admin will be applied.
     * If no user is logged in, we use the default language, defined in the main configuration of the application.
     */
    if($app->isLoggedIn()) {
        $user = $app->getUser();
        $lang = $user->getLanguage();
    }elseif($app->isVerifying()) {
        $lang = $app->getVerificationLanguage();
    }else{
        $lang = $app->getConfiguration("default_lang");
    }

    $lang_file = new FileReader("language/$lang/LC_MESSAGES/messages.mo");
    $lang_fetch = new gettext_reader($lang_file);

    /**
     *  Translate a given string using gettext
     *  @param  The text to be translated
     *  @return The translated text
    */
    function translate($text) {
        global $lang_fetch;
        return $lang_fetch->translate($text);
    }

    /**
     *  Translate a given string using gettext, allowing the parsed string to include parameters
     *
     *  @param  The text to be translated and its variables
     *  @return The translated text, including variables
     */
    function translatevar() {
        $args = func_get_args();
        $num = func_num_args();
        $args[0] = translate($args[0]);

        if($num <= 1)
            return $args[0];

        return call_user_func_array('sprintf', $args);
    }
?>
