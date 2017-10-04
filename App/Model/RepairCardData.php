<?php

namespace App\Model;

use Mladenov\IDatabase;

class RepairCardData extends GeneralModel
{
    public function __construct(IDatabase $database, string $tableName, array $columns)
    {
        parent::__construct($database, $tableName, $columns);
    }

    public function getCollection(array $params, $like = 'LIKE')
    {
        if ($params['advanced']) {
            $result = $this->getAdvancedCollection($params);

            return $this->assignReturnValuesToObject($result);
        }

        return parent::getCollection($params, $like);
    }

    private function getAdvancedCollection($params, $isCount = false)
    {
        $sort = DB_ORDER_BY_VALUE . " `{$params['sortBy']}` {$params['sortDirection']}";

        $limits = DB_LIMIT_VALUE . " {$params['start']}, {$params['count']}";

        $columns = empty($this->getColumns()) ? ' * ' : '`' . implode('`, `', $this->getColumns()) . '`';
        if ($isCount) $columns = 'COUNT(*) AS count';

        $where[] = ($params['unfinished'] == 'on') ? " end_date = '0000-00-00' " : "";

        $where[] = (!empty($params['start_date_after'])) ? " start_date > '{$params['start_date_after']}' " : "";

        $where[] = (!empty($params['end_date_before'])) ? " (end_date < '{$params['end_date_before']}' AND end_date > '0000-00-00') " : "";

        $where[] = (!empty($params['license_number'])) ? " license_number = '{$params['license_number']}' " : "";

        $where = implode(' AND ', array_filter($where));

        $sql = "SELECT {$columns} FROM `{$this->getTableName()}` WHERE {$where} {$sort} {$limits};";

        return $this->getDb()->fetchArray($sql);
    }

    function getCount($params = [], $like = '=')
    {
        if ($params['advanced']) {
            return $this->getAdvancedCollection($params, true);
        }

        return parent::getCount($params, $like);
    }
}