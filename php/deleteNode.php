<?php
	require __DIR__.'/../bootstrap.php';

	include_once(NEMEX_PATH.'php/project.php');

	if(!empty($_POST['itemId'])){

		$Project = new project($_POST['project'], '1');

		if (strlen($Project->name)) {

			$expected_base_dir = realpath(NEMEX_PROJECTS . $Project->name);

			$possible_item_paths = [];
			$possible_item_paths[] = realpath($expected_base_dir . '/' . $_POST['itemId']);
			$possible_item_paths[] = realpath($expected_base_dir . '/big/' . $_POST['itemId']);

			foreach ($possible_item_paths AS $possible_item_path) {
				if ($possible_item_path) {
					$is_in_target_folder = substr($possible_item_path, 0, strlen($expected_base_dir)) === $expected_base_dir;

					if ($is_in_target_folder) {
						unlink($possible_item_path);
					}
				}
			}

			// debug($possible_item_paths);
		}

	} else {
		echo "error";
	}

