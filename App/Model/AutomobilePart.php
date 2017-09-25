<?php

namespace App\Model;

use Mladenov\IDatabase;

class AutomobilePart extends GeneralModel
{
    public function __construct(IDatabase $database, string $tableName, array $columns)
    {
        parent::__construct($database, $tableName, $columns);
    }
}