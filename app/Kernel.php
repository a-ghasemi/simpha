<?php


namespace App;


class Kernel
{
    private $url;
    private $data;

    public function __construct()
    {
        $this->data["request_type"] = $_SERVER['REQUEST_METHOD'];

        $this->data["get_data"] = $_GET;
        $this->data["post_data"] = $_POST;

        //TODO: using HTTP_HOST & REQUEST_URI has security problem, change this as soon as possible
        $url = (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? "https" : "http") . "://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";
        $url = explode('/',parse_url(strtolower(trim($url)))['path']);
        array_shift($url);

        $this->url['class'] = array_shift($url) ?? 'home';
        $this->url['method'] = array_shift($url) ?? 'index';
        $this->url['params'] = $url ?? [];

        $this->data["url"] = $this->url;

    }

    public function run(){
        $controller = "\\App\\Controllers\\".ucwords($this->url['class']).'Controller';

        if(!class_exists($controller)){
            header($_SERVER["SERVER_PROTOCOL"]." 404 Not Found", true, 404);
            return;
        }

        $page = new $controller($this->data);
        $page->run();
    }
}