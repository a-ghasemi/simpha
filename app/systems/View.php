<?php
namespace App\System;


use Jenssegers\Blade\Blade;

class View
{
    private $file;
    private $content;
    private $blade;

    public function __construct($file)
    {
        $this->file = $file;
        $this->blade = new Blade('../views','../storage/views');
    }

    public function render(){
        $this->content = $this->blade->render($this->file);
        return $this;
    }

    static function show($file){
        return (new Self($file))->render();
    }

    public function getContent(){
        @ob_start();
        print($this->content);
        @ob_flush();
    }
}