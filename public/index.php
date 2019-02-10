<?php
require_once 'bootstrap.php';

if (IS_DEBUG_INSTANCE) {
    ini_set('display_errors', 1);
    ini_set('display_startup_errors', 1);
    error_reporting(E_ALL);
}

session_start();

\App\Controller\Authenticator::Authorize();

\App\Router::route();

