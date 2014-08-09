<?php

session_name('NEMEX');
session_start();
session_regenerate_id(true);

function is_authenticated_route($urlPath)
{
    $urlPath = ltrim($urlPath, '/');
    $unauthenticateRoutes = ['login.php', 'test.php'];

    return !in_array($urlPath, $unauthenticateRoutes);
}

function is_authenticated_user(array $sessionVariables)
{
    if (false === isset($sessionVariables['logged'])) {
        return false;
    }

    return (boolean) $sessionVariables['logged'];
}

if (false === is_authenticated_route($_SERVER['PHP_SELF'])) {
    return;
}

if (is_authenticated_user($_SESSION)) {
    return;
}

redirect('login.php');
