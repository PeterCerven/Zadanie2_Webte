<?php

require_once '../vendor/autoload.php';

$openapi = \OpenApi\Generator::scan([$_SERVER['DOCUMENT_ROOT'] . '/Zadanie2_MyMenu/models']);
header('Content-Type: application/json');
echo $openapi->toJSON();
