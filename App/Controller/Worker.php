<?php

namespace App\Controller;

use App\Exceptions\ControllerException;
use Mladenov\Config;
use Mladenov\IController;
use App\Model\Worker as Model;
use Mladenov\IDatabase;
use Mladenov\JsonView;
use Mladenov\View;

/**
 * Class Worker
 * @package App\Controller
 */
class Worker implements IController
{
    private $model;

    /**
     * Worker constructor.
     * @param IDatabase $db
     */
    public function __construct(IDatabase $db)
    {
        $dbTableColumns = Config::getProperty('tables');

        $this->model = new Model($db, DB_TABLE_WORKER, $dbTableColumns[DB_TABLE_WORKER]);
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
     * @return false|string
     * @throws \Exception
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
                $view->render();
        }
    }

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

        return JsonView::render([ 'deleted' => $this->model->deleteItem($id) ]);
    }

    /**
     * @param $id
     * @param $params
     * @return false|string
     * @throws ControllerException
     */
    public function updateItem($id, $params)
    {
        if (Authenticator::$authorizedUser['scope'] !== 'admin') {
            throw new ControllerException('401 Unauthorized', 401);
        }

        return JsonView::render([ 'result' => $this->model->updateItem($id, $params) ]);
    }
}