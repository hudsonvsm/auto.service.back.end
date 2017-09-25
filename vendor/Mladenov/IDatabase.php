<?php
/**
 * @author Valentin Mladenov
 */

namespace Mladenov;

interface IDatabase {
    
    static function getInstance(array $dbConfig);

    function __destruct();

    function getConnection();

    function executeQuery(string $sql);

    function executePreparedStatement(string $preparedSql, array $data);
}
