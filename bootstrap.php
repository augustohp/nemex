<?php

if (defined('DS')) {
    return;
}

define('DS', DIRECTORY_SEPARATOR);
define('NEMEX_PATH', __DIR__.DS);

date_default_timezone_set('UTC');
error_reporting(-1);
ini_set('display_errors', 0);
ini_set('log_errors', 1);
ini_set('error_log', NEMEX_PATH.'error.log');
setlocale(LC_ALL, 'en_US.UTF8');

require_once NEMEX_PATH.'php'.DS.'functions.php';
require_once NEMEX_PATH.'php'.DS.'user.php';
require_once normalizeFilePath(NEMEX_PATH.'config.php');

$requestIsLogin = (boolean) ($_SERVER['PHP_SELF'] == '/login.php');
if (false === $requestIsLogin) {
    require normalizeFilePath(NEMEX_PATH.'auth.php');
}
