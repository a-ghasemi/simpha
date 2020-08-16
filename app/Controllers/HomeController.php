<?php


namespace App\Controllers;

use Kernel\Controller;

class HomeController extends Controller
{
    protected function get_index(){
        return view("home.index");
    }
}