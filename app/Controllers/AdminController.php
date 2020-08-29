<?php


namespace App\Controllers;

use App\Models\User;
use Kernel\Controller;

class AdminController extends Controller
{
    protected function get_index()
    {
        return view("admin.index");
    }

    protected function get_test()
    {
        $data = User::find(1);
        return view("admin.test",['data' => $data['username']]);
    }
}