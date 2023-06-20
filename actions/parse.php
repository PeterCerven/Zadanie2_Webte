<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors,', 1);
error_reporting(E_ALL);


$db = require_once '../utility/config.php';
$Menus = require_once '../utility/urls.php';

require_once '../parsing/parseFunctions.php';


foreach ($Menus as $key => $value) {
    $query = <<<SQL
        SELECT * FROM data d
        WHERE d.html IS NOT NULL AND d.name = ?
        ORDER BY d.date DESC
        LIMIT 1;
        SQL;

    $stmt = $db->prepare($query);
    $stmt->execute([$key]);
    $lastDate = $stmt->fetch(PDO::FETCH_ASSOC);

    switch ($key) {
        case 'eat':
            eat($lastDate['html'],$key, $db);
            break;
        case 'fiitFood':
            fiitFood($lastDate['html'],$key, $db);
            break;
        case 'venza':
            venza($lastDate['html'],$key, $db);
            break;
    }
}

require_once '../header.php';
require_once '../footer.php';

session_start();

$msg = "Data successfully parsed.";
$_SESSION['msg'] = $msg;
header("Location: ../index.php");




