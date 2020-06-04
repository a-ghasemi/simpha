<?php


namespace Kernel;

class Controller
{
    protected $data;

    public function __construct($data = null)
    {
        $this->data = $data;
    }

    public function run(){
        $function = $this->data['request_type'] . '_' . $this->data['url']['method'];

        if(!method_exists($this,$function)){
            $function = 'any_' . $this->data['url']['method'];
        }

        if(!method_exists($this,$function)){
            header($_SERVER["SERVER_PROTOCOL"]." 404 Not Found", true, 404);
            return;
        }

        return $this->{$function}();
    }
}