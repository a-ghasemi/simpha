<?php


namespace App\database\seeds;


use App\Models\User;
use Kernel\Seed;

class Users extends Seed
{
    public function run(){
        User::create([
            'username' => 'admin',
            'password' => hash('sha1','123456'),
        ]);
    }
}