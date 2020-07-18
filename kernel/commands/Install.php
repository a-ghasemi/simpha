<?php

namespace Kernel\Command;


use Kernel\Command;

class Install extends Command
{
    public function index(){
        $this->comment('Composer install');
        shell_exec('composer install');
        $this->comment('Composer dump-autoload');
        shell_exec('composer dump-autoload -o');
        $this->comment('Creates .env file');
        shell_exec('cp .env.example .env');
        $this->comment('enjoy!');
    }
}