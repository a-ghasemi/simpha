<?php


namespace App\database\migrations;


use Kernel\Migration;

class CreateUsersTable extends Migration
{
    public function up(){
        $this->create_table('users',[
            $this->autoincremental('id', 7),
            $this->string('username', 150),
            $this->string('password', 255),
            $this->timestamps(),
        ]);
    }

    public function down(){
        $this->drop_table('users');
    }
}