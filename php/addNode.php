<?php

	/*
		save text-node in project folder
	*/

	require __DIR__.'/../bootstrap.php';

	if(isset($_POST['itemContent'])) {
		$filehash = substr(randomHash(), 12);
		$file = NEMEX_PROJECTS.$_POST['project']."/".time().'-'.$filehash.'.md';
		$current = $_POST['itemContent'];
		file_put_contents($file, $current);

		$old = umask(0);
		chmod($file, 0666);
		umask($old);
	}

?>
