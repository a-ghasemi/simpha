<?php


namespace App\Database\Seeds;


use App\Models\User;
use Kernel\Abstractions\AbsSeed;

class Users extends AbsSeed
{
    public function run(){
        User::create([
            'username' => 'admin',
            'password' => hash('sha1','123456'),
        ]);
    }
}