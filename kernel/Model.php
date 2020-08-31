<?php

namespace Kernel;

use Exception;

abstract class Model
{
    protected static $database;
protected static string $class_name;

private array $tmp_data = [];
private array $query = ['fields' => [],
'where_clause' => [],
'where_like_clause' => [],];

protected static string $table;
protected static array $all_columns = [];
protected static string $primaryKey = 'id';

protected static array $fillables = [];

    public function __construct()
    {
        foreach (static::$all_columns as $col => $inside) {
            $this->tmp_data[$col] = '';
        }
    }

    private static function connect()
    {
        static::$database = new DB(
            env_get('DB_HOST', 'localhost'),
            env_get('DB_PORT', 3306),
            env_get('DB_USER'),
            env_get('DB_PASS'),
            env_get('DB_NAME'),
            );
        static::$database->connect();
        if (static::$database->error) {
            die("Database Connection Failed!");
        }

        static::$class_name = last(explode('\\', get_class(new static)));

        $table = static::$database->show_tables_like(strtolower(static::$class_name));
        if ($table) {
            static::$table = $table;
        } else {
            die("Table of " . static::$class_name . " not found!");
        }

        static::$all_columns = static::$database->get_table_columns(static::$table);
    }

    public function __get($name)
    {
        if (isset($this->tmp_data[$name]))
            return $this->tmp_data[$name];
        else
            throw new Exception("$name does not exists");
    }

    public function __set($name, $value)
    {
        if (isset($this->tmp_data[$name]))
            $this->tmp_data[$name] = $value;
        else
            throw new Exception("$name does not exists");
    }

    public static function find($primaryKey): ?object
    {
        if (is_null(static::$database)) static::connect();

        $result = static::$database->oneSelect(static::$table, null, [static::$primaryKey => $primaryKey]);

        if (is_null($result)) return null;

        $obj = new static;
        $obj->tmp_data = $result;
        return $obj;
    }

    public static function create($data): ?int
    {
        if (is_null(static::$database)) static::connect();

        return static::$database->insert(
            static::$table,
            $data,
            false
        );

    }

    public static function info(): ?array
    {
        if (is_null(static::$database)) static::connect();
        return [
            'class'  => static::$class_name,
            'table'  => static::$table,
            'fields' => static::$all_columns,
        ];
    }

    public function save()
    {
        static::$database->insertOrUpdate(
            static::$table,
            $this->tmp_data,
            [static::$primaryKey => $this->tmp_data[static::$primaryKey]],
            );
    }

    public function update()
    {
        static::$database->update(
            static::$table,
            $this->tmp_data,
            sprintf("`%s` = '%s'", static::$primaryKey, $this->tmp_data[static::$primaryKey]),
            );
    }

    public static function all()
    {
        if (is_null(static::$database)) static::connect();
        return static::$database->Select(static::$table, null, true);
    }

    /**
     * @return mixed
     */
    public static function instance()
    {
        if (is_null(static::$database)) static::connect();
        return (new static);
    }

    public function where(array $where_clause)
    {
        $this->query['where_clause'][] = $where_clause;
        return $this;
    }

    public function whereLike(array $where_clause)
    {
        $this->query['where_like_clause'][] = $where_clause;
        return $this;
    }

    public function get(?array $fields = null)
    {
        $this->query['fields'] = $fields;

        $where_clause = [];
        foreach ($this->query['where_clause'] as $inside)
            $where_clause = array_merge_recursive($where_clause, $inside);

        $where_like_clause = [];
        foreach ($this->query['where_clause'] as $inside)
            $where_like_clause = array_merge_recursive($where_like_clause, $inside);

        $items = static::$database->gSelect(
            static::$table,
            $this->query['fields'],
            $where_clause,
            $where_like_clause,
            );

        $ret = [];
        foreach ($items as $item) {
            $obj = new static;
            foreach ($item as $key => $val)
                $obj->{$key} = $val;
            $ret[] = $obj;
        }

        return $ret;
    }

    public function first(?array $fields = null)
    {
        $this->query['fields'] = $fields;

        $where_clause = [];
        foreach ($this->query['where_clause'] as $inside)
            $where_clause = array_merge_recursive($where_clause, $inside);

        $this->tmp_data = static::$database->oneSelect(
            static::$table,
            $this->query['fields'],
            $where_clause
        );
        return $this;
    }


}