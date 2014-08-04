<?php
/*
	include('auth.php');
	
	if(isset($_GET['project'])) {
		foreach (new DirectoryIterator('../projects/'.$_GET['project'].'/big/') as $fileInfo) {
		    if($fileInfo->isDot() || !$fileInfo->isFile()) continue;
		    unlink('../projects/'.$_GET['project'].'/big/'.$fileInfo->getFilename());
		    //$files[] = $fileInfo->getFilename();
		}
	 	rmdir('../projects/'.$_GET['project'].'/big');

		foreach (new DirectoryIterator('../projects/'.$_GET['project']) as $fileInfo) {
		    if($fileInfo->isDot() || !$fileInfo->isFile()) continue;
		    unlink('../projects/'.$_GET['project']."/".$fileInfo->getFilename());
		    //$files[] = $fileInfo->getFilename();
		}
	 	rmdir('../projects/'.$_GET['project']);
	}

*/
?>

