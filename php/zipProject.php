<?php
	define('NEMEX_PATH', '../');

	include(NEMEX_PATH.'auth.php');
	include_once(NEMEX_PATH.'php/functions.php');

	$filehash = substr(randomHash(), 12);

	$project = $_GET['project'];
	$zip = new ZipArchive;
	
	$zipName = $_GET['project'].'-'.$filehash.'.zip';
	$archive_file_name = NEMEX_PATH.'projects/'.$project.'/'.$zipName;

	if ($zip->open($archive_file_name, ZipArchive::CREATE)) {
		
		foreach (new DirectoryIterator(NEMEX_PATH.'projects/'.$project.'/big/') as $fileInfo2) {
		    if($fileInfo2->isDot() || !$fileInfo2->isFile() || $fileInfo2 == 'index.php') continue;
	   	    $zip->addFile(NEMEX_PATH.'projects/'.$project.'/big/'.$fileInfo2, $project.'/'.$fileInfo2);
		}

		foreach (new DirectoryIterator(NEMEX_PATH.'projects/'.$project) as $fileInfo) {
		    if($fileInfo->isDot() || !$fileInfo->isFile() || $fileInfo == 'index.php') continue;
		    
		    $path_parts = pathinfo(NEMEX_PATH.'projects/'.$fileInfo);
			$extension = strtolower($path_parts['extension']);
			if($extension == 'txt' || $extension == 'md' )
		    	$zip->addFile(NEMEX_PATH.'projects/'.$project.'/'.$fileInfo, $project.'/'.$fileInfo);
		}

		$zip->close();


		header("Content-type: application/zip"); 
		header("Content-Disposition: attachment; filename=$zipName");
		header("Content-length: " . filesize($archive_file_name));
		header("Pragma: no-cache"); 
		header("Expires: 0"); 
		readfile("$archive_file_name");
		

		unlink($archive_file_name);
	}
	else {
		
	}


?>