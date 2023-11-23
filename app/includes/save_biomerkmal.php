<?php

require_once '../model/speise.php';
require_once '../model/speise_verwaltung.php';



include '../connect_pdo.php';

$speiseVerwaltung = new SpeiseVerwaltung($db);




$speise_id = $_REQUEST['speise_id'];
$set_bio = $_REQUEST['set_bio'];

var_dump($bio);
$speise = $speiseVerwaltung->findeAnhandVonId($speise_id);
$speise->setBio($set_bio);
$speiseVerwaltung->speichere($speise);
var_dump($speise);
//
//var_dump($kid, $tag, $monat, $jahr, $speise_nr);

?>