<?php


namespace App\database\seeds;


use Kernel\Seed;

class Users extends Seed
{
    public function run(){
        $this->database->insert('users',[
            'title' => 'Plus500 | Arabic',
            'base_url' => 'https://www.plus500.com/ar/?id=',
            'flag_field_name' => 'tags',
            'our_affiliate_id' => '126753',
            'ext_params' => 'pl=2',
        ]);
    }
}