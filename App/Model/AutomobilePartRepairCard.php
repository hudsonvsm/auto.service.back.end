<?php

namespace App\Model;

use Mladenov\IDatabase;

class AutomobilePartRepairCard extends GeneralModel
{
    public function __construct(IDatabase $database, string $tableName, array $columns)
    {
        parent::__construct($database, $tableName, $columns);
    }

    public function getCustomCollection(string $key, string $value, $like = 'LIKE')
    {
        $sql = "SELECT aprc.*, ap.name AS part_name, ap.price AS part_price
                FROM `{$this->getTableName()}` AS aprc
                JOIN `" . DB_TABLE_AUTOMOBILE_PART . "` AS ap ON ap.id=aprc.automobile_part_id
                WHERE aprc.{$key} {$like} ({$value});";

        $result = $this->getDb()->fetchArray($sql);

        return $this->assignReturnValuesToObject($result);
    }

    public function insertNewItem(array $params) : array
    {
        $allowedParams = [];

        foreach ($params as $key => $param) {
            if(in_array($key, $this->getColumns())) {
                $allowedParams[$key] = $param;
            }
        }

        return parent::insert($this->getTableName(), $allowedParams);
    }
}