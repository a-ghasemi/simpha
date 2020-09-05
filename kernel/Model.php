<?php

namespace Kernel;

use Exception;

abstract class Model
{
    protected static $database;

    private array $tmp_data = [];
    private array $query = ['fields' => [],
        'where_clause' => [],
        'where_like_clause' => [],
    ];

    protected string $class_name;
    protected string $table = '';
    protected array $all_columns = [];
    protected string $primaryKey = 'id';

    protected static array $fillables = [];

    public function __construct($inner_call = false)
    {
        $this->setTable();
        foreach ($this->all_columns as $col => $inside) {
            $this->tmp_data[$col] = '';
        }
    }

    private static function connect()
    {
        self::$database = new DB(
            env_get('DB_HOST', 'localhost'),
            env_get('DB_PORT', 3306),
            env_get('DB_USER'),
            env_get('DB_PASS'),
            env_get('DB_NAME'),
        );
        self::$database->connect();
        if (self::$database->error) {
            die("Database Connection Failed!");
        }
    }

    private function setTable()
    {
        if (empty(self::$database)) static::connect();
        $this->class_name = last(explode('\\', get_class($this)));

        $table = self::$database->show_tables_like(strtolower($this->class_name));
        if ($table) {
            $this->table = $table;
        } else {
            die("Table of " . $this->class_name . " not found!");
        }

        $this->all_columns = self::$database->get_table_columns($this->table);

        return $this;
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
        $obj = new static;
        $result = self::$database->oneSelect($obj->table, null, [$obj->primaryKey => $primaryKey]);
        if (is_null($result)) return null;
        $obj->tmp_data = $result;
        return $obj;
    }

    public static function create($data): ?int
    {
        $obj = new static;
        return self::$database->insert(
            $obj->table,
            $data,
            false
        );
    }

    public static function info(): ?array
    {
        $obj = new static;
        return [
            'class' => $obj->class_name,
            'table' => $obj->table,
            'fields' => $obj->all_columns,
        ];
    }

    public function save()
    {
        self::$database->insertOrUpdate(
            $this->table,
            $this->tmp_data,
            [$this->primaryKey => $this->tmp_data[$this->primaryKey]],
        );
    }

    public function update()
    {
        self::$database->update(
            $this->table,
            $this->tmp_data,
            sprintf("`%s` = '%s'", $this->primaryKey, $this->tmp_data[$this->primaryKey]),
        );
    }

    public static function all()
    {
        $tobj = new static;
        $items = self::$database->gSelect(
            $tobj->table,
            null,
            true
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

    private function whereFunc(array $where_clause)
    {
        $this->query['where_clause'] = array_merge_recursive($this->query['where_clause'], $where_clause);
        return $this;
    }

    public function whereLike(array $where_clause)
    {
        $this->query['where_like_clause'] = array_merge_recursive($this->query['where_like_clause'], $where_clause);
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

        $items = self::$database->gSelect(
            $this->table,
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

        $this->tmp_data = self::$database->oneSelect(
            $this->table,
            $this->query['fields'],
            $where_clause
        );
        return $this;
    }


    public function __call($name, $arguments)
    {
        switch($name){
            case 'where':
                $obj = new static;
                return $obj->whereFunc($arguments);
                break;
            default:
                throw new Exception('Function not found');

        }
    }

    public static function __callStatic($name, $arguments)
    {
        switch($name){
            case 'where':
                $obj = new static;
                return $obj->whereFunc($arguments);
                break;
            default:
                throw new Exception('Static Function not found');

        }


    }

}