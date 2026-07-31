<?php

define('__ROOT__', realpath(__DIR__ . '/..'));

require __ROOT__ . '/vendor/autoload.php';

use App\App;

$app = new App();
$app->server();
