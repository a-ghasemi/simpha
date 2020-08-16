<?php

namespace App\Command;


class Demo extends \Kernel\Command
{
    public function index(){
        $this->comment('This is a demo');
    }
}