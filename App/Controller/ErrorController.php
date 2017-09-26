<?php

namespace App\Controller;

use Mladenov\View;

class ErrorController
{
    public static function showErrorPage(string $message)
    {
        header('Content-Type: text/html');
        $error = array('error'=> $message);

        $view = new View('error404', 'index', $error);
        $view->render();
    }
}