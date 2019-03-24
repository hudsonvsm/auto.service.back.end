<?php

namespace App\Model;

use Mladenov\IDatabase;

/**
 * Class GeneralModel
 *
 * @package App\Model
 */
abstract class GeneralModel extends AbstractModel
{
    /**
     * @var string
     */
    private $tableName;

    /**
     * @var array
     */
    private $columns = [];

    /**
     * GeneralModel constructor.
     *
     * @param \Mladenov\IDatabase $database
     * @param string              $tableName
     * @param array               $columns
     */
    public function __construct(IDatabase $database, string $tableName, array $columns)
    {
        parent::__construct($database);

        $this->tableName = $tableName;
        $this->columns = $columns;
    }

    /**
     * @return string
     */
    public function getTableName() : string
    {
        return $this->tableName;
    }

    /**
     * remove column from object
     */
    public function unsetColumn($key) : void
    {
        unset($this->columns[$key]);
    }

    /**
     * @return array
     */
    public function getColumns() : array
    {
        return $this->columns;
    }

    /**
     * @param $key
     *
     * @return string
     */
    public function getColumn($key) : string
    {
        return $this->columns[$key];
    }

    /**
     * @param array  $params
     * @param string $like
     *
     * @return array
     */
    public function getCount($params = [], $like = '=')
    {
        $search = (!key_exists('search', $params) && is_null($params['search']))
            ? []
            : $params['search'];

        return parent::countRows($this->getTableName(), $search, $like);
    }

    /**
     * @param array  $params
     * @param string $like
     *
     * @return array
     */
    public function getCollection(array $params, $like = 'LIKE')
    {
        $limits = [
            'start' => $params['start'],
            'count' => $params['count'],

        ];

        $sort =  [
            'sortBy' => $params['sortBy'],
            'sortDirection' => $params['sortDirection'],

        ];

        $search = (!key_exists('search', $params) && is_null($params['search']))
            ? []
            : $params['search'];

        $result = parent::findByFilters($this->getTableName(), $this->getColumns(), $limits, $sort, $search, $like);

        return $this->assignReturnValuesToObject($result);
    }

    /**
     * @param string $id
     * @param string $ident
     *
     * @return array
     */
    public function getOne(string $id, string $ident = 'id')
    {
        $search = [
            $ident => $id,

        ];

        $limits = [
            'start' => 0,
            'count' => 1,

        ];

        $result = parent::findByFilters($this->getTableName(), $this->getColumns(), $limits, [], $search);

        return $result[0];
    }

    /**
     * @param array $params
     *
     * @return array
     */
    public function insertNewItem(array $params) : array
    {
        $allowedParams = [];

        foreach ($params as $key => $param) {
            if(in_array($key, $this->getColumns())) {
                $allowedParams[$key] = $param;
            }
        }

        return parent::addEntry($this->getTableName(), $allowedParams);
    }

    /**
     * @param string $id
     * @param array $params
     * @param string $ident
     *
     * @return mixed
     */
    public function updateItem(string $id, array $params, string $ident = 'id')
    {
        $allowedParams = [];

        foreach ($params as $key => $param) {
            if(in_array($key, $this->getColumns())) {
                $allowedParams[$key] = $param;
            }
        }

        return parent::update($this->getTableName(), [ $this->getColumn('id') => $id ], $allowedParams, $ident);
    }

    /**
     * @param string $id
     * @param string $ident
     *
     * @return bool
     */
    public function deleteItem(string $id, string $ident = 'id')
    {
        return parent::delete($this->getTableName(), $id, $ident);
    }

    /**
     * @param $result
     *
     * @return array
     */
    protected function assignReturnValuesToObject($result) : array
    {
        $out = [];
        foreach ($result as $entity) {
            $class = get_called_class();

            $outClass = new $class($this->getDb(), $this->getTableName(), $entity);

            foreach ($entity as $key => $value) {
                $ormKey = lcfirst(str_replace('_', '', ucwords($key, '_')));

                $outClass->{$ormKey} = $value;
            }

            $out[] = $outClass;
        }

        return $out;
    }
}