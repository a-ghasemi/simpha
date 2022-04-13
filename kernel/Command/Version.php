<?php

namespace Kernel\Command;


use Kernel\Abstractions\AbsCommand;

class Version extends AbsCommand
{
    public function index(){
        $this->comment('Simpha version 0.9');
    }
}