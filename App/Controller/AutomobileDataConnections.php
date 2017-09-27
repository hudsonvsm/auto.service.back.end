<?php

namespace App\Controller;

use App\Exceptions\ControllerException;
use Mladenov\Config;
use Mladenov\IController;
use App\Model\Color as ColorModel;
use App\Model\Client as ClientModel;
use App\Model\AutomobileBrandModel as AutomobileBrandModelModel;
use App\Model\AutomobileBrand as AutomobileBrandModel;
use Mladenov\IDatabase;
use Mladenov\JsonView;

class AutomobileDataConnections implements IController
{
    private $colorModel;
    private $clientModel;
    private $brandModelModel;
    private $brandModel;

    public function __construct(IDatabase $db)
    {
        $dbTableColumns = Config::getProperty('tables');

        $this->colorModel = new ColorModel($db, DB_TABLE_COLOR, $dbTableColumns[DB_TABLE_COLOR]);
        $this->clientModel = new ClientModel($db, DB_TABLE_CLIENT, $dbTableColumns[DB_TABLE_CLIENT]);
        $this->brandModelModel = new AutomobileBrandModelModel($db, DB_TABLE_AUTOMOBILE_BRAND_MODEL, $dbTableColumns[DB_TABLE_AUTOMOBILE_BRAND_MODEL]);
        $this->brandModel = new AutomobileBrandModel($db, DB_TABLE_AUTOMOBILE_BRAND, $dbTableColumns[DB_TABLE_AUTOMOBILE_BRAND]);
    }

    public function addItem($params)
    {
        throw new ControllerException("Unsupported Request method: Forbidden.");
    }

    public function getCollection(array $params)
    {
        $out['params'] = $params;
        $out['colors'] = $this->colorModel->getCollection($params);
        $out['clients'] = $this->clientModel->getCollection($params);
        $out['brandModels'] = $this->brandModelModel->getCollection($params);
        $out['brands'] = $this->brandModel->getCollection($params);

        return JsonView::render($out);
    }

    public function getItem($id)
    {
        throw new ControllerException("Unsupported Request method: Forbidden.");
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