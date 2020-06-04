<?php

function env_get($key, $default = null)
{
    return \Kernel\Kernel::$env[$key] ?? $default;
}

function view($blade_path)
{
    \Kernel\View::show($blade_path);
}

function redirect($target, $status_code = 200)
{
    \Kernel\Redirect::to($target, $status_code);
}