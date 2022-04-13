<?php

namespace Kernel;

use Kernel\Abstractions\IDataStorage;

class ArrayDataStorage implements IDataStorage
{
    protected array $data;

    public function __construct()
    {
        $this->data = [];
    }

    public function get(string $key, $default = null){
        if(!array_key_exists($key, $this->data)) return $default;
        return $this->data[$key];
    }

    public function set(string $key, $value){
        $this->data[$key] = $value;
    }

    public function dumpData(){
        dump($this->data);
    }
}