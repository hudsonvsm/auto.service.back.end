<?php
namespace App\Controller;

use App\Exceptions\AccessException;
use App\Router;
use App\Service\Authorize;
use Mladenov\JsonView;
use Mladenov\View;

class Authenticator
{
    public static $isAuthorized = false;
    public static $access_token = '';
    public static $authorizedUser = [];

    /**
     * @throws \Exception
     */
    public static function Authorize()
    {
        $getParams = explode('/', filter_input(INPUT_GET, 'params', FILTER_SANITIZE_MAGIC_QUOTES));
        $returnDataType = filter_input(INPUT_GET, 'returnDataType', FILTER_SANITIZE_MAGIC_QUOTES);

        if (strlen($getParams[0]) !== 2) {
            header('Location: '. OPERATOR_URL_NOPROTOCOL . '/' . Router::$lang  . '/RepairCardData');
            die();
        }

        Router::$lang = $getParams[0];

        if ($getParams[1] == 'Login') {
            die(self::login($getParams, $returnDataType));
        } elseif ($getParams[1] == 'Logout'){
            die(self::logout());
        }

        self::authorizeToken($returnDataType);
    }

    /**
     * @param array $getParams
     * @param $returnDataType
     * @return false|string
     * @throws \Exception
     */
    public static function login(array $getParams, $returnDataType)
    {
        $postParams = $_POST;
        if (empty($postParams)) {
            $postParams = json_decode(file_get_contents('php://input'), true);
        }

        $params = [];
        if (!empty($postParams)) {
            foreach ($postParams as $key => $value) {
                $params[$key] = filter_var($postParams[$key], FILTER_SANITIZE_MAGIC_QUOTES);
            }
        }

        $out = array();

        if (empty($params)) {
            $view = new View($getParams[1], 'index', $out);
            $view->render();
            return;
        }

        try {
            $authService = new Authorize();

            $authService->setAuthLoginService($params['userName'] . ':' . $params['password']);

            $response = $authService->getService()->executeCurl();

            $out = $response->getValues();
var_dump($out);
            switch ($returnDataType) {
                case 'json':
                    return JsonView::render($out);
                case null;
                    $_SESSION['Token'] = $out['access_token'];
                    $_SESSION['Authorized'] = $out;

                    header('Location: '. ROUTER_URL_NOPROTOCOL .'/RepairCardData');
            }
        } catch (AccessException $ex) {
            http_response_code($ex->getCode());

            $out[] = $ex->getMessage();
            switch ($returnDataType) {
                case 'json':
                    return JsonView::render($out);
                case null;
                    $view = new View($getParams[1], 'index', $out);
                    $view->render();
            }
        }
    }

    /**
     * @param $returnDataType
     * @return false|string
     * @throws \Exception
     */
    public static function authorizeToken($returnDataType)
    {
        $out = array();
        $auth = '';
        try {
            $headers = getallheaders();

            if ($headers['Authorization'] === null && $_SESSION['Token'] === null) {
                throw new AccessException('No Authorization', 401);
            }

            $authService = new Authorize();

            $auth = $headers['Authorization'] !== null ? $headers['Authorization'] : $_SESSION['Token'];

            $authService->setAuthAccessService($auth);

            $response = $authService->getService()->executeCurl();

            $out = $response->getValues();

            self::$isAuthorized = $out['success'];
            self::$access_token = $auth;
            self::$authorizedUser = $_SESSION['Authorized'];
        } catch (AccessException $ex){
            http_response_code($ex->getCode());

            $out[] = $ex->getMessage();

            switch ($returnDataType) {
                case 'json':
                    return JsonView::render($out);
                case null;
                    $_SESSION['Token'] = null;
                    header('Location: '. ROUTER_URL_NOPROTOCOL .'/Login');
            }
        }

    }

    public static function logout() : void
    {
        $_SESSION['Token'] = null;
        $_SESSION['Authorized'] = null;
        session_destroy();

        header('Location: '. ROUTER_URL_NOPROTOCOL .'/Login');
    }
}