<?php

include("../vendor/autoload.php");

use \Kernel\_Kernel;

$envEngine = new \Kernel\EnvEngineEngine();
$dataStorage = new \Kernel\ArrayDataStorage();
$errorHandler = new \Kernel\ErrorHandler();
$dbConnection = new \Kernel\MysqlDbConnection($envEngine, $errorHandler);

$app = new _Kernel($dbConnection, $envEngine, $dataStorage);
$app->run();
