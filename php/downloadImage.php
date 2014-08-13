<?php

	require __DIR__.'/../bootstrap.php';

	$path = '../projects/'.$_GET['project'].'/big/'.$_GET['itemId'];

	header('Content-type: image/jpg');
	header('Content-Disposition: attachment; filename='.$_GET['itemId']);
	readfile($path);

