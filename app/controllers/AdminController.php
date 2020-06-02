<?php


namespace App\Controllers;


use App\View;

class AdminController extends Controller
{
    protected function get_index(){
        return View::show("admin.index");
    }
}