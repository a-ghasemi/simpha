<?php
/**
 * Created by PhpStorm.
 * User: aragon
 * Date: 9/5/20
 * Time: 12:20 PM
 */

namespace Kernel;


class Table
{
    public function __construct($table_name)
    {
        $this->database->create_table($table_name, $fields);
    }

    function autoincremental($name, $length = 7)
    {
        $query_string = "`$name` INT($length) AUTO_INCREMENT PRIMARY KEY";

        return $query_string;
    }

    function integer($name, $length = 10)
    {
        $query_string = "`$name` INT($length)";

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

    function timestamp($name, $default = 'CURRENT_TIMESTAMP', $on_update = 'CURRENT_TIMESTAMP')
    {
        $query_string = "`$name` TIMESTAMP DEFAULT $default";
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
}