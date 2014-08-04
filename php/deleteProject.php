<?php
	define('NEMEX_PATH', '../');

	include(NEMEX_PATH.'auth.php');
	if(isset($_POST['project'])) {

		foreach (new DirectoryIterator(NEMEX_PATH.'projects/'.$_POST['project'].'/big') as $fileInfo) {
		    if($fileInfo->isDot() || !$fileInfo->isFile()) continue;
		    unlink(NEMEX_PATH.'projects/'.$_POST['project']."/big/".$fileInfo->getFilename());
		}

		rmdir(NEMEX_PATH.'projects/'.$_POST['project'].'/big');

		foreach (new DirectoryIterator(NEMEX_PATH.'projects/'.$_POST['project']) as $fileInfo) {
		    if($fileInfo->isDot() || !$fileInfo->isFile()) continue;
		    unlink(NEMEX_PATH.'projects/'.$_POST['project']."/".$fileInfo->getFilename());
		}

	 	rmdir(NEMEX_PATH.'projects/'.$_POST['project']);
	}
?>