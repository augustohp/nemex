<?php
	require __DIR__.'/../bootstrap.php';

	$filehash = substr(randomHash(), 12);

	$project = $_GET['project'];
	$zip = new ZipArchive;

	$zipName = $_GET['project'].'-'.$filehash.'.zip';
	$archive_file_name = NEMEX_PROJECTS.$project.'/'.$zipName;

	$added_a_file = false;

	if ($zip->open($archive_file_name, ZipArchive::CREATE)) {

		foreach (new DirectoryIterator(NEMEX_PROJECTS.$project.'/big/') as $fileInfo2) {
		    if($fileInfo2->isDot() || !$fileInfo2->isFile() || $fileInfo2 == 'index.php') continue;
	   	    $zip->addFile(NEMEX_PROJECTS.$project.'/big/'.$fileInfo2, $project.'/'.$fileInfo2);
	   	    $added_a_file = true;
		}

		foreach (new DirectoryIterator(NEMEX_PROJECTS.$project) as $fileInfo) {
		    if($fileInfo->isDot() || !$fileInfo->isFile() || $fileInfo == 'index.php') continue;

		    $path_parts = pathinfo(NEMEX_PROJECTS.$fileInfo);
			$extension = strtolower($path_parts['extension']);

			if($extension == 'txt' || $extension == 'md' ) {
		    	$zip->addFile(NEMEX_PROJECTS.$project.'/'.$fileInfo, $project.'/'.$fileInfo);
		    	$added_a_file = true;
		    }
		}

		$created_zip = $zip->close();

		if ($created_zip AND $added_a_file) {
			header("Content-type: application/zip");
			header("Content-Disposition: attachment; filename=$zipName");
			header("Content-length: " . filesize($archive_file_name));
			header("Pragma: no-cache");
			header("Expires: 0");
			readfile("$archive_file_name");

			unlink($archive_file_name);

		} else {
			header("HTTP/1.0 404 Not Found");
			echo 'No files in this project';
		}

	}
	else {

	}


?>
