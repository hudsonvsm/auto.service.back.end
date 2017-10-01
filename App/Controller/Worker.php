<?php

namespace App\Controller;

use Mladenov\Config;
use Mladenov\IController;
use App\Model\Worker as Model;
use Mladenov\IDatabase;
use Mladenov\JsonView;

class Worker implements IController
{
    private $model;

    public function __construct(IDatabase $db)
    {
        $dbTableColumns = Config::getProperty('tables');

        $this->model = new Model($db, DB_TABLE_WORKER, $dbTableColumns[DB_TABLE_WORKER]);
    }

    public function addItem($params)
    {
        throw new ControllerException("Unsupported Request method: Forbidden.");
    }

    public function getCollection(array $params)
    {
        $out['params'] = $params;
        $out['data'] = $this->model->getCollection($params);

        $out['count'] = $this->model->getCount();

        $out['count'] = $out['count'][0]['count'];

        return JsonView::render($out);
    }

    public function getItem($id)
    {
        return JsonView::render($this->model->getOne($id));
    }

    public function deleteItem($id)
    {
        throw new ControllerException("Unsupported Request method: Forbidden.");
    }

    public function updateItem($id, $params)
    {
        throw new ControllerException("Unsupported Request method: Forbidden.");
    }
}