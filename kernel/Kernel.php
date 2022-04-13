<?php

namespace Kernel;

use Kernel\Abstractions\AbsDataStorage;
use Kernel\Abstractions\AbsDbConnection;
use Kernel\Abstractions\IEnvEngine;

class Kernel
{
    protected $env_engine;
    protected $data_storage;
    protected $db_connection;

    static $global_errors;

    public function __construct(AbsDbConnection $dbConnection, IEnvEngine $envEngine, AbsDataStorage $dataStorage)
    {
        $this->env_engine = $envEngine;
        $this->db_connection = $dbConnection;
        $this->data_storage = $dataStorage;

        $this->switchDebugMode();
        $this->getRequestInfo();
        $this->explodeRequestedUrl();
    }


    public function run()
    {
        @session_start();

        $controller = $this->getRequestedController();

        $page = new $controller($this->db_connection, $this->data_storage);
        $page->run()->getContent();

        /*        if (is_object($ret)) {
                    switch (get_class($ret)) {
                        case 'Kernel\View':
                            $ret->getContent();
                            break;
                        case 'Kernel\Redirect':
                            $ret->go();
                            break;
                        default:
                            print("Kernel Error: Class " . get_class($ret) . " is not cased yet.");
                    }
                } elseif (is_string($ret)) {
                    @ob_start();
                    print($ret);
                    @ob_flush();
                } elseif (empty($ret)) {
                    print('Kernel Warning: Content is Empty!');
                }*/

    }

    protected function switchDebugMode(): void
    {
        if ($this->env_engine->get('DEBUG_MODE', false)) {
            ini_set('display_errors', 1);
            ini_set('display_startup_errors', 1);
            error_reporting(E_ALL);
        } else {
            @error_reporting(0);
        }
    }

    protected function getRequestInfo(): void
    {
        $this->data_storage->set('request_type', $_SERVER['REQUEST_METHOD']);
        $this->data_storage->set('get_data', $_GET);
        $this->data_storage->set('post_data', $_POST);
    }

    protected function explodeRequestedUrl(): void
    {
        $url = [];

        #TODO: using HTTP_HOST & REQUEST_URI has security problem, change this as soon as possible
        $request = (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? "https" : "http") . "://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";

        $request = strtolower(trim($request));
        $parsedUrl = parse_url($request)['path'];
        $parsedUrl = explode('/', $parsedUrl);
        array_shift($parsedUrl);// removes http(s)

        $tmp = array_shift($parsedUrl);
        $url['class'] = !empty($tmp) ? $tmp : 'home';

        $tmp = array_shift($parsedUrl);
        $url['method'] = !empty($tmp) ? $tmp : 'index';

        $url['params'] = $parsedUrl ?? [];

        $this->data_storage->set('url', $url);
    }

    protected function getRequestedController($namespace = '\\App\\Controllers\\')
    {
        $url = $this->data_storage->get('url');
        $controller = sprintf('%s%s%s', $namespace, ucwords($url['class']), 'Controller');

        if (!class_exists($controller)) {
            header($_SERVER["SERVER_PROTOCOL"] . " 404 Not Found", true, 404);
            return null;
        }

        return $controller;
    }
}