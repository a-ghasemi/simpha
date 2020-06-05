<?php

namespace Kernel;


abstract class Command
{
    protected $subcommands;
    protected $parameters;

    public function __construct($subcommands, $parameters = null)
    {
        $this->subcommands = $subcommands ?? ['index'];
        $this->parameters = $parameters;
    }

    final public function run()
    {
        $function = implode('_',$this->subcommands);

        if(!method_exists($this,$function)){
            $this->error('Command not found');
            $this->help();
            return;
        }

        return $this->{$function}();
    }

    protected function comment($message)
    {
        print($message."\n");
    }

    protected function error($message)
    {
        print($message."\n");
        die();
    }

}