<?php

namespace Kernel\Command;


use Kernel\Command;

class Version extends Command
{
    public function index(){
        $this->comment('Simpha version 0.9');
    }
}