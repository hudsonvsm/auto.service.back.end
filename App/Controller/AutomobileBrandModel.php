<?php

namespace App\Controller;

use Mladenov\Config;
use Mladenov\IController;
use App\Model\AutomobileBrandModel as Model;
use Mladenov\IDatabase;
use Mladenov\JsonView;
use Mladenov\View;

class AutomobileBrandModel implements IController
{
    private $model;

    public function __construct(IDatabase $db)
    {
        $dbTableColumns = Config::getProperty('tables');

        $this->model = new Model($db, DB_TABLE_AUTOMOBILE_BRAND_MODEL, $dbTableColumns[DB_TABLE_AUTOMOBILE_BRAND_MODEL]);
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

        switch ($params['returnDataType']) {
            case 'json':
                return JsonView::render($out);
            case null;
                $view = new View('error404', 'index', $out);
                $view->render();
        }
    }

    public function getItem($id)
    {
        return JsonView::render($this->model->getOne($id));
    }

    public function deleteItem($id)
    {
        return $this->model->deleteItem($id);
    }

    public function updateItem($id, $params)
    {
        return $this->model->updateItem($id, $params);
    }
}