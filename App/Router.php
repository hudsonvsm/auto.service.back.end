<?php

namespace App;

use App\Controller\Error404;
use App\Exceptions\ControllerException;
use Mladenov\Config;
use Mladenov\IController;
use Mladenov\View;

class Router
{
    static function route() : void
    {
        try {
            // TODO build response object
            header('Content-Type: application/json');

            switch ($_SERVER['REQUEST_METHOD']) {
                case 'POST':
                    $out['result'] = (self::post()) ? 'true' : 'false';
                    die(json_encode($out));
                case 'PATCH':
                    $out['result'] = self::patch() ? 'true' : 'false';
                    die(json_encode($out));
                case 'DELETE':
                    $out['result'] = self::delete() ? 'true' : 'false';
                    die(json_encode($out));
                case 'GET':
                    die(self::get());
                    break;
                case 'PUT':
                default:
                    self::dieWithError('Unsupported Request method: ' . $_SERVER['REQUEST_METHOD']);
            }
        } catch (ControllerException $e) {
            self::dieWithError($e->getMessage());
        }
    }

    private static function get() : string
    {
        $parameters = explode('/', filter_input(INPUT_GET, 'params', FILTER_SANITIZE_MAGIC_QUOTES));

        $controller = self::loadController($parameters[0]);

        switch (true) {
            case count($parameters) === 1:
                return self::getCollection($controller);
            case count($parameters) === 2:
                return self::getEntity($controller, $parameters[1]);
            default:
                self::dieWithError('Unknown controller method.');
        }
    }

    private static function post() : bool
    {
        $parameters = explode('/', filter_input(INPUT_GET, 'params', FILTER_SANITIZE_MAGIC_QUOTES));

        if (count($parameters) > 1) {
            self::dieWithError('Too many GET parameters.');
        };

        $controller = self::loadController($parameters[0]);

        $params['description'] = filter_input(INPUT_POST, 'description', FILTER_SANITIZE_MAGIC_QUOTES);

        $postParams = $_POST;
        if (empty($postParams)) {
            $postParams = json_decode(file_get_contents('php://input'), true);
        }

        foreach ($postParams as $key => $value) {
            $params[$key] = filter_var($postParams[$key], FILTER_SANITIZE_MAGIC_QUOTES);
        }

        return $controller->addItem($params);
    }

    private static function delete() : bool
    {
        $parameters = explode('/', filter_input(INPUT_GET, 'params', FILTER_SANITIZE_MAGIC_QUOTES));

        $controller = self::loadController($parameters[0]);

        return $controller->deleteItem($parameters[1]);
    }

    private static function patch() : bool
    {
        $updateParams = json_decode(file_get_contents('php://input'),true);

        $parameters = explode('/', filter_input(INPUT_GET, 'params', FILTER_SANITIZE_MAGIC_QUOTES));

        $controller = self::loadController($parameters[0]);

        return $controller->updateItem($parameters[1], $updateParams);
    }

    private static function loadController(string $controller) : IController
    {
        $db = Config::getProperty('db');
        $controllerName = Config::getProperty('controllerPrefix') . ucfirst($controller);

        $dbDriver = $db['dbDriver'];
        if (class_exists($controllerName)) {
            return new $controllerName($dbDriver::getInstance($db));
        }

        self::dieWithError('No such controller.');
    }

    private static function getCollection(IController $controller) : string
    {
        $basicConfigs = Config::getProperty('basicGet');

        $params = $basicConfigs;
        foreach ($_GET as $key => $value) {
            $params[$key] = filter_input(INPUT_GET, $key, FILTER_SANITIZE_MAGIC_QUOTES);
        }

        return $controller->getCollection($params);
    }

    private static function getEntity(IController $controller, string $id) : string
    {
        return $controller->getItem($id);
    }

    private static function dieWithError(string $message)
    {

        header('Content-Type: text/html');
        $error = array('error'=> $message);

        $view = new View('error404', 'index', $error);
        $view->render();
        die();
    }
}