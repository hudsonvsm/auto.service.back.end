<?php

namespace App\Controller;

use App\Model\GeneralModel;
use Mladenov\Config;
use Mladenov\IController;
use App\Model\RepairCard as Model;
use Mladenov\IDatabase;
use Mladenov\JsonView;

class RepairCard implements IController
{
    private $model;

    public function __construct(IDatabase $db)
    {
        $dbTableColumns = Config::getProperty('tables');

        $this->model = new Model($db, DB_TABLE_REPAIR_CARD, $dbTableColumns[DB_TABLE_REPAIR_CARD]);
    }

    public function addItem($params)
    {
        return JsonView::render($this->model->insertNewItem($params));
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
        return JsonView::render(['deleted' => $this->model->deleteItem($id) ]);
    }

    public function updateItem($id, $params)
    {
        return JsonView::render(['result' => $this->model->updateItem($id, $params) ]);
    }
}