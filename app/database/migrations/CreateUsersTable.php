<?php


namespace App\database\migrations;


use Kernel\Migration;
use Kernel\Table;

class CreateUsersTable extends Migration
{
    public function up(){
        $this->create_table('users',function(Table $table){
            $table->autoincremental('id', 7);
            $table->string('username', 150);
            $table->string('password', 255);
            $table->timestamps();
        });
    }

    public function down(){
        $this->drop_table('users');
    }
}