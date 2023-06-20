<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors,', 1);
error_reporting(E_ALL);

$db = require_once "../utility/config.php";
$Menus = require_once "../utility/urls.php";

function downloadHtml($url): bool|string
{
    $ch = curl_init($url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    $returnData = curl_exec($ch);
    curl_close($ch);
    return $returnData;
}

foreach ($Menus as $key => $value) {
    $html = downloadHtml($value);
    $stmt = $db->prepare("INSERT INTO data (name, html) VALUES (?,?)");
    $stmt->execute([$key, $html]);
}
session_start();

$msg = "Data successfully downloaded.";
$_SESSION['msg'] = $msg;
header("Location: ../index.php");

require_once '../header.php';
require_once '../footer.php';









