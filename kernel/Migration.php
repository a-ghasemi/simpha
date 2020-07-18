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
        if($this->database->error){
            die("Database Connection Failed!");
        }
    }

    function drop_table($table_name){
        $this->database->drop_table($table_name);
    }

    function create_table($table_name, $structure)
    {
        $fields = [];
        foreach($structure as $field){
            if(is_array($field))
                foreach($field as $f){
                    $fields[] = $f;
                }
            else{
                $fields[] = $field;
            }
        }

        $this->database->create_table($table_name, $fields);
    }

    function autoincremental($name, $length = 7){
        $query_string = "`$name` INT($length) AUTO_INCREMENT PRIMARY KEY";

        return $query_string;
    }
    function string($name, $length = 255){
        $query_string = "`$name` VARCHAR($length)";

        return $query_string;
    }
    function timestamp($name, $default = 'CURRENT_TIMESTAMP', $on_update = 'CURRENT_TIMESTAMP'){
        $query_string = "`$name` TIMESTAMP DEFAULT $default ON UPDATE $on_update";

        return $query_string;
    }

    function timestamps(){
        return [
            $this->timestamp('created_at'),
            $this->timestamp('updated_at'),
        ];
    }
}