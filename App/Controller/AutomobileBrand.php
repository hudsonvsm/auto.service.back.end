<?php

namespace App\Controller;

use App\Model\GeneralModel;
use Mladenov\Config;
use Mladenov\IController;
use App\Model\AutomobileBrand as Model;
use Mladenov\IDatabase;
use Mladenov\JsonView;
use Mladenov\View;

class AutomobileBrand implements IController
{
    private $model;

    public function __construct(IDatabase $db)
    {
        $dbTableColumns = Config::getProperty('tables');

        $this->model = new Model($db, DB_TABLE_AUTOMOBILE_BRAND, $dbTableColumns[DB_TABLE_AUTOMOBILE_BRAND]);
    }

    public function addItem($params)
    {
        return $this->model->insertNewItem($params);
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

    /**
     * @return \App\Model\GeneralModel
     */
    public function getModel() : GeneralModel
    {
        return $this->model;
    }
}