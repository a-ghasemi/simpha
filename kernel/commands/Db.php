<?php

namespace Kernel\Command;


use Kernel\ClassMap;
use Kernel\Command;

class Db extends Command
{
    private $database;

    public function check()
    {
        $this->connectDatabase(
            env_get('DB_USER'),
            env_get('DB_PASS'),
            env_get('DB_NAME'),
            env_get('DB_HOST', 'localhost'),
            env_get('DB_PORT', 3306),
        );
        $this->comment('Database Connection Successfully.');
    }

    public function migrate()
    {
        $namespace = 'App\\database\\migrations';
        $migration_classes = ClassMap::map($namespace, app_path('database/migrations'));
        foreach ($migration_classes as $class => $methods) {
            $obj = $namespace . "\\" . $class;
            $obj = new $obj;
            $obj->up();

            # TODO: Create regex to remove CREATE####TABLE around $class

            $this->comment("Table [$class] Created Successfully.");
        }

    }

    private function connectDatabase($user, $pass, $db_name, $host = 'localhost', $port = '3306')
    {
        $this->database = new \Kernel\DB($host, $port, $user, $pass, $db_name);
        $this->database->connect();
        if ($this->database->error) {
            $this->error("Database Connection Failed!");
        }
    }
}