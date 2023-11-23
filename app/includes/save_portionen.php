<?php

require_once '../model/portionenaenderung.php';
require_once '../model/portionenaenderung_verwaltung.php';
require_once '../model/abrechnungstag.php';
require_once '../model/abrechnungstag_verwaltung.php';
require_once '../model/standardportionen.php';
require_once '../model/standardportionen_verwaltung.php';



include '../connect_pdo.php';

$portionenaenderungVerwaltung = new PortionenaenderungVerwaltung($db);
$abrechnungstagVerwaltung = new AbrechnungstagVerwaltung($db);
$standardportionenVerwaltung = new StandardportionenVerwaltung($db);



$kid = $_REQUEST['kunde_id'];
$starttag = $_REQUEST['starttag'];
$startmonat = $_REQUEST['startmonat'];
$startjahr = $_REQUEST['startjahr'];
$starttagts = $_REQUEST['startts'];
$speise_nr = $_REQUEST['speise_nr'];
$tag_str = $_REQUEST['tag_str'];
$portionen = $_REQUEST['portionen'];
$tag_ts = $_REQUEST['tag_ts'];
$tag = date('d', $tag_ts);
$monat = date('m', $tag_ts);
$jahr = date('Y', $tag_ts);
$portionenaenderung = $portionenaenderungVerwaltung->findeAnhandVonKundenIdUndWochenstartdatum($kid, $starttag, $startmonat, $startjahr, $speise_nr);
$abrechnungstag = $abrechnungstagVerwaltung->findeAnhandVonKundeIdUndTagMonatJahr($kid, $tag, $monat, $jahr, $speise_nr);

if (!$portionenaenderung->getId()) {
    $portionenaenderung->setKundeId($kid);
    $portionenaenderung->setWochenstarttagTs($starttagts);
    $portionenaenderung->setStarttag($starttag);
    $portionenaenderung->setStartmonat($startmonat);
    $portionenaenderung->setStartjahr($startjahr);
    $portionenaenderung->setSpeiseNr($speise_nr);

    $standardportionen = $standardportionenVerwaltung->findeAnhandVonKundenIdUndSpeiseNr($kid, $speise_nr);
    $portionenaenderung->setPortionenMo($standardportionen->getPortionenMo());
    $portionenaenderung->setPortionenDi($standardportionen->getPortionenDi());
    $portionenaenderung->setPortionenMi($standardportionen->getPortionenMi());
    $portionenaenderung->setPortionenDo($standardportionen->getPortionenDo());
    $portionenaenderung->setPortionenFr($standardportionen->getPortionenFr());
}
switch ($tag_str) {
    case 'mo':
        $portionenaenderung->setPortionenMo($portionen);
        break;
    case 'di':
        $portionenaenderung->setPortionenDi($portionen);
        break;
    case 'mi':
        $portionenaenderung->setPortionenMi($portionen);
        break;
    case 'do':
        $portionenaenderung->setPortionenDo($portionen);
        break;
    case 'fr':
        $portionenaenderung->setPortionenFr($portionen);
        break;
}
$abrechnungstag->setPortionen($portionen);
$abrechnungstagVerwaltung->speichere($abrechnungstag);
$portionenaenderungVerwaltung->speichere($portionenaenderung);

?>