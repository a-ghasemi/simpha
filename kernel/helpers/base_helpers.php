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
    return realpath(__DIR__ . '/../../') . '/' . $dir;
}

function storage_dir($dir = '')
{
    return base_dir('storage') . '/' . $dir;
}

if (!function_exists('dump')) {
    function dump()
    {
        foreach (func_get_args() as $arg) var_dump($arg);
    }
}
if (!function_exists('dd')) {
    function dd()
    {
        foreach (func_get_args() as $arg) var_dump($arg);
        die();
    }
}
