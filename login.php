<?php
    define('NEMEX_PATH', '');

    include(NEMEX_PATH.'config.php');


     if ($_SERVER['REQUEST_METHOD'] == 'POST') {
      session_start();

      $cfg = new Cfg();


      $username = $_POST['username'];
      $passwort = $_POST['passwort'];

      $hostname = $_SERVER['HTTP_HOST'];
      $path = dirname($_SERVER['PHP_SELF']);

      // Benutzername und Passwort werden überprüft
      if ($username == $cfg->getUsr() && $passwort == $cfg->getPwd()) {
       $_SESSION['angemeldet'] = true;

       // Weiterleitung zur geschützten Startseite
       if ($_SERVER['SERVER_PROTOCOL'] == 'HTTP/1.1') {
        if (php_sapi_name() == 'cgi') {
         header('Status: 303 See Other');
         }
        else {
         header('HTTP/1.1 303 See Other');
         }
        }

       header('Location: http://'.$hostname.($path == '/' ? '' : $path).'/index.php');
       exit;
       }
      }
?>
<html>
  <head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0,maximum-scale=1.0,user-scalable=no">
    <link rel="stylesheet" type="text/css" href="css/style.css">
    <link rel="stylesheet" type="text/css" href="css/style-res.css">
    <link rel="icon" type="image/png" href="favicon.png" />
   
   </head>

  <body class="login">

    <div class="login-header"><img src="img/nemex_icon.svg" alt="nemex logo" width="100px" height="100px" /></div>
    <form action="login.php" method="post" class="loginform">
     <input type="text" name="username" placeholder="Username:"/><br/>
     <input type="password" name="passwort" placeholder="Password:" /><br/>
     <input type="submit" value="Login" />
    </form>

	<footer>
		<p>made by <a href="http://neonelephant.de">neonelephant</a></p>
		
		</footer>

 </body>
</html>