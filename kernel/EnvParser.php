<?php

namespace Kernel;


class EnvParser
{
    private $env_address;
    private $env_data;

    public function __construct($env_path = ".env")
    {
        $this->env_address = $env_path;
    }

    public function parse(){
        $content = file_get_contents($this->env_address);
        $content = explode("\n", $content);
        foreach($content as $line){
            if(empty($line))continue;
            $data = explode("=",$line);
            $this->env_data[trim($data[0])] = trim($data[1]);
        }

        return $this->env_data;
    }
}