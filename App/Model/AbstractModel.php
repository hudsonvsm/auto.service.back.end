<?php

/**
 * @author Valentin Mladenov
 */
namespace App\Model;

use Mladenov\IDatabase;

/**
 * Class AbstractModel
 *
 * @package Mladenov
 */
abstract class AbstractModel
{
    /**
     * @var \Mladenov\IDatabase $db
     */
    private $db;

    /**
     * MySqlModel constructor.
     *
     * @param \Mladenov\IDatabase $database
     */
    protected function __construct(IDatabase $database)
    {
        $this->db = $database::getInstance([]);
    }

    /**
     * @return null|\Mladenov\IDatabase
     */
    public function getDb()
    {
        return $this->db;
    }

    /**
     * Checks for existence of an element and insert the data if not found.
     * IN other case updates the current Date time.
     *
     * @param string $tableName the name of the table passed from child element.
     * @param array  $data      which will be inserted or updated
     *
     * @return array
     */
    protected function addEntry(string $tableName, array $data)
    {
        $element = $this->findByFilters($tableName, [], [], [], $data);
        if ($element == NULL) {
            return $this->insert($tableName, $data);
        } else {
            $this->update($tableName, [ $element[0]['id'] ], $data);

            return $element[0];
        }
    }

    /**
     * @param string $tableName the name of the table passed from child element.
     * @param array  $columns
     * @param array  $limits
     * @param array  $sort
     * @param array  $search
     * @param string $like      SQL key word surrounded by spaces can be LIKE, =, NOT LIKE.
     *
     * @return array The found object, can be empty if no element is found.
     * @internal param array $data constrains of the search
     */
    protected function findByFilters(string $tableName, array $columns, array $limits, array $sort, array $search, $like = '=')
    {

        $columns = empty($columns) ? ' * ' : '`' . implode('`, `', $columns) . '`';

        $where =  $this->whereBuilder($search, ' AND ', $like);

        $where = empty($where) ? '' : DB_WHERE_VALUE . ' ' . $where;

        $sort = empty($sort) ? '' : DB_ORDER_BY_VALUE . " `{$sort['sortBy']}` {$sort['sortDirection']}";

        $limits = empty($limits) ? '' : DB_LIMIT_VALUE . " {$limits['start']}, {$limits['count']}";

        $sql = "SELECT {$columns} FROM `{$tableName}` {$where} {$sort} {$limits};";

        return $this->db->fetchArray($sql);
    }

    /**
     * @param string $tableName         the name of the table passed from child element.
     * @param array  $identification    with data as array
     * @param array  $data              which will be updated
     */
    protected function update(string $tableName, array $identification, array $data)
    {
        $setData = '`' . implode('`=?, `', array_keys($data)) . '`=?';

        $sql = "UPDATE `{$tableName}` SET {$setData} WHERE `id`=?";

        $preparedData = array_values(array_merge($data, $identification));

        return $this->db->executePreparedStatement($sql, $preparedData);
    }

    /**
     * @param string $tableName         the name of the table passed from child element.
     * @param string $identification
     *
     * @return bool
     */
    protected function delete(string $tableName, string $identification) : bool
    {
        $sql = "DELETE FROM `{$tableName}` WHERE `id`=?";

        return $this->db->executePreparedStatement($sql, [ $identification ]);
    }

    /**
     * @param string $tableName the name of the table passed from child element.
     * @param array  $data      which will be inserted
     *
     * @return array
     */
    protected function insert(string $tableName, array $data) : array
    {
        $keys = '`' . implode('`, `', array_keys($data)) . '`';

        $values = implode(", ", array_fill(0, count($data), '?'));

        $sql = "INSERT INTO `{$tableName}` ({$keys}) VALUES ({$values});";

        $result = $this->db->executePreparedStatement($sql, array_values($data));

        return $result ? ['id' => $this->db->getConnection()->lastInsertId()] : [];
    }

    /**
     * @param string $tableName the name of the table passed from child element.
     *
     * @return array
     */
    protected function countRows(string $tableName) : array
    {
        $sql = "SELECT COUNT(*) AS `count` FROM `{$tableName}`;";

        return $this->db->fetchArray($sql);
    }

    /**
     * @param   array  $data              to be build as where string
     * @param   string $whereSeparator    main separator between major constrains (AND, OR)
     * @param   string $keyValueSeparator separator between the key value (id{=}1)
     *
     * @return  string Fully build where (`id`='1' AND `data`='book')
     */
    private function whereBuilder ($data = array(), $whereSeparator = ', ', $keyValueSeparator = '=')
    {
        $result = array();

        foreach ($data as $key => $value) {
            $keyValueSeparatorIn = $keyValueSeparator;
            switch (true) {
                case (is_null($value)):
                    $valueIn = DB_NULL_VALUE;
                    $keyValueSeparatorIn = DB_IS_VALUE;
                break;
                case ($value === DB_EXPRESSION_NOW):
                    $valueIn = $value;
                break;
                default:
                    $valueIn = "'{$value}'";
                break;
            }

            $result[] = "`{$key}` {$keyValueSeparatorIn} {$valueIn}";
        }

        return implode($whereSeparator, $result);
    }
}
