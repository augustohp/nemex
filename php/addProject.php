<?php
require __DIR__.'/../bootstrap.php';

if (empty($_POST['newProject'])) {
    throw new Exception('Project name not sent.');
}

$newp = clearUTF(preg_replace('/\s+/', '', $_POST['newProject']));
$newp = substr(preg_replace('/[^\p{L}\p{N}\s]/u', '', $newp), 0, 18);

mkdir(NEMEX_PATH.'projects/'.$newp, 0777, true);
$old = umask(0);
chmod(NEMEX_PATH.'projects/'.$newp, 0777);
umask($old);
mkdir(NEMEX_PATH.'projects/'.$newp.'/big', 0777, true);
$old = umask(0);
chmod(NEMEX_PATH.'projects/'.$newp.'/big', 0777);
umask($old);
