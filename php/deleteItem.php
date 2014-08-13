<?php
	include('auth.php');

	if(!empty($_POST['itemId'])){
		unlink(NEMEX_PROJECTS.$_SESSION['activeProject']."/".$_POST['itemId']);
		$path_parts = pathinfo(NEMEX_PROJECTS.$this->project.'/'.$this->name);
		if(! $path_parts['extension'] == 'txt')
			unlink(NEMEX_PROJECTS.$_SESSION['activeProject']."/big/".$_POST['itemId']);
	}else echo "error";

?>
