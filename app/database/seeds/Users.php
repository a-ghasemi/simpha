<?php


namespace App\database\seeds;


use Kernel\Seed;

class Users extends Seed
{
    public function run(){
        $this->database->insert('users',[
            'username' => 'admin',
            'password' => hash('sha1','123456'),
        ]);
    }
}