<?php

namespace Kernel\Abstractions;

abstract class AbsDbConnection implements ITransactionalDbConnection, ISqlDb, ITableBaseDb
{
    protected IEnvEngine $env_engine;
    protected IErrorHandler $error_handler;

    protected bool $on_demand;
    protected string $state;

    public function __construct(IEnvEngine $envEngine, IErrorHandler $errorHandler, bool $on_demand = false)
    {
        $this->on_demand = $on_demand;
        $this->env_engine = $envEngine;
        $this->error_handler = $errorHandler;
        $this->state = 'created';

        $this->connect();
    }

    abstract protected function connect();

    abstract protected function disconnect();
}