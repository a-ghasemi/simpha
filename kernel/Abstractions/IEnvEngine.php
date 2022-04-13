<?php

namespace Kernel\Abstractions;

interface IEnvEngine
{
    public function get(string $key, $default = false);

}