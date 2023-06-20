<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors,', 1);
error_reporting(E_ALL);

header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: PUT');
header('Access-Control-Allow-Methods: POST');
header('Access-Control-Allow-Methods: GET');

$db = require_once '../utility/config.php';
require_once('../models/MealController.php');


$controller = new MealController($db);
switch ($_SERVER['REQUEST_METHOD']) {
    case 'GET':
        if (isset($_GET['day']))
            $controller->readMenuByDay($db, $_GET['day']);
        else
            $controller->readMenu($db);
        break;
    case 'POST':
        $data = json_decode(file_get_contents('php://input'), true);
        $controller->createMealForTheWeek($db, $data);
        break;
    case 'PUT':
        $meal_id = $_GET['id'];
        $meal_data = json_decode(file_get_contents('php://input'), true);
        $controller->updateMealPrice($db, $meal_id, $meal_data);
        break;
    case 'DELETE':
        $controller->deleteRestaurant($db, $_GET['id']);
        break;
}



function isEmpty($param): bool
{

    if (empty($param)) {
        $isOk = false;
    } else {
        $isOk = true;
    }

    return $isOk;
}
