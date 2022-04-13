<?php

namespace Kernel\Abstractions;

abstract class AbsCommand
{
    protected $data_storage;

    public function __construct(IDataStorage $dataStorage)
    {
        $this->data_storage = $dataStorage;
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