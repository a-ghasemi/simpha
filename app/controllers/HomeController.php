<?php


namespace App\Controllers;


use App\View;

class HomeController extends Controller
{
    protected function get_index(){
        return View::show("home.index");
    }
}