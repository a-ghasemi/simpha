<?php


namespace App\Controllers;

use Kernel\Abstractions\AbsController;

class HomeController extends AbsController
{
    protected function get_index(){
        return view("home.index");
    }
}