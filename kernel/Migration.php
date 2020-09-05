<?php


namespace Kernel;

use Kernel\DB;

abstract class Migration
{
    private $database;

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
    }

    function drop_table($table_name)
    {
        $this->database->drop_table($table_name);
    }

    function create_table($table_name, $closure)
    {
        $closure(new Table($table_name));
    }

    function create_table_old($table_name, $structure)
    {
        $fields = [];
        foreach ($structure as $field) {
            if (is_array($field))
                foreach ($field as $f) {
                    $fields[] = $f;
                }
            else {
                $fields[] = $field;
            }
        }

        $this->database->create_table($table_name, $fields);
    }

    abstract function up();
    abstract function down();
}