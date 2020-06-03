<?php
namespace App;


class View
{
    private $file;

    public function __construct($file)
    {
        $file = implode("/",explode('.',$file));
        $this->file = "../views/$file.html";
    }

    public function render(){
        return file_get_contents($this->file);
    }

    static function show($file){
        return (new Self($file))->render();
    }
}