<?php

namespace App\Model;

use Mladenov\IDatabase;

class Client extends GeneralModel
{
    public function __construct(IDatabase $database, string $tableName, array $columns)
    {
        parent::__construct($database, $tableName, $columns);
    }
}