<?php

if ($_REQUEST['kunde_id']) {
    $kuid = $_REQUEST['kunde_id'];
    $start_tag_woche = $_REQUEST['starttag'];
    $start_monat_woche = $_REQUEST['startmonat'];
    $start_jahr_woche = $_REQUEST['startjahr'];
    $starttagts = $_REQUEST['startts'];
    $speise_nr = $_REQUEST['speise_nr'];
    $tag_str = $_REQUEST['tag_str'];
    $portionen = $_REQUEST['portionen'];
    $tage_strs = array('mo', 'di', 'mi', 'do', 'fr');
    require_once '../model/portionenaenderung.php';
    require_once '../model/portionenaenderung_verwaltung.php';
    require_once '../model/standardportionen.php';
    require_once '../model/standardportionen_verwaltung.php';

    include '../connect_pdo.php';

    $portionenaenderungVerwaltung = new PortionenaenderungVerwaltung($db);
    $standardportionenVerwaltung = new StandardportionenVerwaltung($db);
}
$kundentyp2 = $_REQUEST['kundentyp'];
$portionenaenderung = $portionenaenderungVerwaltung->findeAnhandVonKundenIdUndWochenstartdatum($kuid, $start_tag_woche, $start_monat_woche, $start_jahr_woche, $speise_nr);
$standardportionen = $standardportionenVerwaltung->findeAnhandVonKundenIdUndSpeiseNr($kuid, $speise_nr);

if (!$portionenaenderung->getId()) {
    $portionenaenderung->setPortionenMo($standardportionen->getPortionenMo());
    $portionenaenderung->setPortionenDi($standardportionen->getPortionenDi());
    $portionenaenderung->setPortionenMi($standardportionen->getPortionenMi());
    $portionenaenderung->setPortionenDo($standardportionen->getPortionenDo());
    $portionenaenderung->setPortionenFr($standardportionen->getPortionenFr());
}
if (!$tag_str) {
    $tag_str = $tage_strs[$count_tag - 1];
}
switch ($tag_str) {
    case 'mo':
        $portionen_db = $portionenaenderung->getPortionenMo();
        $standard_portionen = $standardportionen->getPortionenMo();
        break;
    case 'di':
        $portionen_db = $portionenaenderung->getPortionenDi();
        $standard_portionen = $standardportionen->getPortionenDi();
        break;
    case 'mi':
        $portionen_db = $portionenaenderung->getPortionenMi();
        $standard_portionen = $standardportionen->getPortionenMi();
        break;
    case 'do':
        $portionen_db = $portionenaenderung->getPortionenDo();
        $standard_portionen = $standardportionen->getPortionenDo();
        break;
    case 'fr':
        $portionen_db = $portionenaenderung->getPortionenFr();
        $standard_portionen = $standardportionen->getPortionenFr();
        break;
}

$style = "color:#009400;";

if (isset($kunde) && $kunde->getStaedtischerKunde() && $portionen_db == 0) {
    $style = "color:red;";
}
switch ($kundentyp2) {
    case 'standard':
    case 'kitafino':
        break;
    case 'bio':
        break;
    case 'stadt':
        if ($portionen_db == 0) {
            $style = "color:red;";
        } else {
            $style = "color:#009400;";
        }
        break;
}




if (!isset($kundentyp)) {
    $kundentyp = $kundentyp2;
}


echo '<div style="' . $style . '">' . $portionen_db . '</div>';

if (isset($_REQUEST['dev1']) && $_REQUEST['dev1'] == 333) {

    $std_add_border = '';
//prüfen ob Eingabe um X prozent kleiner ist als standardportion
    if ($kundentyp2 != 'kitafino' && $kundentyp != 'kitafino') {
        if ($standard_portionen == 0) {
            $differenz_in_prozent = 0;
        } elseif ($portionen_db == 0) {
            $differenz_in_prozent = 0;
        } else {
            $differenz_in_prozent = (1 - $portionen_db / $standard_portionen) * 100;
        }
        $um_prozent_kleiner = 30; //Hinweis wenn die Portionen um X Prozent weniger sind
        $um_prozent_groesser = 30;  //Hinweis wenn die Portionen um X Prozent mehr sind
        // $differenz_in_prozent_2st = number_format($differenz_in_prozent, 2, ',', '.');

        if ($differenz_in_prozent < 0) { //Portioneneingabe ist kleiner als Standardmengen
//$differenz_in_prozent_2st = $differenz_in_prozent_2st * -1;
            if ($differenz_in_prozent < $um_prozent_groesser * (-1)) {
                // $std_add_border = 'border:1px solid red;';
                echo '<div style="clear:both;font-size:11px; background: red;color:#fff;padding:2px;">Prüfen! Eingabe ist über ' . $um_prozent_kleiner . '% geringer als Std..</div>';
            }
        }
        if ($differenz_in_prozent > 0) { //Portioneneingabe ist größer als Standardmengen
            if ($differenz_in_prozent > $um_prozent_groesser) {
                ///  $std_add_border = 'border:1px solid orangered;';
                echo '<div style="clear:both;font-size:11px; background: orangered;color:#fff;padding:2px;">Prüfen! Eingabe ist über ' . $um_prozent_groesser . '% größer als Std..</div>';
            }
        }

        if ($standard_portionen && $portionen_db) {
            
        }
        
          echo '<pre>';
          var_dump('DB: ' . $portionen_db, 'St: ' . $standard_portionen, $differenz_in_prozent);
          echo '</pre>'; 
    }
}
?>