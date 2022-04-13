<?php

namespace Kernel;

use Kernel\Abstractions\AbsDataStorage;

class DataStorage extends AbsDataStorage
{
    protected $data;

    public function get(string $key){
        if(!array_key_exists($key,$this->data)) throw new \Exception('Data key not exists');
        return $this->data[$key];
    }

    public function set(string $key, $value){
        $this->data[$key] = $value;
    }

}