<?php

namespace App\Controller;

use Mladenov\Config;
use Mladenov\IController;
use App\Model\User as Model;
use Mladenov\IDatabase;
use Mladenov\JsonView;
use Mladenov\View;

class User implements IController
{
    private $model;

    public function __construct(IDatabase $db)
    {
        $dbTableColumns = Config::getProperty('tables');

        $oauth = Config::getProperty('oauth');

        $dbDriver = $oauth['dbDriver'];
        $db = $dbDriver::getInstance($oauth);

        $this->model = new Model($db, DB_TABLE_USER, $dbTableColumns[DB_TABLE_USER]);
    }

    public function addItem($params)
    {
        return JsonView::render($this->model->insertNewItem($params));
    }

    public function getCollection(array $params)
    {
        $out['params'] = $params;
        $out['data'] = $this->model->getCollection($params);

        foreach ($out['data'] as $data) {
            // $data->unsetColumn('client_secret');
            unset($data->clientSecret);
        }

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

    /**
     * @param string $id
     *
     * @return false|string
     */
    public function getItem($id)
    {
        return JsonView::render($this->model->getOne($id, 'client_id'));
    }

    /**
     * @param $id
     * @return false|string
     */
    public function deleteItem($id)
    {
        return JsonView::render([ 'deleted' => $this->model->deleteItem($id, 'client_id') ]);
    }

    /**
     * @param $id
     * @param $params
     * @return false|string
     */
    public function updateItem($id, $params)
    {
        return JsonView::render([ 'result' => $this->model->updateItem($id, $params, 'client_id') ]);
    }
}