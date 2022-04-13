<?php


namespace App\Controllers;

use Kernel\Abstractions\AbsController;

class AdminController extends AbsController
{
    protected function get_index()
    {
        return view("admin.index");
    }
}