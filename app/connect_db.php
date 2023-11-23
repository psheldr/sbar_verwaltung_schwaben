<?php
require_once 'Dotenv.php';
$db_name = 'kitafino_' . $projekt_id;

$conn = new PDO("mysql:host=".$_ENV['KITAFINO_DB_HOST']. $_ENV['KITAFINO_DB_NAME'], $_ENV['KITAFINO_DB_USER'], $_ENV['KITAFINO_DB_PASSWORD'], $_ENV['KITAFINO_DB_PORT']);
$conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);



$stmt = $conn->prepare("SELECT * FROM bestellungen where tag = $tag_sel AND monat = $monat_sel AND jahr = $jahr_sel");
                            var_dump("SELECT FROM bestellungen where tag = $tag_sel AND monat = $monat_sel AND jahr = $jahr_sel");
$stmt->execute();



while ($row = $stmt->fetch()) {
    var_dump($row);
   /* $projekt_ids_arr[] = $row['projekt_id'];
    $kunden_ids_arr[] = $row['id'];*/
}
?>