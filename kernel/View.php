<?php

namespace Kernel;

use Jenssegers\Blade\Blade;

class View
{
    private $file;
    private $data;
    private $content;
    private $blade;

    public function __construct(string $file, array $data = [])
    {
        $this->file = $file;
        $this->data = $data;
        $this->blade = new Blade('../app/views', '../storage/views');
    }

    public function render()
    {
        $this->content = $this->blade->render($this->file, $this->data);
        return $this;
    }

    static function show(string $file, array $data = [])
    {
        return (new self($file, $data))->render();
    }

    public function getContent()
    {
        @ob_start();
        print($this->content);
        @ob_flush();
    }
}