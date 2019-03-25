<?php

namespace App\Controller;

use App\Exceptions\ControllerException;
use Exception;
use Mladenov\Config;
use Mladenov\IController;
use App\Model\AutomobilePart as Model;
use Mladenov\IDatabase;
use Mladenov\JsonView;
use Mladenov\View;

/**
 * Class AutomobilePart
 * @package App\Controller
 */
class AutomobilePart implements IController
{
    use ReflectionShortName;

    private $model;

    /**
     * AutomobilePart constructor.
     * @param IDatabase $db
     * @throws ControllerException
     */
    public function __construct(IDatabase $db)
    {
        $dbTableColumns = Config::getProperty('tables');

        $this->model = new Model($db, DB_TABLE_AUTOMOBILE_PART, $dbTableColumns[DB_TABLE_AUTOMOBILE_PART]);
    }

    /**
     * @param $params
     * @return false|string
     * @throws ControllerException
     */
    public function addItem($params)
    {
        if (Authenticator::$authorizedUser['scope'] !== 'admin') {
            throw new ControllerException('401 Unauthorized', 401);
        }

        return JsonView::render($this->model->insertNewItem($params));
    }

    /**
     * @param array $params
     * @return false|string|void
     * @throws ControllerException
     */
    public function getCollection(array $params)
    {
        $out['params'] = $params;
        $out['data'] = $this->model->getCollection($params);

        $out['count'] = $this->model->getCount();

        $out['count'] = $out['count'][0]['count'];

        switch ($params['returnDataType']) {
            case 'json':
                return JsonView::render($out);
            case null;
                $view = new View(ReflectionShortName::getClassShortName(__CLASS__), 'index', $out);
                try {
                    return $view->render();
                } catch (Exception $e) {
                    throw new ControllerException($e->getMessage(), $e->getCode(), $e);
                }
        }
    }

    /**
     * @param $id
     * @return false|string
     */
    public function getItem($id)
    {
        return JsonView::render($this->model->getOne($id));
    }

    /**
     * @param $id
     * @return false|string
     * @throws ControllerException
     */
    public function deleteItem($id)
    {
        if (Authenticator::$authorizedUser['scope'] !== 'admin') {
            throw new ControllerException('401 Unauthorized', 401);
        }

        try {
            $out = [ 'deleted' => $this->model->deleteItem($id) ];
        } catch (\PDOException $ex) {
            if (strpos($ex->getMessage(), "Cannot delete or update")) {
                $out = [ 'error' => 'Елементът не може да бъде изтрит. Има свързани Ремонтни карти към него' ];
            } else {
                $out = [ 'error' => $ex->getMessage() ];
            }
        }

        return JsonView::render($out);
    }

    /**
     * @param $id
     * @param $params
     * @return mixed
     * @throws ControllerException
     */
    public function updateItem($id, $params)
    {
        if (Authenticator::$authorizedUser['scope'] !== 'admin') {
            throw new ControllerException('401 Unauthorized', 401);
        }

        return $this->model->updateItem($id, $params);
    }
}