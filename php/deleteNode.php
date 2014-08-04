<?php
	define('NEMEX_PATH', '../');

	include(NEMEX_PATH.'auth.php');

	if(!empty($_POST['itemId'])){ 
		unlink(NEMEX_PATH.'projects/'.$_POST['project']."/".$_POST['itemId']);
		$path_parts = pathinfo(NEMEX_PATH.'projects/'.$_POST['project'].'/'.$_POST['itemId']);
		if(! ($path_parts['extension'] == 'txt')){
						
			unlink(NEMEX_PATH.'projects/'.$_POST['project']."/big/".$_POST['itemId']);
		
		}
	}else echo "error";

?>