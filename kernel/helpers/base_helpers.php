<?php

function env_get($key, $default = null){
    return \Kernel\Kernel::$env[$key] ?? $default;
}