<?php


namespace Kernel\Abstractions;

abstract class AbsSeed
{
    protected $db_connection;

    public function __construct(AbsDbConnection $dbConnection)
    {
        $this->db_connection = $dbConnection;
    }

    public abstract function run();

}