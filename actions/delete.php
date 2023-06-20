<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors,', 1);
error_reporting(E_ALL);


$db = require '../utility/config.php';

$stmt = $db->prepare("DELETE FROM data");
$result = $stmt->execute();

if(!$result) {
    $msg = "Something went wrong.";
    $_SESSION['error'] = $msg;
    header("Location: ../index.php");
}

$stmt = $db->prepare("DELETE FROM restaurants");
$result = $stmt->execute();

if(!$result) {
    $msg = "Something went wrong.";
    $_SESSION['error'] = $msg;
    header("Location: ../index.php");
}
session_start();

$msg = "Data successfully deleted.";
$_SESSION['msg'] = $msg;
header("Location: ../index.php");

require_once '../header.php';

require_once '../footer.php';
