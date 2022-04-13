<?php


namespace App\database\migrations;


use Kernel\Abstractions\AbsMigration;

class CreateUsersTable extends AbsMigration
{
    public function up(){
        $this->create_table('users',[
            $this->autoincremental('id', 7),
            $this->string('username', 150),
            $this->string('password'),
            $this->timestamps(),
        ]);
    }

    public function down(){
        $this->drop_table('users');
    }
}