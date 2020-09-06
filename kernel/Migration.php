<?php


namespace Kernel;

use Kernel\DB;

class Migration
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

    function create_table($table_name, $structure)
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

    function autoincremental($name, $length = 7)
    {
        $query_string = "`$name` INT($length) AUTO_INCREMENT PRIMARY KEY";

        return $query_string;
    }

    function boolean($name)
    {
        $query_string = "`$name` TINYINT(1)";

        return $query_string;
    }

    function tinyInteger($name, $length = 3)
    {
        $query_string = "`$name` TINYINT($length)";

        return $query_string;
    }

    function integer($name, $length = 10)
    {
        $query_string = "`$name` INT($length)";

        return $query_string;
    }

    function bigInteger($name, $length = 10)
    {
        $query_string = "`$name` BIGINT($length)";

        return $query_string;
    }

    function double($name, $length = 8, $decimal = 5)
    {
        $query_string = "`$name` DOUBLE($length,$decimal)";

        return $query_string;
    }

    function float($name, $length = 8, $decimal = 5)
    {
        $query_string = "`$name` FLOAT($length,$decimal)";

        return $query_string;
    }

    function enum($name, array $values)
    {
        $query_string = "`$name` ENUM('" . implode("','",$values) . "')";

        return $query_string;
    }

    function string($name, $length = 255)
    {
        $query_string = "`$name` VARCHAR($length)";

        return $query_string;
    }

    function text($name)
    {
        $query_string = "`$name` TEXT";

        return $query_string;
    }

    function json($name)
    {
        $query_string = "`$name` TEXT";

        return $query_string;
    }

    function timestamp($name, $default = 'CURRENT_TIMESTAMP', $on_update = 'CURRENT_TIMESTAMP')
    {
        $query_string = "`$name` TIMESTAMP DEFAULT $default";
        $query_string .= $on_update ? " ON UPDATE $on_update" : "";

        return $query_string;
    }

    function date($name, $default = 'CURRENT_TIMESTAMP', $on_update = 'CURRENT_TIMESTAMP')
    {
        $query_string = "`$name` DATE ";
        $query_string .= $default ? " DEFAULT $default" : "";
        $query_string .= $on_update ? " ON UPDATE $on_update" : "";

        return $query_string;
    }

    function dateTime($name, $default = 'CURRENT_TIMESTAMP', $on_update = 'CURRENT_TIMESTAMP')
    {
        $query_string = "`$name` DATETIME ";
        $query_string .= $default ? " DEFAULT $default" : "";
        $query_string .= $on_update ? " ON UPDATE $on_update" : "";

        return $query_string;
    }

    function timestamps()
    {
        return [
            $this->timestamp('created_at', 'CURRENT_TIMESTAMP', null),
            $this->timestamp('updated_at'),
        ];
    }

    function geometry($name)
    {
        $query_string = "`$name` GEOMETRY";

        return $query_string;
    }

}