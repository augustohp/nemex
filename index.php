<?php 
	define('NEMEX_PATH', '');


	include(NEMEX_PATH.'auth.php');
	session_start();

	include_once(NEMEX_PATH.'php/functions.php');
	include_once('php/project.php');
	include_once('php/user.php');

	$u = new user('1');
			
?>

<html>
	<head>
		<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
		<meta name="viewport" content="width=device-width, initial-scale=1.0,maximum-scale=1.0,user-scalable=no">
		<meta name="apple-mobile-web-app-capable" content="yes" />
		<meta name="viewport" content="width=device-width, minimal-ui">
		<meta name="apple-mobile-web-app-status-bar-style" content="black-translucent">
		<title>nemex.io</title>
		<link rel="stylesheet" type="text/css" href="css/style.css">
		<link rel="stylesheet" type="text/css" href="css/style-res.css">
		<link rel="apple-touch-icon" sizes="60x60" href="touch-icon-iphone.png">
		<link rel="apple-touch-icon" sizes="76x76" href="img/touch-icon-ipad.png">
		<link rel="apple-touch-icon" sizes="120x120" href="img/touch-icon-iphone-retina.png">
		<link rel="apple-touch-icon" sizes="152x152" href="img/touch-icon-ipad-retina.png">
		<script> var noElements = 0; </script>
		<link rel="icon" type="image/png" href="favicon.png" />
		

	</head>

	<body>
	<div id="mdhelp">
		<h1>markdown help</h1>
			headlines: # headline1 ## headline2 ...<br/>
			code: `nemex` (backticks)<br />
			link: [nemex io](http://www.nemex.io)<br/>
			bold text: *nemex* or _nemex_<br />
			italic text: **nemex** or __nemex__<br />
	</div>


	<?php 
		if(isset($_GET['view']) ) {
			$p = new project($_GET['view'], '1');
			$p->getNodes();
			$p->showProject();
		}
		else if(isset($_GET['deleteProject']) ) {
			$p = new project($_GET['deleteProject'], '1');
			$p->deleteProject();
		}
		else {
			echo '<div class="header">NEMEX</div>';
			echo '<div class="project-list">';

			echo '<div id="addProject"></div>
					<div class="addProjectForm">
						<input type="text" id="newProject" placeholder="Project name:"/><br/><button type="submit" id="np" ></button>
					</div>';

			$u->showProjects();
			
			echo '</div>';
			echo '<div class="navigation">
			<a class="index" href="logout.php"><img src="img/logout.svg" /></a>
			</div>';
		}
	 ?>
	
	<div class="preloader">
		<img src="img/cancel@2x.png" />
		<img src="img/cancel_edit@2x.png" />
		<img src="img/save@2x.png" />
	</div>

	<script src="//code.jquery.com/jquery-1.10.2.js"></script>
	<script src="js/contenteditable.js" type="text/javascript" ></script>
	<script src="js/showdown.js" type="text/javascript" ></script>
	<script src="js/to-markdown.js" type="text/javascript" ></script>
	<script src="js/script.js" type="text/javascript"></script>
	<script src="js/jquery.autosize.min.js" type="text/javascript"></script>
	<script src="js/snap.min.js" type="text/javascript"></script>
	<script src="js/webapp.js" type="text/javascript"></script>
</body>
</html>  