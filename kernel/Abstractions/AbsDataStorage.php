<?php

namespace Kernel\Abstractions;

abstract class AbsDataStorage
{
    protected $data;

    abstract public function get(string $key);
    abstract public function set(string $key, $value);
}