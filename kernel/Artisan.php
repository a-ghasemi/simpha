<?php

namespace Kernel;


class Artisan
{
    private $command;
    private $subcommands;
    private $parameters;
    static $env;

    public function __construct()
    {
        self::$env = (new EnvEngineEngine(base_path(".env")))->parse();

        if(env_get('DEBUG_MODE',false)) {
            ini_set('display_errors',1);
            ini_set('display_startup_errors',1);
            error_reporting(E_ALL);
        }
        else {
//            ini_set('display_errors',0);
//            ini_set('display_startup_errors',0);
            @error_reporting(0);
        }

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

        $system_classes = ClassMap::map('Kernel\\Command',kernel_path('Command'));
        $user_classes = ClassMap::map('App\\Command',app_path('Command'));

        print("System Commands\n");
        foreach($system_classes as $class=>$methods){
            foreach($methods as $method){
                print("\t".strtolower($class).($method?":".$method:'')."\n");
            }
            print("\n");
        }

        print("=================\n");
        print("User Commands\n");
        foreach($user_classes as $class=>$methods){
            foreach($methods as $method){
                print("\t".strtolower($class).($method?":".$method:'')."\n");
            }
            print("\n");
        }


        return;
    }
}