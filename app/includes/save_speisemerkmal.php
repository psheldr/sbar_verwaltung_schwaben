<?php

require_once '../model/speise.php';
require_once '../model/speise_verwaltung.php';



include '../connect_pdo.php';

$speiseVerwaltung = new SpeiseVerwaltung($db);



$speise_id = $_REQUEST['speise_id'];
$set_bio = $_REQUEST['set_bio'];
$set_cooled = $_REQUEST['set_cooled'];
$set_nonprint = $_REQUEST['set_nonprint'];

$speise = $speiseVerwaltung->findeAnhandVonId($speise_id);

if (isset($_REQUEST['set_bio'])) {
    $speise->setBio($set_bio);
}
if (isset($_REQUEST['set_cooled'])) {
    $speise->setCooled($set_cooled);
}
if (isset($_REQUEST['set_nonprint'])) {
    $speise->setNonprint($set_nonprint);
}
$speiseVerwaltung->speichere($speise);
var_dump($speise);
//
//var_dump($kid, $tag, $monat, $jahr, $speise_nr);
?>