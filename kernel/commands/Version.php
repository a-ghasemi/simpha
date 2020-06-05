<?php

namespace Kernel\Command;


use Kernel\Command;

class Version extends Command
{
    protected function index(){
        $this->comment('v 0.9');
    }
}