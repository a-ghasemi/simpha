<?php

namespace Kernel;

use Kernel\Abstractions\AbsDbConnection;
use Kernel\Abstractions\IEnvEngine;
use Kernel\Abstractions\IErrorHandler;

class MysqlDbConnection extends AbsDbConnection
{
    protected $connection;

    protected function connect()
    {
        try {
            $this->connection = new \mysqli(
                $this->env_engine->get('DB_HOST', 'localhost'),
                $this->env_engine->get('DB_USER'),
                $this->env_engine->get('DB_PASS'),
                $this->env_engine->get('DB_NAME'),
                (int) $this->env_engine->get('DB_PORT', 3306),
            );
        } catch (\Exception $e) {
            $this->error_handler->addError('MySql Database Connection Failed!', $e->getMessage());
            $this->error_handler->throwError();
        }

        if ($this->connection->connect_error) {
            $this->error_handler->addError('MySql Database Connection Failed!', $this->connection->connect_error);
            $this->error_handler->throwError();
            return;
        }

//        $this->connection->autocommit(!$this->on_demand);
        $this->state = 'connected';
    }
}