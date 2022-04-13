<?php

namespace Kernel\Command;


use Kernel\AbsCommand;

class Version extends AbsCommand
{
    public function index(){
        $this->comment('Simpha version 0.9');
    }
}