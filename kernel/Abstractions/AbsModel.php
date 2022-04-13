<?php

namespace Kernel\Abstractions;

use Exception;

abstract class AbsModel
{
    protected AbsDbConnection $db_connection;
    protected IErrorHandler $error_handler;

    private ?array $tmp_data = [];
    private ?array $query = [
        'fields'            => [],
        'where_clause'      => [],
        'where_like_clause' => [],
    ];

    protected string $class_name;
    protected string $table = '';
    protected ?array $all_columns = [];
    protected string $primaryKey = 'id';

    protected static array $fillables = [];

    public function __construct(AbsDbConnection $dbConnection, IErrorHandler $errorHandler)
    {
        $this->db_connection = $dbConnection;
        $this->error_handler = $errorHandler;

        $this->setTable();
        $this->setFields();
    }

    protected function setTable()
    {
        $this->class_name = last(explode('\\', get_class($this)));

        $table = $this->db_connection->showTablesLike(strtolower($this->class_name));
        if ($table) {
            $this->table = $table;
        } else {
            $this->error_handler->addError("Table of " . $this->class_name . " not found!");
            $this->error_handler->throwError();
        }

        $this->all_columns = $this->db_connection->getTableColumns($this->table);

        return $this;
    }

    public function __get($name)
    {
        if (isset($this->tmp_data[$name]))
            return $this->tmp_data[$name];
        else {
            $this->error_handler->addError("$name does not exists");
            $this->error_handler->throwError();
        }
    }

    public function __set($name, $value)
    {
        if (isset($this->tmp_data[$name]))
            $this->tmp_data[$name] = $value;
        else {
            $this->error_handler->addError("$name does not exists");
            $this->error_handler->throwError();
        }
    }

    // Finds a record with its PK
    public static function find($primaryKey): ?object
    {
        $obj = new static::class;
        $result = $obj->db_connection->oneSelect($obj->table, null, [$obj->primaryKey => $primaryKey]);
        if (is_null($result)) return null;
        $obj->tmp_data = $result;
        return $obj;
    }

    // Creates new record on the table
    public static function create($data): ?int
    {
        $obj = new static::class;
        return $obj->db_connection->insert(
            $obj->table,
            $data,
            false
        );
    }

    // Returns info about current model
    public static function info(): ?array
    {
        $obj = new static::class;
        return [
            'class'  => $obj->class_name,
            'table'  => $obj->table,
            'fields' => $obj->all_columns,
        ];
    }

    public function save()
    {
        $this->db_connection->insertOrUpdate(
            $this->table,
            $this->tmp_data,
            [$this->primaryKey => $this->tmp_data[$this->primaryKey]],
        );
    }

    public function update()
    {
        $this->db_connection->update(
            $this->table,
            $this->tmp_data,
            sprintf("`%s` = '%s'", $this->primaryKey, $this->tmp_data[$this->primaryKey]),
        );
    }

    public static function all()
    {
        $tobj = new static::class;
        $items = $tobj->db_connection->generatorSelect(
            $tobj->table,
            null,
            true
        );

        $ret = [];
        foreach ($items as $item) {
            $obj = new static::class;
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

        $items = $this->db_connection->generatorSelect(
            $this->table,
            $this->query['fields'],
            $where_clause,
        );

        $ret = [];
        foreach ($items as $item) {
            $obj = new static::class;
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

        $this->tmp_data = $this->db_connection->singleSelect(
            $this->table,
            $this->query['fields'],
            $where_clause
        );
        return $this;
    }


    public function __call($name, $arguments)
    {
        $obj = new static::class;

        switch ($name) {
            case 'where':
                return $obj->whereFunc($arguments);

            default:
                $obj->error_handler->addError("Function not found");
                $obj->error_handler->throwError();

        }
    }

    public static function __callStatic($name, $arguments)
    {
        $obj = new static::class;

        switch ($name) {
            case 'where':
                return $obj->whereFunc($arguments);

            default:
                $obj->error_handler->addError("Static Function not found");
                $obj->error_handler->throwError();

        }

    }

    protected function setFields(): void
    {
        foreach ($this->all_columns as $col => $inside) {
            $this->tmp_data[$col] = '';
        }
    }

}