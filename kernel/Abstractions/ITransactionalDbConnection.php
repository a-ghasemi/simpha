<?php

namespace Kernel\Abstractions;

interface ITransactionalDbConnection
{
    public function commit();
    public function beginTransaction();
    public function rollback();

}