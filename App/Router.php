<?php

namespace App;

use App\Controller\ErrorController;
use App\Exceptions\ControllerException;
use App\Exceptions\RouterException;
use Mladenov\Config;
use Mladenov\IController;

class Router
{
    static function route()
    {
        try {
            switch ($_SERVER['REQUEST_METHOD']) {
                case 'POST':
                    die(self::post());
                case 'PATCH':
                    die(self::patch());
                case 'DELETE':
                    die(self::delete());
                case 'GET':
                    die(self::get());
                    break;
                case 'PUT':
                default:
                    ErrorController::showErrorPage('Unsupported Request method: ' . $_SERVER['REQUEST_METHOD']);
            }
        } catch (ControllerException $e) {
            ErrorController::showErrorPage($e->getMessage());
        } catch (RouterException $e) {
            ErrorController::showErrorPage($e->getMessage());
        }
    }

    private static function get()
    {
        $parameters = explode('/', filter_input(INPUT_GET, 'params', FILTER_SANITIZE_MAGIC_QUOTES));

        $controller = self::loadController($parameters[0]);

        switch (true) {
            case count($parameters) === 1:
                return self::getCollection($controller);
            case count($parameters) === 2:
                return self::getEntity($controller, $parameters[1]);
            default:
                throw new RouterException('Unknown controller method.');
        }
    }

    private static function post() : string
    {
        $parameters = explode('/', filter_input(INPUT_GET, 'params', FILTER_SANITIZE_MAGIC_QUOTES));

        if (count($parameters) > 1) {
            throw new RouterException('Too many GET parameters.');
        };

        $controller = self::loadController($parameters[0]);

        //$params['description'] = filter_input(INPUT_POST, 'description', FILTER_SANITIZE_MAGIC_QUOTES);

        $postParams = $_POST;
        if (empty($postParams)) {
            $postParams = json_decode(file_get_contents('php://input'), true);
        }

        $params = [];
        foreach ($postParams as $key => $value) {
            $params[$key] = filter_var($postParams[$key], FILTER_SANITIZE_MAGIC_QUOTES);
        }

        return $controller->addItem($params);
    }

    private static function delete() : string
    {
        $parameters = explode('/', filter_input(INPUT_GET, 'params', FILTER_SANITIZE_MAGIC_QUOTES));

        $controller = self::loadController($parameters[0]);

        return $controller->deleteItem($parameters[1]);
    }

    private static function patch() : string
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

        throw new RouterException('No such controller.');
    }

    private static function getCollection(IController $controller)
    {
        $basicConfigs = Config::getProperty('basicGet');

        $params = $basicConfigs[$_GET['params']];
        foreach ($_GET as $key => $value) {
            $params[$key] = filter_input(INPUT_GET, $key, FILTER_SANITIZE_MAGIC_QUOTES);
        }


        return $controller->getCollection($params);
    }

    private static function getEntity(IController $controller, string $id) : string
    {
        return $controller->getItem($id);
    }
}