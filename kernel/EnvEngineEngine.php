<?php

namespace Kernel;


use Kernel\Abstractions\IEnvEngine;
use function PHPUnit\Framework\stringEndsWith;

class EnvEngineEngine implements IEnvEngine
{
    protected $env_address;
    protected $env_data;

    public function __construct()
    {
        $this->env_address = base_path(".env");
        $this->parse();
    }

    protected function parse():void
    {
        $content = file_get_contents($this->env_address);

        $content = explode("\n", $content);

        foreach ($content as $line) {
            if (empty($line)) continue;
            $data = explode("=", $line);
            if (count($data) < 1) continue;

            $tmp = trim($data[1]) ?? null;
            $this->env_data[trim($data[0])] =
                $this->convertStrBooleansToBoolean($tmp);
        }
    }

    public function get(string $key, $default = false){
        return $this->env_data[$key] ?? $default;
    }

    // "true" have been change to true:bool
    // "false" have been change to false:bool
    // everything else passes without change
    protected function convertStrBooleansToBoolean(string $input){
        switch ($input){
            case 'true': return true; break;
            case 'false': return false; break;
            default: return $input;
        }
    }
}