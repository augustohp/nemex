<?php
	require __DIR__.'/../bootstrap.php';

$errorResponse = [];

if (empty($_POST['itemId'])) {
    $errorResponse = [
        'error' => true,
        'message' => '"itemId" is required for node removal.'
    ];
}

if (empty($_POST['project'])) {
    $errorResponse = [
        'error' => true,
        'message' => '"project" is required for node removal.'
    ];
}

if (count($errorResponse)) {
    echo json_encode($errorResponse);
    error_log($errorResponse['message']);
    return;
}

$Project = new Nemex\Legacy\Project($_POST['project'], '1');

if (strlen($Project->getName())) {

    $expected_base_dir = realpath(NEMEX_PATH.'projects/' . $Project->getName());
    $possible_item_paths = [];
    $possible_item_paths[] = realpath($expected_base_dir . '/' . $_POST['itemId']);
    $possible_item_paths[] = realpath($expected_base_dir . '/big/' . $_POST['itemId']);

    foreach ($possible_item_paths AS $possible_item_path) {
        if (empty($possible_item_path)) {
            continue;
        }

        if (false === unlink($possible_item_path)) {
            error_log('Could not remove file: '.$possible_item_path);
        }

        error_log('Removed file: '.$possible_item_path);
    }
}
