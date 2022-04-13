<?php

namespace Kernel\Abstractions;

abstract class AbsCommand
{
    protected IDataStorage $data_storage;
    protected AbsDbConnection $db_connection;

    public function __construct(IDataStorage $dataStorage, AbsDbConnection $dbConnection)
    {
        $this->data_storage = $dataStorage;
        $this->db_connection = $dbConnection;
    }

    final public function run()
    {
        $function = implode('_', $this->data_storage->get('subcommands'));

        if (!method_exists($this, $function)) {
            $this->error('Command not found');
            $this->help();
            return null;
        }

        return $this->{$function}();
    }

    protected function comment($message)
    {
        print($message . "\n");
    }

    protected function error($message)
    {
        $this->comment($message);
        die();
    }

}