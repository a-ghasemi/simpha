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
        $data = User::instance()->where(['username' => '123'])->first();
//        $data->username = '123';
//        $data->save();
        dd($data);
        return view("admin.test",['data' => $data->password]);
    }
}