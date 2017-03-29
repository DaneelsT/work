<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Work | <?php echo $this->getTitle(); ?></title>
    <meta charset="UTF-8">
    <meta name="description" content="Work webclient">
    <meta name="author" content="Gaetan Dumortier">
    <link href='https://fonts.googleapis.com/css?family=Lato:400,700&subset=latin,latin-ext'
          rel='stylesheet' type='text/css'>
    <?php $this->placeStyleSheets(); ?>
</head>
<body>
    <nav class="navbar navbar-inverse navbar-fixed-top">
          <div class="container-fluid">
            <div class="navbar-header">
              <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#navbar" aria-expanded="false" aria-controls="navbar">
                <span class="sr-only">Toggle navigation</span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
              </button>
              <a class="navbar-brand" href="#">Tuincafé Van Gastel</a>
            </div>
            <div id="navbar" class="navbar-collapse collapse">
              <ul class="nav navbar-nav navbar-right">
                  <?php
                  use \Carbon\Application\Application;
                  $app = Application::getInstance();

                  placeMenuItem("", "Dashboard");
                  placeMenuItem("month", translate("Monthly Overview"));
                  placeMenuItem("profile", $app->getUser()->getFullName());

                  if($app->getUser()->isAdmin())
                  placeMenuItem("admin", translate("Admin"), "color:red");

                  placeMenuItem("logout", translate("Logout"), "color:red");
                  ?>
              </ul>
            </div>
          </div>
        </nav>
