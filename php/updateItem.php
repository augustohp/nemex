<?php
	define('NEMEX_PATH', '../');

	include(NEMEX_PATH.'auth.php');
	include_once(NEMEX_PATH.'php/functions.php');

	if(!empty($_POST['itemId'])){ 
		$filehash = substr(randomHash(), 12);
		$file = NEMEX_PATH.'projects/'.$_POST['project']."/".time().'-'.$filehash.'.md';	

		// Fügt eine neue Person zur Datei hinzu
		$current = $_POST['itemContent'];

		// Schreibt den Inhalt in die Datei zurück
		file_put_contents($file, $current);

		unlink(NEMEX_PATH.'projects/'.$_POST['project']."/".$_POST['itemId']);

	}else echo "error";


?>