<?php
	require __DIR__.'/../bootstrap.php';

	if(isset($_POST['project'])) {

		foreach (new DirectoryIterator(NEMEX_PROJECTS.$_POST['project'].'/big') as $fileInfo) {
		    if($fileInfo->isDot() || !$fileInfo->isFile()) continue;
		    unlink(NEMEX_PROJECTS.$_POST['project']."/big/".$fileInfo->getFilename());
		}

		rmdir(NEMEX_PROJECTS.$_POST['project'].'/big');

		foreach (new DirectoryIterator(NEMEX_PROJECTS.$_POST['project']) as $fileInfo) {
		    if($fileInfo->isDot() || !$fileInfo->isFile()) continue;
		    unlink(NEMEX_PROJECTS.$_POST['project']."/".$fileInfo->getFilename());
		}

	 	rmdir(NEMEX_PROJECTS.$_POST['project']);
	}
?>
