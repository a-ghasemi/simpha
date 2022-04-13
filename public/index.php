<?php

include("../vendor/autoload.php");

use \Kernel\Kernel;

$envEngine = new \Kernel\EnvEngineEngine();
$dataStorage = new \Kernel\DataStorage();
$errorHandler = new \Kernel\ErrorHandler();
$dbConnection = new \Kernel\MysqlDbConnection($envEngine, $errorHandler);

$app = new Kernel($dbConnection, $envEngine, $dataStorage);
$app->run();
