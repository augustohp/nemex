<?php
	require __DIR__.'/../bootstrap.php';


	if(!empty($_POST['itemId'])){
		$filehash = substr(randomHash(), 12);
		$file = NEMEX_PROJECTS.$_POST['project']."/".time().'-'.$filehash.'.md';

		// Fügt eine neue Person zur Datei hinzu
		$current = $_POST['itemContent'];

		// Schreibt den Inhalt in die Datei zurück
		file_put_contents($file, $current);

		unlink(NEMEX_PROJECTS.$_POST['project']."/".$_POST['itemId']);

	}else echo "error";


?>
