<?php


namespace App\Controllers;

use App\System\Controller;
use App\System\View;

class HomeController extends Controller
{
    protected function get_index(){
        return View::show("home.index");
    }
}