<?php
/**
 * @author Valentin Mladenov
 */

namespace Mladenov;

/*
* Mysql database class - only one connection allowed
*/
class PdoDatabase implements IDatabase
{
    private $connection;
    private static $_instance; //The single instance
    //const FETCH_ASSOC = \PDO::FETCH_ASSOC;

    /**
     * Get an instance of the Database
     *
     * @param array $dbConfig
     *
     * @return \Mladenov\IDatabase Instance
     */
    public static function getInstance(array $dbConfig)
    {
        if(!self::$_instance) { // If no instance then make one
            self::$_instance = new self($dbConfig);
        }
        return self::$_instance;
    }

    /**
     * PdoDatabase constructor.
     *
     * @param array $dbConfig
     */
    private function __construct(array $dbConfig)
    {
        $connectionString =
            $dbConfig['dbType']
            . ':host='
            . $dbConfig['dbHost']
            . ';dbname='
            . $dbConfig['dbName'];

        $connectionString .= (empty($dbConfig['dbCharset'])) ? '' : ';charset=' . $dbConfig['dbCharset'];

        $this->connection = new \PDO(
            $connectionString,
            $dbConfig['dbUser'],
            $dbConfig['dbPassword'],
            [
                \PDO::ATTR_ERRMODE => \PDO::ERRMODE_EXCEPTION,
                \PDO::MYSQL_ATTR_SSL_CA => $dbConfig['dbSSL'],
            ]
        );
    }

    /**
     * Magic method clone is empty to prevent duplication of connection
     */
    private function __clone() { }

    public function __destruct()
    {
        $this->connection = null;
    }

    public function getConnection()
    {
        return $this->connection;
    }

    public function executeQuery(string $sql)
    {
        return $this->getConnection()->query($sql);
    }

    public function executePreparedStatement(string $preparedSql, array $data)
    {
        $prepared = $this->getConnection()->prepare($preparedSql);

        return $prepared->execute($data);
    }

    public function fetch()
    {
        return $this->getConnection()->fetch();
    }

    /**
     * @param string $sql
     *
     * @return array
     */
    public function fetchArray(string $sql) : array
    {
        $result = $this->executeQuery($sql);

        return $result->fetchAll(\PDO::FETCH_ASSOC);
    }
}
