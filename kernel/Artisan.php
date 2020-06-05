<?php

namespace Kernel;


class Artisan
{
    private $command;
    private $subcommands;
    private $parameters;

    private $debug;

    public function __construct($debug = false)
    {
        $this->debug = $debug;

        $args = $_SERVER['argv'];
        array_shift($args); //removes "artisan" from the args array

        if (count($args) == 0) {
            $this->help();
            return;
        }

        $command = explode(':', trim(array_shift($args)));
        $this->command = str_replace('-','', array_shift($command));
        if (count($command) > 0) $this->subcommands = $command;

        if (count($args) > 0) $this->parameters = explode(' ', trim(array_shift($args)));
    }

    public function run(): void
    {
        $class = "\\Kernel\\Command\\" . ucwords($this->command);

        if (!class_exists($class)) {
            $class = "\\App\\Command\\" . ucwords($this->command);
        }

        if (!class_exists($class)) {
            $this->help();
            return;
        }

        $command = new $class($this->subcommands, $this->parameters);
        $command->run();
    }

    public function help(): void
    {
        if($this->debug) dump($this->command,$this->subcommands,$this->parameters);
        print("=================\n");

        $system_classes = ClassMap::map('Kernel\\Command',kernel_path('commands'));
        $user_classes = ClassMap::map('App\\Command',app_path('commands'));

        print("System Commands\n");
        foreach($system_classes as $class=>$methods){
            foreach($methods as $method){
                print("\t".$class.($method?":".$method:'')."\n");
            }
            print("\n");
        }

        print("=================\n");
        print("User Commands\n");
        foreach($user_classes as $class=>$methods){
            foreach($methods as $method){
                print("\t".$class.($method?":".$method:'')."\n");
            }
            print("\n");
        }


        return;
    }
}