<?php
namespace App\Model;

use Mladenov\IDatabase;

class I18N extends GeneralModel
{
    public function __construct(IDatabase $database, string $tableName, array $columns)
    {
        parent::__construct($database, $tableName, $columns);
    }

    public function getLocalization(string $lang, array $values)
    {
        $preparation = implode(", ", array_fill(0, count($values), '?'));

        $dbLang = "name_{$lang}";

        $sql = "SELECT `key`, `{$dbLang}`
                FROM `{$this->getTableName()}`
                WHERE `key` IN ({$preparation});";

        $prepared =  $this->getDb()->getConnection()->prepare($sql);

        $prepared->execute($values);

        $results = $prepared->fetchAll(\PDO::FETCH_ASSOC);

        $out = array();
        foreach ($results as $value){
            $out[$value['key']] = $value[$dbLang];
        }

        return $out;
    }
}