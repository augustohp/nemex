<?php
require 'bootstrap.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $cfg = new Cfg();
    $username = filter_input(INPUT_POST, 'username', FILTER_SANITIZE_STRING);
    $passwort = filter_input(INPUT_POST, 'passwort', FILTER_SANITIZE_STRING);

    if ($username == $cfg->getUsr() && $passwort == $cfg->getPwd()) {
        $_SESSION['logged'] = true;
        redirect('index.php');
    } else {
        header('HTTP/1.1 403 Forbidden');
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
    <title>Nemex: Authentication Required</title>
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
