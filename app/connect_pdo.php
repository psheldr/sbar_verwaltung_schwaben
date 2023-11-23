<?php
require_once 'Dotenv.php';

$rechner = php_uname('n');
$optionen = array(
    PDO::ATTR_PERSISTENT => true,
    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION
);

    $db = new PDO("mysql:host=".$_ENV['DB_HOST'].";dbname=".$_ENV['DB_NAME'].";", $_ENV['DB_USER'], $_ENV['DB_PASS'], $optionen);

?>