<?php

namespace App;

use App\Controller\ErrorController;
use App\Exceptions\AccessException;
use App\Exceptions\ControllerException;
use App\Exceptions\RouterException;
use Mladenov\Config;
use Mladenov\IController;

/**
 * Class Router
 * @package App
 */
class Router
{
    public static $lang = 'bg';
    public static $controllerName = null;
    public static $id = null;

    /**
     * Routing function
     */
    static function route()
    {
        try {
            $parameters = explode('/', filter_input(INPUT_GET, 'params', FILTER_SANITIZE_MAGIC_QUOTES));

            self::$lang = $parameters[0];
            self::$controllerName = $parameters[1];
            self::$id = count($parameters) == 3 ? $parameters[2] : null;

            switch ($_SERVER['REQUEST_METHOD']) {
                case 'POST':
                    //if ($paramCount > 2) throw new RouterException('Unknown controller method.');
                    die(self::post());
                case 'PATCH':
                case 'PUT':
                    //if ($paramCount > 2) throw new RouterException('Unknown controller method.');
                    die(self::patch());
                case 'DELETE':
                    die(self::delete());
                case 'GET':
                    die(self::get(is_null(self::$id)));
                default:
                    ErrorController::showErrorPage('Unsupported Request method: ' . $_SERVER['REQUEST_METHOD']);
            }
        } catch (ControllerException $e) {
            ErrorController::showErrorPage($e->getMessage());
        } catch (RouterException $e) {
            ErrorController::showErrorPage($e->getMessage());
        }
    }

    /**
     * @param bool $isCollection
     * @return string
     * @throws RouterException
     */
    private static function get(bool $isCollection)
    {
        $controller = self::loadController(self::$controllerName);

        return $isCollection ? self::getCollection($controller) : self::getEntity($controller);
    }

    /**
     * @return string
     * @throws RouterException
     */
    private static function post() : string
    {
        if (!is_null(self::$id)) {
            throw new RouterException('Too many GET parameters.');
        }

        $controller = self::loadController(self::$controllerName);

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

    /**
     * @return string
     * @throws RouterException
     */
    private static function delete() : string
    {
        $controller = self::loadController(self::$controllerName);

        return $controller->deleteItem(self::$id);
    }

    /**
     * @return string
     * @throws RouterException
     */
    private static function patch() : string
    {
        $updateParams = json_decode(file_get_contents('php://input'),true);

        $controller = self::loadController(self::$controllerName);

        return $controller->updateItem(self::$id, $updateParams);
    }

    /**
     * @param string $controller
     * @return IController
     * @throws RouterException
     */
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

    /**
     * @param IController $controller
     * @return mixed
     */
    private static function getCollection(IController $controller)
    {
        $basicConfigs = Config::getProperty('basicGet');

        $params = $basicConfigs[self::$controllerName];
        foreach ($_GET as $key => $value) {
            $params[self::sanitizeMagicQuotes($key)] = self::sanitizeMagicQuotes($value);
        }

        return $controller->getCollection($params);
    }

    /**
     * @param IController $controller
     * @param string $id
     * @return string
     */
    private static function getEntity(IController $controller) : string
    {
        return $controller->getItem(self::$id);
    }

    /**
     * @param $value
     * @return array|mixed
     */
    private static function sanitizeMagicQuotes($value)
    {
        if (is_array($value)) {
            $params = [];

            foreach ($value as $k => $v) {
                $params[self::sanitizeMagicQuotes($k)] = self::sanitizeMagicQuotes($v);
            }

            return $params;
        }

        return filter_var($value, FILTER_SANITIZE_MAGIC_QUOTES);
    }
}