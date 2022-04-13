<?php


namespace Kernel\Abstractions;

abstract class AbsSeed
{
    protected $database;

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

    public abstract function run();

}