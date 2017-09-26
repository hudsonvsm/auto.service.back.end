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
     * @return array
     */
    public function getCount()
    {
        return parent::countRows($this->getTableName());
    }

    /**
     * @param array $params
     *
     * @return array
     */
    public function getCollection(array $params)
    {
        $limits = [
            'start' => $params['start'],
            'count' => $params['count'],

        ];

        $sort =  [
            'sortBy' => $params['sortBy'],
            'sortDirection' => $params['sortDirection'],

        ];

        $search = (!key_exists('searchBy', $params) || !key_exists('search', $params)
            || is_null($params['searchBy']) || is_null($params['search']))
            ? []
            : [
                $params['searchBy'] => $params['search'],
            ];

        $result = parent::findByFilters($this->getTableName(), $this->getColumns(), $limits, $sort, $search, 'LIKE');

        return $this->assignReturnValuesToObject($result);
    }

    /**
     * @param string $id
     *
     * @return array
     */
    public function getOne(string $id)
    {
        $search = [
            'id' => $id,

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
     * @return bool
     */
    public function insertNewItem(array $params)
    {
        $allowedParams = [];

        foreach ($params as $key => $param) {
            if(in_array($key, $this->getColumns())) {
                $allowedParams[$key] = $param;
            }
        }

        return parent::insert($this->getTableName(), $allowedParams);
    }

    /**
     * @param string $id
     * @param array  $params
     *
     * @return mixed
     */
    public function updateItem(string $id, array $params)
    {
        $allowedParams = [];

        foreach ($params as $key => $param) {
            if(in_array($key, $this->getColumns())) {
                $allowedParams[$key] = $param;
            }
        }

        return parent::update($this->getTableName(), [ $this->getColumn('id') => $id ], $allowedParams);
    }

    /**
     * @param string $id
     *
     * @return bool
     */
    public function deleteItem(string $id)
    {
        return parent::delete($this->getTableName(), $id);
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