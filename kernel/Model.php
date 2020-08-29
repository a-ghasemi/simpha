<?php

namespace Kernel;


abstract class Model
{
    protected static $database;
    protected static $class_name;

    private $tmp_data;

    protected static $table;
    protected static $all_columns = [];
    protected static $primaryKey = 'id';

    protected static $fillables = [];

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

    public static function find($primaryKey): ?array
    {
        if(is_null(static::$database)) static::connect();

        return static::$database->oneSelect(static::$table, null, [static::$primaryKey => $primaryKey]);
    }

    public static function create($data): ?int
    {
        if(is_null(static::$database)) static::connect();

        return static::$database->insert(
            static::$table,
            $data,
            false
        );

    }

    public static function info(): ?array
    {
        if(is_null(static::$database)) static::connect();
        return [
            'class' => static::$class_name,
            'table' => static::$table,
            'fields' => static::$all_columns,
        ];
    }

    public function save()
    {
        static::$database->insertOrUpdate(
            static::$table,
            static::$fillables,
            array_values($this->tmp_data),
            sprintf("`%s` = '%s'",static::$primaryKey,$this->tmp_data[static::$primaryKey]),
        );
    }

    public static function all()
    {
        if(is_null(static::$database)) static::connect();
        return static::$database->Select(static::$table, null, true);
    }




}