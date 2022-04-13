<?php


namespace App\Models;

use Kernel\Abstractions\AbsModel;

class User extends AbsModel
{
    protected $fillable = ['username', 'password'];
}