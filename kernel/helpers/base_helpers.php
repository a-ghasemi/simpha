<?php

function view($blade_path, $data = [])
{
    return \Kernel\View::show($blade_path, $data);
}

function redirect($target, $status_code = 200)
{
    return \Kernel\Redirect::to($target, $status_code);
}

function base_path($dir = '')
{
    return realpath(__DIR__ . implode('..', [DIRECTORY_SEPARATOR, DIRECTORY_SEPARATOR,
                DIRECTORY_SEPARATOR])) . DIRECTORY_SEPARATOR . $dir;
}

function storage_path($dir = '')
{
    return base_path('storage') . DIRECTORY_SEPARATOR . $dir;
}

function kernel_path($dir = '')
{
    return base_path('kernel') . DIRECTORY_SEPARATOR . $dir;
}

function app_path($dir = '')
{
    return base_path('app') . DIRECTORY_SEPARATOR . $dir;
}

if (!function_exists('dump')) {
    function dump()
    {
        foreach (func_get_args() as $arg) var_dump($arg);
    }
}
if (!function_exists('stack')) {
    function stack()
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
