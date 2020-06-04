<?php


namespace App\Controllers;

use Kernel\Controller;
use Kernel\Redirect;
use Kernel\View;

class AdminController extends Controller
{
    protected function get_index(){
        return Redirect::to("/home");
        return View::show("admin.index");
    }
}