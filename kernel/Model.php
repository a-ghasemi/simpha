<?php

namespace Kernel;


abstract class Model
{
    private $database;
    private $class_name;

    private $tmp_data;

    protected $table = '';
    protected $all_columns = [];
    protected $primaryKey = 'id';

    protected $fillables = [];

    public function __construct()
    {
        $this->database = new DB(
            env_get('DB_HOST', 'localhost'),
            env_get('DB_PORT', 3306),
            env_get('DB_USER'),
            env_get('DB_PASS'),
            env_get('DB_NAME'),
        );
        $this->database->connect();
        if ($this->database->error) {
            die("Database Connection Failed!");
        }

        $this->class_name = last(explode('\\', get_class($this)));

        $table = $this->database->show_tables_like(strtolower($this->class_name));
        if ($table) {
            $this->table = $table;
        } else {
            die("Table of {$this->class_name} not found!");
        }

        $this->all_columns = $this->database->get_table_columns($this->table);
    }

    public static function find(): ?object
    {

    }

    public function create(): ?int
    {

    }

    public function info(): ?array
    {
        return [
            'class' => $this->class_name,
            'table' => $this->table,
            'fields' => $this->all_columns,
        ];
    }

    public function save()
    {
        $this->database->insertOrUpdate(
            $this->table,
            $this->fillables,
            array_values($this->tmp_data),
            "`{$this->primaryKey}` = '" . $this->tmp_data[$this->primaryKey] . "'"
        );
    }

    public static function all()
    {

    }

}