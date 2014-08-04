<?php
	define('NEMEX_PATH', '../');

	include(NEMEX_PATH.'auth.php');

	if(!empty($_POST['newProject'])){ 

		$newp = clearUTF(preg_replace('/\s+/', '', $_POST['newProject']));
		$newp = substr(preg_replace('/[^\p{L}\p{N}\s]/u', '', $newp), 0, 18);
		

		if (!file_exists(NEMEX_PATH.'projects/'.$newp)) {
			$redirect = '<?php 
				header("HTTP/1.0 404 Not Found");
				?>';

		    mkdir(NEMEX_PATH.'projects/'.$newp, 0777, true);
		 	$old = umask(0);
			chmod(NEMEX_PATH.'projects/'.$newp, 0777);
			file_put_contents(NEMEX_PATH.'projects/'.$newp.'/index.php', $redirect);
			umask($old);
		 	

		 	mkdir(NEMEX_PATH.'projects/'.$newp.'/big', 0777, true);
		

		 	$old = umask(0);
			chmod(NEMEX_PATH.'projects/'.$newp.'/big', 0777);
			file_put_contents(NEMEX_PATH.'projects/'.$newp.'/big/index.php', $redirect);
			umask($old);

		 	
		}
		else echo "no";
	}else echo "error";



?>