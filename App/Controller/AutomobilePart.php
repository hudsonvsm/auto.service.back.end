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
        if (Authenticator::$authorizedUser['scope'] !== 'admin') {
            throw new ControllerException('401 Unauthorized', 401);
        }

        $dbTableColumns = Config::getProperty('tables');

        $this->model = new Model($db, DB_TABLE_AUTOMOBILE_PART, $dbTableColumns[DB_TABLE_AUTOMOBILE_PART]);
    }

    public function addItem($params)
    {
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

    public function getItem($id)
    {
        return JsonView::render($this->model->getOne($id));
    }

    public function deleteItem($id)
    {
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

    public function updateItem($id, $params)
    {
        return $this->model->updateItem($id, $params);
    }
}