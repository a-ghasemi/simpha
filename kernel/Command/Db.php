<?php

namespace Kernel\Command;


use Kernel\Abstractions\AbsCommand;
use Kernel\ClassMap;

class Db extends AbsCommand
{
    public function check()
    {
        $this->comment('Database Connection Successfully.');
    }

    public function migrate()
    {
        $namespace = 'App\\Database\\Migrations';
        $migration_classes = ClassMap::map($namespace, app_path('Database/Migrations'));

        foreach ($migration_classes as $class => $methods) {
            $obj = $namespace . "\\" . $class;
            $obj = new $obj($this->db_connection, $this->error_handler);
            $obj->up();
            $this->comment("Table [$class] Created Successfully.");
        }

    }

    public function info()
    {
        $namespace = 'App\\Database\\Migrations';
        $migration_classes = ClassMap::map($namespace, app_path('database/migrations'));
        foreach ($migration_classes as $class => $methods) {
            $obj = $namespace . "\\" . $class;
            $obj = new $obj($this->db_connection);
            $obj->up();
            $this->comment("Table [$class] Created Successfully.");
        }

    }

    public function seed(): void
    {
        $folder = $this->parameters[0] ?? '';

        $namespace = 'App\\Database\\Seeds' . ($folder ? "\\$folder" : "");
        $seed_classes = ClassMap::map($namespace, app_path('Database/Seeds/' . $folder));

        foreach ($seed_classes as $class => $methods) {
            $obj = $namespace . "\\" . $class;
            $obj = new $obj($this->db_connection);
            $obj->run();
            $this->comment("Seed [$class] Executed Successfully.");
        }
    }

    public function rollback()
    {
        $namespace = 'App\\database\\migrations';
        $migration_classes = ClassMap::map($namespace, app_path('Database/Migrations'));
        $migration_classes = array_reverse($migration_classes);
        foreach ($migration_classes as $class => $methods) {
            $obj = $namespace . "\\" . $class;
            $obj = new $obj;
            $obj->down();
            $this->comment("Table [$class] Dropped Successfully.");
        }

    }

    public function renew()
    {
        $this->rollback();
        $this->migrate();
        $this->seed();
    }
}