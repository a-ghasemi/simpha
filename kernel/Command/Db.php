<?php

namespace Kernel\Command;


use Kernel\Abstractions\AbsCommand;
use Kernel\ClassMap;

class Db extends AbsCommand
{
    const NAMESPACE_MIGRATION = 'App\\Database\\Migrations';

    public function migrate()
    {
        $migration_classes = ClassMap::map(self::NAMESPACE_MIGRATION, app_path('Database/Migrations'));

        foreach ($migration_classes as $class => $methods) {
            $obj = self::NAMESPACE_MIGRATION . "\\" . $class;
            $obj = new $obj($this->db_connection, $this->error_handler);
            $obj->up();
            $this->comment("Table [$class] Created Successfully.");
        }
    }

    public function rollback()
    {
        $migration_classes = ClassMap::map(self::NAMESPACE_MIGRATION, app_path('Database/Migrations'));
        $migration_classes = array_reverse($migration_classes);
        foreach ($migration_classes as $class => $methods) {
            $obj = self::NAMESPACE_MIGRATION . "\\" . $class;
            $obj = new $obj($this->db_connection);
            $obj->down();
            $this->comment("Table [$class] Dropped Successfully.");
        }
    }

    public function renew()
    {
        $this->rollback();
        $this->migrate();
    }
}