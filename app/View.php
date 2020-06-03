<?php
namespace App;


use Jenssegers\Blade\Blade;

class View
{
    private $file;
    private $blade;

    public function __construct($file)
    {
        $this->file = $file;
        $this->blade = new Blade('../views','../storage/views');
    }

    public function render(){
        return $this->blade->render($this->file);
    }

    static function show($file){
        return (new Self($file))->render();
    }
}