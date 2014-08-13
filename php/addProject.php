<?php
	require __DIR__.'/../bootstrap.php';

    if (true === empty($_POST['newProject'])) {
        return error_log('Cannot create project without name.');
    }


    $newp = clearUTF(preg_replace('/\s+/', '', $_POST['newProject']));
    $newp = substr(preg_replace('/[^\p{L}\p{N}\s]/u', '', $newp), 0, 18);

    if (strlen($newp) == 0) {
        return error_log('Project name is invalid: '.$_POST['newProject']);
    }

    $projectPath = NEMEX_PATH.'projects/'.$newp;
    clearstatcache(true, $projectPath);
    if (file_exists($projectPath)) {
        return error_log('Project already exists: '.$projectPath);
    }
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

