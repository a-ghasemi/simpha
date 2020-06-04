<?php


namespace App\Controllers;

use App\System\Controller;
use App\System\View;

class AdminController extends Controller
{
    protected function get_index(){
        return View::show("admin.index");
    }
}