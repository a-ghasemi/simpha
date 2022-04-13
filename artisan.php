<?php

include("vendor/autoload.php");

use \Kernel\_Artisan;

$envEngine = new \Kernel\EnvEngineEngine();
$dataStorage = new \Kernel\ArrayDataStorage();
$errorHandler = new \Kernel\ErrorHandler();
$dbConnection = new \Kernel\MysqlDbConnection($envEngine, $errorHandler);

$artisan = new _Artisan($dbConnection, $envEngine, $dataStorage);
$artisan->run();
