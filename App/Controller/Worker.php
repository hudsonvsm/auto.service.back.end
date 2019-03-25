<?php

namespace App\Controller;

use App\Exceptions\ControllerException;
use Mladenov\Config;
use Mladenov\IController;
use App\Model\Worker as Model;
use Mladenov\IDatabase;
use Mladenov\JsonView;
use Mladenov\View;

class Worker implements IController
{
    private $model;

    /**
     * Worker constructor.
     * @param IDatabase $db
     * @throws ControllerException
     */
    public function __construct(IDatabase $db)
    {
        if (Authenticator::$authorizedUser['scope'] !== 'admin') {
            throw new ControllerException('401 Unauthorized', 401);
        }

        $dbTableColumns = Config::getProperty('tables');

        $this->model = new Model($db, DB_TABLE_WORKER, $dbTableColumns[DB_TABLE_WORKER]);
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
                $view = new View(ReflectionShortName::getClassShortName(__CLASS__), 'index', $out);
                $view->render();
        }
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