<?php

require 'bootstrap.php';

use App\App;

$app = new App();
$app->fresh();
$app->seeder();