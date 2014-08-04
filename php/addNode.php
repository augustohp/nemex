<?php

	/* 
		save text-node in project folder
	*/

	define('NEMEX_PATH', '../');


	include(NEMEX_PATH.'auth.php');
	include_once(NEMEX_PATH.'php/functions.php');

	if(isset($_POST['itemContent'])) {
		$filehash = substr(randomHash(), 12);
		$file = NEMEX_PATH.'projects/'.$_POST['project']."/".time().'-'.$filehash.'.md';
		$current = $_POST['itemContent'];
		file_put_contents($file, $current);

		$old = umask(0);
		chmod($file, 0666);
		umask($old);
	}

?>