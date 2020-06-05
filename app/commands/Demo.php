<?php

namespace App\Command;


class Demo extends \Kernel\Command
{
    protected function index(){
        $this->comment('This is a demo');
    }
}