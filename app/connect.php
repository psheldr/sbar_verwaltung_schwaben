<?php
require_once __DIR__ . '/Dotenv.php';

$rechner = php_uname('n');
$dbname = "db1055935-schwaben";

$conn = mysqli_connect($_ENV['DB_HOST'], $_ENV['DB_USER'], $_ENV['DB_PASS'], $dbname, $_ENV['DB_PORT']) or die ("Error connecting to database");

mysqli_query($conn,'SET NAMES utf8');
?>
