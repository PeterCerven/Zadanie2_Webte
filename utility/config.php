<?php
$hostname = "localhost";
$username = "xcervenp";
$password = "BU5tIZLgpHeD0GN";
$dbname = "menu";

date_default_timezone_set('Europe/Berlin');

$db = new PDO("mysql:host=$hostname;dbname=$dbname;charset=utf8", $username, $password);
$db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

return $db;