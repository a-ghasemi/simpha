<?php


namespace Kernel\Abstractions;

use Kernel\Abstractions\IDataStorage;
use Kernel\Abstractions\AbsDbConnection;

abstract class AbsController
{
    protected AbsDbConnection $db_connection;
    protected IDataStorage $data_storage;

    public function __construct(AbsDbConnection $dbConnection, IDataStorage $dataStorage)
    {
        $this->db_connection = $dbConnection;
        $this->data_storage = $dataStorage;
    }

    final public function run()
    {
        $function = $this->getRequestedMethod();
        return $this->{$function}();
    }

    protected function getRequestedMethod()
    {
        $function = null;

        $level = 0;
        do {
            switch ($level) {
                case 0:
                    $function = sprintf("%s_%s", strtolower($this->data_storage->get('request_type')), $this->data_storage->get('url')['method']);
                    break;
                case 1:
                    $function = sprintf('any_%s', $this->data_storage->get('url')['method']);
                    break;
                case 2:
                    $function = sprintf('%s_', $this->data_storage->get('url')['request_type']);
                    break;
                case 3:
                    $function = 'any_';
                    break;

                default:
                    $function = null;
                    header($_SERVER["SERVER_PROTOCOL"] . " 404 Not Found", true, 404);
            }
            $level++;
        } while (!method_exists($this, $function));

        return $function;
    }
}