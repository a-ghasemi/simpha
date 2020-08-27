<?php

namespace Kernel;


abstract class Model
{
    private $database;
    private $class_name;
    private $identity_field = 'id';
    private $tmp_data;

    protected $table_name = '';
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

        $this->class_name = STATIC::__CLASS__;
        $tables = $this->database->show_tables_like(strtolower($this->class_name));
        if (count($tables)) {
            $this->table_name = $tables[0];
        } else {
            die("Table of {$this->class_name} not found!");
        }
    }

    public function save()
    {
        $this->database->insertOrUpdate(
            $this->table_name,
            $this->fillables,
            array_values($this->tmp_data),
            "`{$this->identity_field}` = '" .$this->tmp_data[$this->identity_field]. "'"
        );

    }

}