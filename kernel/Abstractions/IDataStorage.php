<?php

namespace Kernel\Abstractions;

interface IDataStorage
{
   public function get(string $key, $default = null);
    public function set(string $key, $value);

    public function dumpData();
}