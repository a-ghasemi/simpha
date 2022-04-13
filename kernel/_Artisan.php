<?php

namespace Kernel;


use Kernel\Abstractions\IDataStorage;
use Kernel\Abstractions\AbsDbConnection;
use Kernel\Abstractions\IEnvEngine;

class _Artisan
{
    const GLOBAL_NAMESPACE = '\\Kernel\\Command\\';
    const USER_NAMESPACE = '\\App\\Command\\';

    public function __construct(AbsDbConnection $dbConnection, IEnvEngine $envEngine, IDataStorage $dataStorage)
    {
        $this->env_engine = $envEngine;
        $this->db_connection = $dbConnection;
        $this->data_storage = $dataStorage;

        $this->getRequestInfo();
    }

    public function run(): void
    {
        $class = $this->getRequestedCommand();

        $command = new $class($this->data_storage);
        $command->run();
    }

    public function showHelpContent(): void
    {
        if ($this->env_engine->get('DEBUG_MODE')) $this->data_storage->dumpData();

        print("=================\n");

        print("System Commands\n");
        $system_classes = ClassMap::map(self::GLOBAL_NAMESPACE, kernel_path('Command'));
        foreach ($system_classes as $class => $methods) {
            foreach ($methods as $method) {
                print("\t" . strtolower($class) . ($method ? ":" . $method : '') . "\n");
            }
            print("\n");
        }

        print("=================\n");
        print("User Commands\n");
        $user_classes = ClassMap::map(self::USER_NAMESPACE, app_path('Command'));
        foreach ($user_classes as $class => $methods) {
            foreach ($methods as $method) {
                print("\t" . strtolower($class) . ($method ? ":" . $method : '') . "\n");
            }
            print("\n");
        }
    }

    protected function getRequestInfo()
    {
        $args = $_SERVER['argv'];

        //removes "artisan" from the args array
        array_shift($args);

        if (count($args) == 0) {
            $this->showHelpContent();
            return;
        }

        $command = explode(':', trim(array_shift($args)));

        $this->data_storage->set('command', str_replace('-', '', array_shift($command)));
        $this->data_storage->set('subcommands', count($command) ? $command : ['index']);
        $this->data_storage->set('parameters', count($args) ? explode(' ', trim(array_shift($args))) : null);
    }

    protected function getRequestedCommand()
    {
        $command = $this->data_storage->get('command', null);

        $class = self::GLOBAL_NAMESPACE . ucwords($command);

        if (!class_exists($class)) {
            $class = self::USER_NAMESPACE . ucwords($command);
        }

        if (!class_exists($class)) {
            $this->showHelpContent();
            return null;
        }

    }
}