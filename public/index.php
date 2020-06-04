<?php

include("../vendor/autoload.php");

use \App\System\Kernel;

$app = new Kernel(true);
$app->run();
