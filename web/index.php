<?php
include '../config/config.php';
include '../vendor/autoload.php';

use vendor\Core\App\LiteApplication;

$app = new LiteApplication();

$app->run();
