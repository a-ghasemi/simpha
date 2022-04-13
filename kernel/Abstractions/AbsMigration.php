<?php


namespace Kernel\Abstractions;

class AbsMigration
{
    protected $db_connection;

    public function __construct(AbsDbConnection $dbConnection)
    {
        $this->db_connection = $dbConnection;
    }

    function drop($table_name)
    {
        $this->db_connection->dropTable($table_name);
    }

    function create($table_name, $structure)
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

        $this->db_connection->createTable($table_name, $fields);
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