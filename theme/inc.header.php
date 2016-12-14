<!doctype html>
<html lang="en">
<head>
    <title>Work | <?php echo $this->getTitle(); ?></title>
    <meta charset="UTF-8">
    <meta name="description" content="Work webclient">
    <meta name="author" content="Gaetan Dumortier">
    <link href='https://fonts.googleapis.com/css?family=Lato:400,700&subset=latin,latin-ext' 
          rel='stylesheet' type='text/css'>
    <?php $this->placeStyleSheets(); ?>
</head>
<body>
<header>
    <h1><?php echo $this->getTitle(); ?></h1>
    <nav id="siteNavigation">
    <ul>        
        <?php
        	use \Carbon\Application\Application;
			$app = Application::getInstance();
			
        	placeMenuItem("", "dashboard");
			placeMenuItem("month", translate("monthly overview"));
			placeMenuItem("profile", $app->getUser()->getFullName());
			
			if($app->getUser()->isAdmin())
				placeMenuItem("admin", translate("admin"), "color:red");
			
			placeMenuItem("logout", translate("logout"), "color:red");
		?>
	</ul>
</nav>
</header>