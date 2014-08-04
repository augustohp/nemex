<?php
	define('NEMEX_PATH', '../');

	include(NEMEX_PATH.'auth.php');


	if($_GET['ext'] == 'txt' || $_GET['ext'] == 'md'){
		$path = NEMEX_PATH.'projects/'.$_GET['project'].'/'.$_GET['itemId'];

		header('Content-type: image/'.$_GET['ext']);
		header('Content-Disposition: attachment; filename='.$_GET['itemId']);
		readfile($path);		
	}
	else{
		$path = NEMEX_PATH.'projects/'.$_GET['project'].'/big/'.$_GET['itemId'];

		header('Content-type: image/'.$_GET['ext']);
		header('Content-Disposition: attachment; filename='.$_GET['itemId']);
		readfile($path);	
	}
	
?>
