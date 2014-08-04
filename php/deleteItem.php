<?php
	include('auth.php');

	if(!empty($_POST['itemId'])){ 
		unlink('../projects/'.$_SESSION['activeProject']."/".$_POST['itemId']);
		$path_parts = pathinfo('projects/'.$this->project.'/'.$this->name);
		if(! $path_parts['extension'] == 'txt')
			unlink('../projects/'.$_SESSION['activeProject']."/big/".$_POST['itemId']);
	}else echo "error";

?>