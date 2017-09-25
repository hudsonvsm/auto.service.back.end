<?php

namespace App\Controller;

use App\Model\GeneralModel;
use Mladenov\Config;
use Mladenov\IController;
use App\Model\Color as Model;
use Mladenov\IDatabase;

class Color implements IController
{
    private $model;

    public function __construct(IDatabase $db)
    {
        $dbTableColumns = Config::getProperty('tables');

        $this->model = new Model($db, DB_TABLE_COLOR, $dbTableColumns[DB_TABLE_COLOR]);
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

        return json_encode($out);
    }

    public function getItem($id)
    {
        return json_encode($this->model->getOne($id));
    }

    public function deleteItem($id)
    {
        throw new ControllerException("Unsupported Request method: Forbidden.");
    }

    public function updateItem($id, $params)
    {
        throw new ControllerException("Unsupported Request method: Forbidden.");
    }

    /**
     * @return \App\Model\GeneralModel
     */
    public function getModel() : GeneralModel
    {
        return $this->model;
    }
}