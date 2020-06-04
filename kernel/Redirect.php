<?php

namespace Kernel;


class Redirect
{
    private $target;
    private $status_code;

    public function __construct($target, $status_code = 200)
    {
        $this->target = $target;
        $this->status_code = $status_code;
    }

    public function go()
    {
        header("Location: {$this->target}", true, $this->status_code);
        return $this;
    }

    static function to($target, $status_code)
    {
        return (new Self($target, $status_code));
    }
}