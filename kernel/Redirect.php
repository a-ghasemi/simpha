<?php
namespace Kernel;


class Redirect
{
    private $target;

    public function __construct($target)
    {
        $this->target = $target;
    }

    public function go(){
        header("Location: {$this->target}");
        return $this;
    }

    static function to($target){
        return (new Self($target));
    }
}