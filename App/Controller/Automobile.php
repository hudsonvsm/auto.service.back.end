<?php

namespace App\Controller;

use Mladenov\Config;
use Mladenov\IController;
use App\Model\Automobile as Model;
use Mladenov\IDatabase;
use Mladenov\JsonView;
use \PDOException;

class Automobile implements IController
{
    private $model;

    public function __construct(IDatabase $db)
    {
        $dbTableColumns = Config::getProperty('tables');

        $this->model = new Model($db, DB_TABLE_AUTOMOBILE, $dbTableColumns[DB_TABLE_AUTOMOBILE]);
    }

    public function addItem($params)
    {
        try {
            $out = [ 'result' => $this->model->insertNewItem($params) ];
        } catch (PDOException $ex) {
            $errorKeys = ['engine_number', 'vin_number', 'license_number'];

            $field = '';
            foreach ($errorKeys as $errorKey) {
                if (strpos($ex->getMessage(), $errorKey)) {
                    $field = $errorKey;

                    break;
                }
            }

            $out = [ 'error' => $field ];
        }

        return JsonView::render($out);
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
        return JsonView::render([ 'deleted' => $this->model->deleteItem($id) ]);
    }

    public function updateItem($id, $params)
    {
        return JsonView::render([ 'result' => $this->model->updateItem($id, $params) ]);
    }
}