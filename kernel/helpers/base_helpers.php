<?php

function env_get($key, $default = null)
{
    return \Kernel\Kernel::$env[$key] ?? $default;
}

function view($blade_path)
{
    return \Kernel\View::show($blade_path);
}

function redirect($target, $status_code = 200)
{
    return \Kernel\Redirect::to($target, $status_code);
}

function base_dir($dir = '')
{
    return __DIR__ . '/../../' . $dir . '/';
}