<?php

namespace Kernel;

class Kernel
{
    private $url;
    private $data;

    static $env;

    public function __construct($debug_mode = false)
    {
        Self::$env = (new EnvParser("../.env"))->parse();

        $this->data["request_type"] = $_SERVER['REQUEST_METHOD'];

        $this->data["get_data"] = $_GET;
        $this->data["post_data"] = $_POST;

        //TODO: using HTTP_HOST & REQUEST_URI has security problem, change this as soon as possible
        $url = (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? "https" : "http") . "://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";
        $url = explode('/',parse_url(strtolower(trim($url)))['path']);
        array_shift($url);

        $tmp = array_shift($url);
        $this->url['class'] = (!empty($tmp))? $tmp : 'home';
        $tmp = array_shift($url);
        $this->url['method'] = (!empty($tmp))? $tmp : 'index';
        $this->url['params'] = $url ?? [];

        $this->data["url"] = $this->url;

        $this->data['debug_mode'] = $debug_mode;
    }

    public function run(){
        $controller = "\\App\\Controllers\\".ucwords($this->url['class']).'Controller';

        if(!class_exists($controller)){
            header($_SERVER["SERVER_PROTOCOL"]." 404 Not Found", true, 404);
            return;
        }

        $page = new $controller($this->data);
        $ret = $page->run();

        if(is_object($ret)){
            switch(get_class($ret)){
                case 'Kernel\View':
                    $ret->getContent();
                    break;
                case 'Kernel\Redirect':
                    header("Location:$ret");
                    break;
                default:
                    echo "Class ".get_class($ret)." is not cased yet.";
            }
        }
        elseif(is_string($ret)){
            @ob_start();
            print($ret);
            @ob_flush();
        }

    }
}