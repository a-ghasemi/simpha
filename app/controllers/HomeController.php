<?php


namespace App\Controllers;

use Kernel\Controller;
use Kernel\View;

class HomeController extends Controller
{
    protected function get_index(){
        return View::show("home.index");
    }
}