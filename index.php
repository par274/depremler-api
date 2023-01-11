<?php

ini_set('max_execution_time', 900);

//Application location.
define('APPLICATION_SELF', __DIR__ . '/src');

require(APPLICATION_SELF . '/vendor/autoload.php');
$app = new \Init\app();

$app->RunPlatform();
$app->RenderPlatformDirect();