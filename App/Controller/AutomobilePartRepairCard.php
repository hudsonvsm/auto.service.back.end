<?php

namespace App\Controller;

use Mladenov\Config;
use Mladenov\IController;
use App\Model\AutomobilePartRepairCard as Model;
use Mladenov\IDatabase;
use Mladenov\JsonView;
use Mladenov\View;

class AutomobilePartRepairCard implements IController
{
    private $model;

    public function __construct(IDatabase $db)
    {
        $dbTableColumns = Config::getProperty('tables');

        $this->model = new Model($db, DB_TABLE_AUTOMOBILE_PART_REPAIR_CARD, $dbTableColumns[DB_TABLE_AUTOMOBILE_PART_REPAIR_CARD]);
    }

    public function addItem($params)
    {
        return JsonView::render($this->model->insertNewItem($params));
    }

    public function getCollection(array $params)
    {
        $out['params'] = $params;

        if ($params['search']) {
            $key = key($params['search']);

            $out['data'] = $this->model->getCustomCollection($key, $params['search'][$key],'IN');
        } else {
            $out['data'] = $this->model->getCollection($params,'=');
        }

        $out['count'] = $this->model->getCount($params);

        $out['count'] = $out['count'][0]['count'];

        switch ($params['returnDataType']) {
            case 'json':
                return JsonView::render($out);
            case null;
                $view = new View('', '', $out);
                $view->loadPartial('table.view.part.card');
        };
    }

    public function getItem($id)
    {
        return json_encode($this->model->getOne($id));
    }

    public function deleteItem($id)
    {
        return JsonView::render([ 'deleted' => $this->model->deleteItem($id) ]);
    }

    public function updateItem($id, $params)
    {
        return $this->model->updateItem($id, $params);
    }
}