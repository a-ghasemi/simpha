<?php

namespace App\Command;


class Demo extends \Kernel\Abstractions\AbsCommand
{
    public function index(){
        $this->comment('This is a demo');
    }
}