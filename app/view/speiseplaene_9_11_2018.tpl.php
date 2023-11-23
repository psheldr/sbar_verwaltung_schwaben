
<div class="loader_cover" style="text-align:center;position:fixed;top: 30px;right: 30px;">
    <div>
        <h4>Wird geladen...</h4>
        <img src="images/load.gif"  />
    </div>

</div>
<?php
$add_to_links = '';
if ($action == 'cockpit') {
    $add_to_links = '&kids=' . $_REQUEST['kids'];
}
$wochenstarttag_ts = time();
//$heute = mktime(0,0,0,$anzeige_monat,$anzeige_tag,$anzeige_jahr);
//$heute = mktime(0,0,0,date('m'),date('d'),date('Y'));
/*
  $tag_anzeigen = 1;
  $monat_anzeigen = 9;
  $jahr_anzeigen = 2013;
 */

$tag_in_angezeigter_woche_ts = mktime(12, 0, 0, $monat_anzeigen, $tag_anzeigen, $jahr_anzeigen);
//$wochentag_string = strftime('%a', $heute);
$wochentag_string = strftime('%a', $tag_in_angezeigter_woche_ts);

$wochenstarttage = array();
switch ($wochentag_string) {
    case 'Sa':
        $start_tag_woche = date('d', $tag_in_angezeigter_woche_ts + 86400 * 2);
        $start_monat_woche = date('m', $tag_in_angezeigter_woche_ts + 86400 * 2);
        $start_jahr_woche = date('Y', $tag_in_angezeigter_woche_ts + 86400 * 2);
        //$wochenstarttage['woche1'] = $wochenstarttag_ts = $heute + 86400*2;
        break;
    case 'So':
        //$wochenstarttage['woche1'] = $wochenstarttag_ts = $heute + 86400*1;
        $start_tag_woche = date('d', $tag_in_angezeigter_woche_ts + 86400 * 1);
        $start_monat_woche = date('m', $tag_in_angezeigter_woche_ts + 86400 * 1);
        $start_jahr_woche = date('Y', $tag_in_angezeigter_woche_ts + 86400 * 1);
        break;
    case 'Mo':
        //$wochenstarttage['woche1'] = $wochenstarttag_ts = $heute + 86400*0;
        $start_tag_woche = date('d', $tag_in_angezeigter_woche_ts + 86400 * 0);
        $start_monat_woche = date('m', $tag_in_angezeigter_woche_ts + 86400 * 0);
        $start_jahr_woche = date('Y', $tag_in_angezeigter_woche_ts + 86400 * 0);
        break;
    case 'Di':
        //$wochenstarttage['woche1'] = $wochenstarttag_ts = $heute - 86400*1;
        $start_tag_woche = date('d', $tag_in_angezeigter_woche_ts - 86400 * 1);
        $start_monat_woche = date('m', $tag_in_angezeigter_woche_ts - 86400 * 1);
        $start_jahr_woche = date('Y', $tag_in_angezeigter_woche_ts - 86400 * 1);
        break;
    case 'Mi':
        //$wochenstarttage['woche1'] = $wochenstarttag_ts = $heute - 86400*2;
        $start_tag_woche = date('d', $tag_in_angezeigter_woche_ts - 86400 * 2);
        $start_monat_woche = date('m', $tag_in_angezeigter_woche_ts - 86400 * 2);
        $start_jahr_woche = date('Y', $tag_in_angezeigter_woche_ts - 86400 * 2);
        break;
    case 'Do':
        //$wochenstarttage['woche1'] = $wochenstarttag_ts = $heute - 86400*3;
        $start_tag_woche = date('d', $tag_in_angezeigter_woche_ts - 86400 * 3);
        $start_monat_woche = date('m', $tag_in_angezeigter_woche_ts - 86400 * 3);
        $start_jahr_woche = date('Y', $tag_in_angezeigter_woche_ts - 86400 * 3);
        break;
    case 'Fr':
        //$wochenstarttage['woche1'] = $wochenstarttag_ts = $heute - 86400*4;
        $start_tag_woche = date('d', $tag_in_angezeigter_woche_ts - 86400 * 4);
        $start_monat_woche = date('m', $tag_in_angezeigter_woche_ts - 86400 * 4);
        $start_jahr_woche = date('Y', $tag_in_angezeigter_woche_ts - 86400 * 4);
        break;
}



/*
  if (isset($_REQUEST['woche_mit_start_anzeigen'])) {
  $woche_mit_start_anzeigen = $_REQUEST['woche_mit_start_anzeigen'];
  $wochenstarttag_ts = $woche_mit_start_anzeigen;
  } else {
  $woche_mit_start_anzeigen = $wochenstarttag_ts;
  } */
/*
  if (!date('I', $wochenstarttag_ts)) {
  $wochenstarttag_ts += 3600;
  }
  if (!date('I', $woche_mit_start_anzeigen)) {
  $woche_mit_start_anzeigen += 3600;
  } */
?>
<?php /* if ($_REQUEST['dev']) { ?>
  <a class="action_links" href="index.php">zu alter Ansicht</a>
  <?php } else { ?>
  <a class="action_links" href="index.php?dev=1">zu neuer kitafino Ansicht</a>
  <?php } */ ?>
<br style="clear:both;" />
<br style="clear:both;" />
<hr />
<br />

<form method="post" action="index.php?action=<?php echo $action ?><?php echo $add_to_links ?>">

    <select name="monat_anzeigen">
        <?php
        for ($m = 1; $m <= 12; $m++) {

            switch ($m) {
                case 1:
                    $monat_str = 'Januar';
                    break;
                case 2:
                    $monat_str = 'Februar';
                    break;
                case 3:
                    $monat_str = 'März';
                    break;
                case 4:
                    $monat_str = 'April';
                    break;
                case 5:
                    $monat_str = 'Mai';
                    break;
                case 6:
                    $monat_str = 'Juni';
                    break;
                case 7:
                    $monat_str = 'Juli';
                    break;
                case 8:
                    $monat_str = 'August';
                    break;
                case 9:
                    $monat_str = 'September';
                    break;
                case 10:
                    $monat_str = 'Oktober';
                    break;
                case 11:
                    $monat_str = 'November';
                    break;
                case 12:
                    $monat_str = 'Dezember';
                    break;
            }
            ?>


            <option value="<?php echo $m ?>" <?php
            if ($m == $monat_anzeigen) {
                echo 'selected="selected"';
            }
            ?>><?php echo $monat_str ?></option>

        <?php } ?>
    </select>
    <select name="jahr_anzeigen">
        <?php for ($j = 2012; $j <= date('Y') + 1; $j++) { ?>
            <option value="<?php echo $j ?>" <?php
            if ($j == $jahr_anzeigen) {
                echo 'selected="selected"';
            }
            ?>><?php echo $j ?></option>
                <?php } ?>
    </select>
    <input type="submit" value="anzeigen" />
</form>


<?php
$ts_starttag_der_woche = mktime(12, 0, 0, $start_monat_woche, $start_tag_woche, $start_jahr_woche);

$vorige_woche_ts = $ts_starttag_der_woche - (86400 * 7);
$naechste_woche_ts = $ts_starttag_der_woche + (86400 * 7);

$starttag_vorige_woche = date('d', $vorige_woche_ts);
$startmonat_vorige_woche = date('m', $vorige_woche_ts);
$startjahr_vorige_woche = date('Y', $vorige_woche_ts);

$starttag_naechste_woche = date('d', $naechste_woche_ts);
$startmonat_naechste_woche = date('m', $naechste_woche_ts);
$startjahr_naechste_woche = date('Y', $naechste_woche_ts);
?>

<p>
    <a class="action_links" href="index.php?action=<?php echo $action ?>&starttag=<?php echo $starttag_vorige_woche ?>&startmonat=<?php echo $startmonat_vorige_woche ?>&startjahr=<?php echo $startjahr_vorige_woche ?><?php echo $add_to_links ?>"><<< zurück</a> |
    <a class="action_links" href="index.php?action=<?php echo $action ?><?php echo $add_to_links ?>">aktuelle Woche</a> |
    <a class="action_links" href="index.php?action=<?php echo $action ?>&starttag=<?php echo $starttag_naechste_woche ?>&startmonat=<?php echo $startmonat_naechste_woche ?>&startjahr=<?php echo $startjahr_naechste_woche ?><?php echo $add_to_links ?>">vor >>></a>
</p>
<?php if ($action != 'cockpit') { ?>
    <a class="action_links" style="background:#666;color:#fff;border: 2px solid #ddd; padding: 3px;" href="index.php?action=erzeuge_wochenmengenaufstellung&starttag=<?php echo $_REQUEST['starttag'] ?>&startmonat=<?php echo $_REQUEST['startmonat'] ?>&startjahr=<?php echo $_REQUEST['startjahr'] ?>&speise_id=<?php echo $speise_id ?>">Erzeuge Wochenmengenaufstellung als Excel</a>
<?php } ?>
<?php
$wochenstarttag = $ts_starttag_der_woche;
$dienstagts = $wochenstarttag + 86400 * 1;
$mittwochts = $wochenstarttag + 86400 * 2;
$donnerstagts = $wochenstarttag + 86400 * 3;
$freitagts = $wochenstarttag + 86400 * 4;

$woche_ts_array = array($wochenstarttag, $dienstagts, $mittwochts, $donnerstagts);

$tage_ts_woche_array = array($wochenstarttag, $dienstagts, $mittwochts, $donnerstagts, $freitagts);

$highlight_col_arr = array();
$color_col_hghlight = 'highlight_col';
?>
<form method="post" action="index.php?action=speichere_portionenaenderung">

    <input type="hidden" name="wochenstarttagts[]" value="<?php echo $wochenstarttag ?>" />
    <input type="hidden" name="starttag[]" value="<?php echo $start_tag_woche ?>" />
    <input type="hidden" name="startmonat[]" value="<?php echo $start_monat_woche ?>" />
    <input type="hidden" name="startjahr[]" value="<?php echo $start_jahr_woche ?>" />

    <table border="1" class="speiseplan_tbl" style="width: 100%;">
        <tr>
            <th style="min-width: 150px;" >
                KW <?php echo date('W', $wochenstarttag) ?>
            </th>
            <th colspan="2" <?php
            if (date('m') == date('m', $wochenstarttag) && date('d') == date('d', $wochenstarttag) && date('Y') == date('Y', $wochenstarttag)) {
                echo 'class="heute_tag"';
                $highlight_col_arr[1] = $color_col_hghlight;
            }
            ?>><a title="Tagesübersicht" href="index.php?action=uebersicht_tag&tag2=<?php echo date('d', $wochenstarttag) ?>&monat=<?php echo date('m', $wochenstarttag) ?>&jahr=<?php echo date('Y', $wochenstarttag) ?>&starttag=<?php echo date('d', $wochenstarttag) ?>&startmonat=<?php echo date('m', $wochenstarttag) ?>&startjahr=<?php echo date('Y', $wochenstarttag) ?>"><?php echo strftime('%a %d.%m.', $wochenstarttag) ?></a></th>

            <th colspan="2" <?php
            if (date('m') == date('m', $dienstagts) && date('d') == date('d', $dienstagts) && date('Y') == date('Y', $dienstagts)) {
                echo 'class="heute_tag"';
                $highlight_col_arr[2] = $color_col_hghlight;
            }
            ?>><a title="Tagesübersicht" href="index.php?action=uebersicht_tag&tag2=<?php echo date('d', $dienstagts) ?>&monat=<?php echo date('m', $dienstagts) ?>&jahr=<?php echo date('Y', $dienstagts) ?>&starttag=<?php echo date('d', $wochenstarttag) ?>&startmonat=<?php echo date('m', $wochenstarttag) ?>&startjahr=<?php echo date('Y', $wochenstarttag) ?>"><?php echo strftime('%a %d.%m.', $dienstagts) ?></a></th>
            <th colspan="2" <?php
            if (date('m') == date('m', $mittwochts) && date('d') == date('d', $mittwochts) && date('Y') == date('Y', $mittwochts)) {
                echo 'class="heute_tag"';
                $highlight_col_arr[3] = $color_col_hghlight;
            }
            ?>><a title="Tagesübersicht" href="index.php?action=uebersicht_tag&tag2=<?php echo date('d', $mittwochts) ?>&monat=<?php echo date('m', $mittwochts) ?>&jahr=<?php echo date('Y', $mittwochts) ?>&starttag=<?php echo date('d', $wochenstarttag) ?>&startmonat=<?php echo date('m', $wochenstarttag) ?>&startjahr=<?php echo date('Y', $wochenstarttag) ?>"><?php echo strftime('%a %d.%m.', $mittwochts) ?></a></th>
            <th colspan="2" <?php
            if (date('m') == date('m', $donnerstagts) && date('d') == date('d', $donnerstagts) && date('Y') == date('Y', $donnerstagts)) {
                echo 'class="heute_tag"';
                $highlight_col_arr[4] = $color_col_hghlight;
            }
            ?>><a title="Tagesübersicht" href="index.php?action=uebersicht_tag&tag2=<?php echo date('d', $donnerstagts) ?>&monat=<?php echo date('m', $donnerstagts) ?>&jahr=<?php echo date('Y', $donnerstagts) ?>&starttag=<?php echo date('d', $wochenstarttag) ?>&startmonat=<?php echo date('m', $wochenstarttag) ?>&startjahr=<?php echo date('Y', $wochenstarttag) ?>"><?php echo strftime('%a %d.%m.', $donnerstagts) ?></a></th>
            <th colspan="2" <?php
            if (date('m') == date('m', $freitagts) && date('d') == date('d', $freitagts) && date('Y') == date('Y', $freitagts)) {
                echo 'class="heute_tag"';
                $highlight_col_arr[5] = $color_col_hghlight;
            }
            ?>><a title="Tagesübersicht" href="index.php?action=uebersicht_tag&tag2=<?php echo date('d', $freitagts) ?>&monat=<?php echo date('m', $freitagts) ?>&jahr=<?php echo date('Y', $freitagts) ?>&starttag=<?php echo date('d', $wochenstarttag) ?>&startmonat=<?php echo date('m', $wochenstarttag) ?>&startjahr=<?php echo date('Y', $wochenstarttag) ?>"><?php echo strftime('%a %d.%m.', $freitagts) ?></a></th>

        </tr>
        <?php 
                $speisen_strings = array(); if ($action != 'cockpit') { ?>
            <tr>
                <td style="background: none;border:none;">
                </td>
                <?php
                for ($i = 1; $i <= 5; $i++) {
                    switch ($i) {
                        case 1:
                            $tag = date('d', $wochenstarttag);
                            $monat = date('m', $wochenstarttag);
                            $jahr = date('Y', $wochenstarttag);
                            break;
                        case 2:
                            $tag = date('d', $dienstagts);
                            $monat = date('m', $dienstagts);
                            $jahr = date('Y', $dienstagts);
                            break;
                        case 3:
                            $tag = date('d', $mittwochts);
                            $monat = date('m', $mittwochts);
                            $jahr = date('Y', $mittwochts);
                            break;
                        case 4:
                            $tag = date('d', $donnerstagts);
                            $monat = date('m', $donnerstagts);
                            $jahr = date('Y', $donnerstagts);
                            break;
                        case 5:
                            $tag = date('d', $freitagts);
                            $monat = date('m', $freitagts);
                            $jahr = date('Y', $freitagts);
                            break;
                    }


                    sprintf('%02d', $tag);
                    sprintf('%02d', $monat);
                    ?>

                    <td colspan="2">
                        <?php
                        $bestellung = $bestellungVerwaltung->findeAnhandVonTagMonatJahr($tag, $monat, $jahr);
                        if ($bestellung->getId()) {
                            $bestellung_has_speisen_check = $bestellung_has_speiseVerwaltung->findeAnhandVonBestellungId($bestellung->getId());
                        }
                        if ($bestellung->getId() && count($bestellung_has_speisen_check) > 0) {

                            $bestellung_has_speisen = $bestellung_has_speisen_check;



                            $sp_nr_check = 1;
                            $speise2_vorhanden = false;

                            $speisen_kitafino_info = array();

                            $speisen_tag_array = array();
                            for ($s = 1; $s <= 4; $s++) {
                                $speisen_tag_array[$s] = array(
                                    'speise_ids' => array(),
                                    'kitafino_info' => 0
                                );
                            }
                            $speisen_show_ids = array();
                            foreach ($bestellung_has_speisen as $bestellung_has_speise) {
                                $speise = $speiseVerwaltung->findeAnhandVonId($bestellung_has_speise->getSpeiseId());
                                $speisen_tag_array[$bestellung_has_speise->getSpeiseNr()]['speise_ids'][$bestellung_has_speise->getSpeiseId()] = $speise->getBezeichnung();

                                $speisen_strings[$i][$bestellung_has_speise->getSpeiseNr()]['speise_ids'][$bestellung_has_speise->getSpeiseId()] = $speise->getBezeichnung();



                                $speisen_tag_array[$bestellung_has_speise->getSpeiseNr()]['kitafino_info'] = $bestellung_has_speise->getKitafinoSpeiseNr();
                                $speisen_ids_temp[] = $bestellung_has_speise->getSpeiseId();
                            }


                            foreach ($speisen_tag_array as $speise_nr => $speisen_bestellung) {
                                $speisen_kitafino_info = $speisen_tag_array[$speise_nr]['kitafino_info'];
                                $menuname_str_obj = $menunamenVerwaltung->findeAnhandVonTagMonatJahrSpeiseNr($tag, $monat, $jahr, $speise_nr);
                                $menuname_str = $menuname_str_obj->getBezeichnung();
                                $menuname_str_intern = $menuname_str_obj->getBezeichnungIntern();
                                $show_class = 'show_' . $speise_nr . $tag . $monat . $jahr;
                                if (trim($menuname_str) == '') {
                                    $menuname_str = '<span style="color: red;">kein Menüname</span>';
                                }
                                if (trim($menuname_str_intern) == '') {
                                    $menuname_str_intern = '<span style="color: red;">kein Menüname intern</span>';
                                }

                                $margin_top = '5px';

                                if ($speise_nr > 1) {
                                    $margin_top = '20px';
                                }
                                ?>
                                <div style="border-bottom: 1px solid #ccc; background: #C3CD53;padding:3px;;margin-top: <?php echo $margin_top ?>;">Speise <?php echo $speise_nr ?>
                                    <?php
                                    if ($speisen_kitafino_info == 1 && $speise_nr <= 2) {
                                        echo '<span class="success_text sup">[kitafino Fleischgericht (Sp 1)]</span>';
                                    } elseif ($speisen_kitafino_info == 0 && $speise_nr <= 2) {
                                        echo '<span class="error_text sup">[kitafino Zuordnung fehlt!]</span>';
                                    }
                                    ?>
                                </div>
                                <div style="border-bottom: 1px solid #ccc; background: #D8E179;padding:3px;margin-top: 0;">
                                    <?php echo $menuname_str ?><br />
                                    <strong>intern:</strong> <?php echo $menuname_str_intern ?>
                                </div>
                                <?php if (count($speisen_bestellung['speise_ids']) == 0) { ?>
                                    <div class="error_text" style="border-top: 1px solid #ccc; padding: 5px; color: #fff; background: red;">Achtung: <?php echo $speise_nr ?>. Speise fehlt</div>
                                <?php } else { ?>
                                    <a href="#" class="show_kompos action_links" data-show="<?php echo $show_class ?>" style="padding:3px;display:block;border-bottom: 1px solid #ccc; background:#ddd;">Komponenten</a>


                                <?php } ?>

                                <span class="<?php echo $show_class ?> kompos">

                                    <?php foreach ($speisen_bestellung['speise_ids'] as $speise_id => $speise_str) { ?>
                                        <strong style="font-size: 11px;">
                                            <?php echo $speise_str; ?> <?php //echo $speise_id;   ?>
                                        </strong> <br />
                                    <?php }
                                    ?>  
                                </span>    
                            <?php }
                            ?>

                            <br /><br />
                            <a class="action_links" href="index.php?action=bearbeite_tag&tag=<?php echo $tag ?>&monat=<?php echo $monat ?>&jahr=<?php echo $jahr ?>&starttag=<?php echo $start_tag_woche ?>&startmonat=<?php echo $start_monat_woche ?>&startjahr=<?php echo $start_jahr_woche ?>">Speisen bearbeiten</a>
                        <?php } else { ?>
                            <a class="action_links" href="index.php?action=bearbeite_tag&tag=<?php echo $tag ?>&monat=<?php echo $monat ?>&jahr=<?php echo $jahr ?>&starttag=<?php echo $start_tag_woche ?>&startmonat=<?php echo $start_monat_woche ?>&startjahr=<?php echo $start_jahr_woche ?>">Speisen festlegen</a>
                        <?php } ?>
                    </td>
                <?php } ?>
            </tr>
        <?php } ?>
        <tr class="th2">
            <th class="<?php echo $highlight_col_arr[1] ?>">Kunde</th>            

            <th class="<?php echo $highlight_col_arr[1] ?>">Speise</th>
            <th class="<?php echo $highlight_col_arr[1] ?>">Port.</th>

            <th class="<?php echo $highlight_col_arr[2] ?>">Speise</th>
            <th class="<?php echo $highlight_col_arr[2] ?>">Port.</th>

            <th class="<?php echo $highlight_col_arr[3] ?>">Speise</th>
            <th class="<?php echo $highlight_col_arr[3] ?>">Port.</th>

            <th class="<?php echo $highlight_col_arr[4] ?>">Speise</th>
            <th class="<?php echo $highlight_col_arr[4] ?>">Port.</th>

            <th class="<?php echo $highlight_col_arr[5] ?>" >Speise</th>
            <th class="<?php echo $highlight_col_arr[5] ?>">Port.</th>
        </tr>
        <?php
        $gesamtportionen_mo = 0;
        $gesamtportionen_di = 0;
        $gesamtportionen_mi = 0;
        $gesamtportionen_do = 0;
        $gesamtportionen_fr = 0;


        /* echo '<pre>';
          var_dump($speisen_strings);
          echo '</pre>'; */



        if ($_REQUEST['dev']) {
            $kunden_kitafino = $kundeVerwaltung->findeAlleMitKundennummerKitafino();
            $bestellzahlen = ermittleZahlenZuKundeWoche($tage_ts_woche_array, $kunden_kitafino);



            ksort($bestellzahlen);
            // var_dump($bestellzahlen);
            ////var_dump($d, $m, $y,$bestellungen_tag);
            $portionenaenderungen = array();
            $portionen_tage = array();
            foreach ($bestellzahlen as $pid_sbarkid_key_str => $bestellungen_kunde) {
                $subs = explode('-', $pid_sbarkid_key_str);
                $pid = $subs[0];
                $sbarkid = $subs[1];
                $kunde = $kundeVerwaltung->findeAnhandVonId($sbarkid);
                $orders_tag = array();
                $ct = 0;


                /// foreach ($bestellungen_kunde as $tag_key_str => $bestellungen_tag) {
                foreach ($tage_ts_woche_array as $tag_ts) {

                    if ($bestellungen_kunde == 0) {
                        $portionen_tage[$pid][$sbarkid][1][$ct] = 0;
                    }


                    $d = date('d', $tag_ts);
                    $m = date('m', $tag_ts);
                    $y = date('Y', $tag_ts);
                    /* $d = $subs[0];
                      $m = $subs[1];
                      $y = $subs[2]; */
                    if ($bestellungen_kunde[$ct] == NULL) {
                        $bestellungen_tag = array();
                    } else {
                        $bestellungen_tag = $bestellungen_kunde[$ct];
                    }
                    $subs = explode('-', $tag_key_str);

                    $bestellungen_tag = array_values($bestellungen_tag);

                    $bestellungen_tag = $bestellungen_tag[0];

                    if ($bestellungen_tag == NULL) {
                        $bestellungen_tag = array();
                    }

                    foreach ($bestellungen_tag as $speise_nr => $bestellungen_menunr) {

                        if ($kunde->getBioKunde()) {
                            switch ($speise_nr) {
                                case 1:
                                    $speise_nr = 3;
                                    break;
                                case 2:
                                    $speise_nr = 4;
                                    break;
                            }
                        }

                        if ($kunde->getAnzahlSpeisen() > 1) {

                            $bestellung = $bestellungVerwaltung->findeAnhandVonTagMonatJahr($d, $m, $y);


                            if ($bestellung->getId()) {

                                $bestellung_has_speisen = $bestellung_has_speiseVerwaltung->findeAnhandVonBestellungIdUndSpeiseNr($bestellung->getId(), $speise_nr);
                            }
                            //$bestellung_has_speisen2 = $bestellung_has_speiseVerwaltung->findeAnhandVonBestellungIdUndSpeiseNr($bestellung->getId(), 2);


                            if (count($bestellung_has_speisen) && $bestellung_has_speisen[0]->getKitafinoSpeiseNr() && !$kunde->getBioKunde()) {

                                $speise_nr = $bestellung_has_speisen[0]->getKitafinoSpeiseNr();
                            }
                        }

                        /* if ($ct == 0) {
                          $portionenaenderungen[$pid][$sbarkid][$speise_nr] = $portionenaenderungVerwaltung->findeAnhandVonKundenIdUndWochenstartdatum($sbarkid, $d, $m, $y, $speise_nr);
                          } */
                        $portionen_tage[$pid][$sbarkid][$speise_nr][$ct] = $bestellungen_menunr;
                    }
                    $ct++;
                }
            }

            /// var_dump('-------------------------------------------------------------------------------------------------------------------');
        }
        foreach ($portionen_tage as $projekt_id => $portionen_tag_kid) {
            /// if ($projekt_id != '90555' && $projekt_id != '96000' && $projekt_id != '96003') {
            ///   continue;
            /// }
            foreach ($portionen_tag_kid as $sbar_kunden_id => $portionen_tag_menunr) {
                $portionenaenderung_vorhanden = new Portionenaenderung();


                foreach ($portionen_tag_menunr as $speise_nr => $portionen_tag) {
                    $ts_starttag = $woche_ts_array[0];
                    $wochenstarttag_ts = mktime(12, 0, 0, date('m', $ts_starttag), date('d', $ts_starttag), date('Y', $ts_starttag));
                    $d = date('d', $wochenstarttag_ts);
                    $m = date('m', $wochenstarttag_ts);
                    $y = date('Y', $wochenstarttag_ts);



                    $portionenaenderung_vorhanden = $portionenaenderungVerwaltung->findeAnhandVonKundenIdUndWochenstartdatum($sbar_kunden_id, $d, $m, $y, $speise_nr);


                    if ($portionenaenderung_vorhanden->getId()) {
                        $neue_portionenaenderung = $portionenaenderung_vorhanden;
                    } else {
                        $neue_portionenaenderung = new Portionenaenderung();
                        $neue_portionenaenderung->setKundeId($sbar_kunden_id);
                        $neue_portionenaenderung->setWochenstarttagTs($wochenstarttag_ts);
                        $neue_portionenaenderung->setStarttag($d);
                        $neue_portionenaenderung->setStartmonat($m);
                        $neue_portionenaenderung->setStartjahr($y);
                        $neue_portionenaenderung->setSpeiseNr($speise_nr);
                    }
                    $neue_portionenaenderung->setPortionenMo($portionen_tag[0]);
                    $neue_portionenaenderung->setPortionenDi($portionen_tag[1]);
                    $neue_portionenaenderung->setPortionenMi($portionen_tag[2]);
                    $neue_portionenaenderung->setPortionenDo($portionen_tag[3]);
                    $neue_portionenaenderung->setPortionenFr($portionen_tag[4]);
                    $check_kunde = $kundeVerwaltung->findeAnhandVonId($sbar_kunden_id);
                    if ($check_kunde->getKitafinoGruppen()) {
                        $portionenaenderungVerwaltung->speichere($neue_portionenaenderung);
                    }
                }
            }
        }

        $rowcount = 1;

        $count_queries = 0;
        $time_sum = 0;
        $focus_count = 0;
        $gesamt_portionen_array = array();
        foreach ($kunden as $kunde) {

            $daten_neue_port_aenderung = array(
                'kunde_id' => $kunde->getId() * 1,
                'wochenstarttagts' => $wochenstarttag,
                'starttag' => $start_tag_woche,
                'startmonat' => $start_monat_woche,
                'startjahr' => $start_jahr_woche,
                'speise_nr' => 0
            );

            $highlight_class = '';
            if ($rowcount % 2 == 0) {
                $highlight_class = 'highlight_row';
            }
            if ($kunde->getEinrichtungskategorieId() == 5) {
                $highlight_class .= ' tourtrenner';
            }
            if ($kunde->getEinrichtungskategorieId() == 6) {
                $highlight_class .= ' tourzeile';
                $rowcount = 0;
            }
            $rowcount++;
// Abrechnungstage ergänzen
            $abrechnungstag_check_mo = $abrechnungstagVerwaltung->findeAnhandVonKundeIdUndTagMonatJahr($kunde->getId(), $tag, $monat, $jahr);
            $abrechnungstag_check_di = $abrechnungstagVerwaltung->findeAnhandVonKundeIdUndTagMonatJahr($kunde->getId(), date('d', $dienstagts), date('m', $dienstagts), date('Y', $dienstagts));
            $abrechnungstag_check_mi = $abrechnungstagVerwaltung->findeAnhandVonKundeIdUndTagMonatJahr($kunde->getId(), date('d', $mittwochts), date('m', $mittwochts), date('Y', $mittwochts));
            $abrechnungstag_check_do = $abrechnungstagVerwaltung->findeAnhandVonKundeIdUndTagMonatJahr($kunde->getId(), date('d', $donnerstagts), date('m', $donnerstagts), date('Y', $donnerstagts));
            $abrechnungstag_check_fr = $abrechnungstagVerwaltung->findeAnhandVonKundeIdUndTagMonatJahr($kunde->getId(), date('d', $freitagts), date('m', $freitagts), date('Y', $freitagts));


// Abrechnungstage ergänzen Ende

            $standardportionen = $standardportionenVerwaltung->findeAnhandVonKundenIdUndSpeiseNr($kunde->getId(), 1);
            $standardportionen2 = $standardportionenVerwaltung->findeAnhandVonKundenIdUndSpeiseNr($kunde->getId(), 2);
            $standardportionen3 = $standardportionenVerwaltung->findeAnhandVonKundenIdUndSpeiseNr($kunde->getId(), 3);
            $standardportionen4 = $standardportionenVerwaltung->findeAnhandVonKundenIdUndSpeiseNr($kunde->getId(), 4);

            $portionenaenderung = $portionenaenderungVerwaltung->findeAnhandVonKundenIdUndWochenstartdatum($kunde->getId(), $start_tag_woche, $start_monat_woche, $start_jahr_woche, 1);
            $portionenaenderung2 = $portionenaenderungVerwaltung->findeAnhandVonKundenIdUndWochenstartdatum($kunde->getId(), $start_tag_woche, $start_monat_woche, $start_jahr_woche, 2);
            $portionenaenderung3 = $portionenaenderungVerwaltung->findeAnhandVonKundenIdUndWochenstartdatum($kunde->getId(), $start_tag_woche, $start_monat_woche, $start_jahr_woche, 3);
            $portionenaenderung4 = $portionenaenderungVerwaltung->findeAnhandVonKundenIdUndWochenstartdatum($kunde->getId(), $start_tag_woche, $start_monat_woche, $start_jahr_woche, 4);

            if ($kunde->getBioKunde() || $kunde->getStaedtischerKunde()) {
                $portionenaenderungVerwaltung->loesche($portionenaenderung);
                $portionenaenderungVerwaltung->loesche($portionenaenderung2);
            }
            /* echo '<pre>';
              var_dump($kunde->getId(), $portionenaenderung, $portionenaenderung2);
              echo '</pre>'; */

            if ($portionenaenderung->getId()) {
                $portionen_mo = $portionenaenderung->getPortionenMo();
                $portionen_di = $portionenaenderung->getPortionenDi();
                $portionen_mi = $portionenaenderung->getPortionenMi();
                $portionen_do = $portionenaenderung->getPortionenDo();
                $portionen_fr = $portionenaenderung->getPortionenFr();
                $aenderung = true;
            } else {
                $daten_neue_port_aenderung['speise_nr'] = 1;
                $portionenaenderung = new Portionenaenderung($daten_neue_port_aenderung);
                $portionen_mo = $standardportionen->getPortionenMo();
                $portionen_di = $standardportionen->getPortionenDi();
                $portionen_mi = $standardportionen->getPortionenMi();
                $portionen_do = $standardportionen->getPortionenDo();
                $portionen_fr = $standardportionen->getPortionenFr();
                $aenderung = false;
            }
            $standardportionen_array = array($standardportionen->getPortionenMo(),
                $standardportionen->getPortionenDi(),
                $standardportionen->getPortionenMi(),
                $standardportionen->getPortionenDo(),
                $standardportionen->getPortionenFr()
            );
            $standardportionen_array2 = array($standardportionen2->getPortionenMo(),
                $standardportionen2->getPortionenDi(),
                $standardportionen2->getPortionenMi(),
                $standardportionen2->getPortionenDo(),
                $standardportionen2->getPortionenFr()
            );
            $standardportionen_array3 = array($standardportionen3->getPortionenMo(),
                $standardportionen3->getPortionenDi(),
                $standardportionen3->getPortionenMi(),
                $standardportionen3->getPortionenDo(),
                $standardportionen3->getPortionenFr()
            );
            $standardportionen_array4 = array($standardportionen4->getPortionenMo(),
                $standardportionen4->getPortionenDi(),
                $standardportionen4->getPortionenMi(),
                $standardportionen4->getPortionenDo(),
                $standardportionen4->getPortionenFr()
            );

            $standardports_array = array();
            $standardports_array[1] = $standardportionen_array;
            $standardports_array[2] = $standardportionen_array2;
            $standardports_array[3] = $standardportionen_array3;
            $standardports_array[4] = $standardportionen_array4;

            if ($portionenaenderung2->getId()) {
                $portionen2_mo = $portionenaenderung2->getPortionenMo();
                $portionen2_di = $portionenaenderung2->getPortionenDi();
                $portionen2_mi = $portionenaenderung2->getPortionenMi();
                $portionen2_do = $portionenaenderung2->getPortionenDo();
                $portionen2_fr = $portionenaenderung2->getPortionenFr();
                $aenderung2 = true;
            } else {
                $daten_neue_port_aenderung['speise_nr'] = 2;
                $portionenaenderung2 = new Portionenaenderung($daten_neue_port_aenderung);
                $portionen2_mo = $standardportionen2->getPortionenMo();
                $portionen2_di = $standardportionen2->getPortionenDi();
                $portionen2_mi = $standardportionen2->getPortionenMi();
                $portionen2_do = $standardportionen2->getPortionenDo();
                $portionen2_fr = $standardportionen2->getPortionenFr();
                $aenderung2 = false;
            }

            if ($portionenaenderung3->getId()) {
                $portionen3_mo = $portionenaenderung3->getPortionenMo();
                $portionen3_di = $portionenaenderung3->getPortionenDi();
                $portionen3_mi = $portionenaenderung3->getPortionenMi();
                $portionen3_do = $portionenaenderung3->getPortionenDo();
                $portionen3_fr = $portionenaenderung3->getPortionenFr();
                $aenderung3 = true;
            } else {
                $daten_neue_port_aenderung['speise_nr'] = 3;
                $portionenaenderung3 = new Portionenaenderung($daten_neue_port_aenderung);
                $portionen3_mo = $standardportionen3->getPortionenMo();
                $portionen3_di = $standardportionen3->getPortionenDi();
                $portionen3_mi = $standardportionen3->getPortionenMi();
                $portionen3_do = $standardportionen3->getPortionenDo();
                $portionen3_fr = $standardportionen3->getPortionenFr();
                $aenderung3 = false;
            }

            if ($portionenaenderung4->getId()) {
                $portionen4_mo = $portionenaenderung4->getPortionenMo();
                $portionen4_di = $portionenaenderung4->getPortionenDi();
                $portionen4_mi = $portionenaenderung4->getPortionenMi();
                $portionen4_do = $portionenaenderung4->getPortionenDo();
                $portionen4_fr = $portionenaenderung4->getPortionenFr();
                $aenderung4 = true;
            } else {
                $daten_neue_port_aenderung['speise_nr'] = 4;
                $portionenaenderung4 = new Portionenaenderung($daten_neue_port_aenderung);
                $portionen4_mo = $standardportionen4->getPortionenMo();
                $portionen4_di = $standardportionen4->getPortionenDi();
                $portionen4_mi = $standardportionen4->getPortionenMi();
                $portionen4_do = $standardportionen4->getPortionenDo();
                $portionen4_fr = $standardportionen4->getPortionenFr();
                $aenderung4 = false;
            }

            $echo_start_array = array();
            $echo_mo = $echo_di = $echo_mi = $echo_do = $echo_fr = '';
            if ($kunde->getStartdatum() > 0 && $kunde->getStartdatum() > $wochenstarttag) {
                $echo_mo = '<p style="color: red;font-size: 10px;">Startet am ' . strftime('%d.%m.%Y', $kunde->getStartdatum()) . '</p>';
                $portionen_mo = 0;
                $portionen2_mo = 0;
                $portionen3_mo = 0;
                $portionen4_mo = 0;
                $echo_start_array[1] = $echo_mo;

                if (!$kunde->getStaedtischerKunde() && !$kunde->getBioKunde()) {
                    $portionenaenderung->setPortionenMo(0);
                    $portionenaenderungVerwaltung->speichere($portionenaenderung);
                }
                if (!$kunde->getStaedtischerKunde() && !$kunde->getBioKunde() && $kunde->getAnzahlSpeisen() > 1) {
                    $portionenaenderung2->setPortionenMo(0);
                    $portionenaenderungVerwaltung->speichere($portionenaenderung2);
                }
                if ($kunde->getStaedtischerKunde() || $kunde->getBioKunde()) {
                    $portionenaenderung3->setPortionenMo(0);
                    $portionenaenderungVerwaltung->speichere($portionenaenderung3);
                }
                if ($kunde->getStaedtischerKunde() || ($kunde->getBioKunde() && $kunde->getAnzahlSpeisen() > 1)) {
                    $portionenaenderung4->setPortionenMo(0);
                    $portionenaenderungVerwaltung->speichere($portionenaenderung4);
                }
            }
            if ($kunde->getStartdatum() > 0 && $kunde->getStartdatum() > $dienstagts) {
                $echo_di = '<p style="color: red;font-size: 10px;">Startet am ' . strftime('%d.%m.%Y', $kunde->getStartdatum()) . '</p>';
                $portionen_di = 0;
                $portionen2_di = 0;
                $portionen3_di = 0;
                $portionen4_di = 0;
                $echo_start_array[2] = $echo_di;
                if (!$kunde->getStaedtischerKunde() && !$kunde->getBioKunde()) {
                    $portionenaenderung->setPortionenDi(0);
                    $portionenaenderungVerwaltung->speichere($portionenaenderung);
                }
                if (!$kunde->getStaedtischerKunde() && !$kunde->getBioKunde() && $kunde->getAnzahlSpeisen() > 1) {

                    $portionenaenderung2->setPortionenDi(0);
                    $portionenaenderungVerwaltung->speichere($portionenaenderung2);
                }
                if ($kunde->getStaedtischerKunde() || $kunde->getBioKunde()) {

                    $portionenaenderung3->setPortionenDi(0);
                    $portionenaenderungVerwaltung->speichere($portionenaenderung3);
                }
                if ($kunde->getStaedtischerKunde() || ($kunde->getBioKunde() && $kunde->getAnzahlSpeisen() > 1)) {

                    $portionenaenderung4->setPortionenDi(0);
                    $portionenaenderungVerwaltung->speichere($portionenaenderung4);
                }
            }
            if ($kunde->getStartdatum() > 0 && $kunde->getStartdatum() > $mittwochts) {

                $echo_mi = '<p style="color: red;font-size: 10px;">Startet am ' . strftime('%d.%m.%Y', $kunde->getStartdatum()) . '</p>';
                $portionen_mi = 0;
                $portionen2_mi = 0;
                $portionen3_mi = 0;
                $portionen4_mi = 0;
                $echo_start_array[3] = $echo_mi;
                if (!$kunde->getStaedtischerKunde() && !$kunde->getBioKunde()) {
                    $portionenaenderung->setPortionenMi(0);
                    $portionenaenderungVerwaltung->speichere($portionenaenderung);
                }
                if (!$kunde->getStaedtischerKunde() && !$kunde->getBioKunde() && $kunde->getAnzahlSpeisen() > 1) {

                    $portionenaenderung2->setPortionenMi(0);
                    $portionenaenderungVerwaltung->speichere($portionenaenderung2);
                }
                if ($kunde->getStaedtischerKunde() || $kunde->getBioKunde()) {

                    $portionenaenderung3->setPortionenMi(0);
                    $portionenaenderungVerwaltung->speichere($portionenaenderung3);
                }
                if ($kunde->getStaedtischerKunde() || ($kunde->getBioKunde() && $kunde->getAnzahlSpeisen() > 1)) {

                    $portionenaenderung4->setPortionenMi(0);
                    $portionenaenderungVerwaltung->speichere($portionenaenderung4);
                }
            }
            if ($kunde->getStartdatum() > 0 && $kunde->getStartdatum() > $donnerstagts) {
                $echo_do = '<p style="color: red;font-size: 10px;">Startet am ' . strftime('%d.%m.%Y', $kunde->getStartdatum()) . '</p>';
                $portionen_do = 0;
                $portionen2_do = 0;
                $portionen3_do = 0;
                $portionen4_do = 0;
                $echo_start_array[4] = $echo_do;
                if (!$kunde->getStaedtischerKunde() && !$kunde->getBioKunde()) {
                    $portionenaenderung->setPortionenDo(0);
                    $portionenaenderungVerwaltung->speichere($portionenaenderung);
                }
                if (!$kunde->getStaedtischerKunde() && !$kunde->getBioKunde() && $kunde->getAnzahlSpeisen() > 1) {

                    $portionenaenderung2->setPortionenDo(0);
                    $portionenaenderungVerwaltung->speichere($portionenaenderung2);
                }
                if ($kunde->getStaedtischerKunde() || $kunde->getBioKunde()) {

                    $portionenaenderung3->setPortionenDo(0);
                    $portionenaenderungVerwaltung->speichere($portionenaenderung3);
                }
                if ($kunde->getStaedtischerKunde() || ($kunde->getBioKunde() && $kunde->getAnzahlSpeisen() > 1)) {

                    $portionenaenderung4->setPortionenDo(0);
                    $portionenaenderungVerwaltung->speichere($portionenaenderung4);
                }
            }
            if ($kunde->getStartdatum() > 0 && $kunde->getStartdatum() > $freitagts) {
                $echo_fr = '<p style="color: red;font-size: 10px;">Startet am ' . strftime('%d.%m.%Y', $kunde->getStartdatum()) . '</p>';
                $portionen_fr = 0;
                $portionen2_fr = 0;
                $portionen3_fr = 0;
                $portionen4_fr = 0;
                $echo_start_array[5] = $echo_fr;
                if (!$kunde->getStaedtischerKunde() && !$kunde->getBioKunde()) {
                    $portionenaenderung->setPortionenFr(0);
                    $portionenaenderungVerwaltung->speichere($portionenaenderung);
                }
                if (!$kunde->getStaedtischerKunde() && !$kunde->getBioKunde() && $kunde->getAnzahlSpeisen() > 1) {

                    $portionenaenderung2->setPortionenFr(0);
                    $portionenaenderungVerwaltung->speichere($portionenaenderung2);
                }
                if ($kunde->getStaedtischerKunde() || $kunde->getBioKunde()) {

                    $portionenaenderung3->setPortionenFr(0);
                    $portionenaenderungVerwaltung->speichere($portionenaenderung3);
                }
                if ($kunde->getStaedtischerKunde() || ($kunde->getBioKunde() && $kunde->getAnzahlSpeisen() > 1)) {

                    $portionenaenderung4->setPortionenFr(0);
                    $portionenaenderungVerwaltung->speichere($portionenaenderung4);
                }
            }

            /*   if (!$kunde->getStaedtischerKunde() && !$kunde->getBioKunde()) {
              $portionenaenderungVerwaltung->speichere($portionenaenderung);
              }
              if (!$kunde->getStaedtischerKunde() && !$kunde->getBioKunde() && $kunde->getAnzahlSpeisen() > 1) {
              $portionenaenderungVerwaltung->speichere($portionenaenderung2);
              }
              if ($kunde->getStaedtischerKunde() || $kunde->getBioKunde()) {
              $portionenaenderungVerwaltung->speichere($portionenaenderung3);
              }
              if ($kunde->getStaedtischerKunde() || ($kunde->getBioKunde() && $kunde->getAnzahlSpeisen() > 1)) {
              $portionenaenderungVerwaltung->speichere($portionenaenderung4);
              } */

            $gesamtportionen_mo += $portionen_mo;
            $gesamtportionen_di += $portionen_di;
            $gesamtportionen_mi += $portionen_mi;
            $gesamtportionen_do += $portionen_do;
            $gesamtportionen_fr += $portionen_fr;

            $gesamtportionen_mo += $portionen2_mo;
            $gesamtportionen_di += $portionen2_di;
            $gesamtportionen_mi += $portionen2_mi;
            $gesamtportionen_do += $portionen2_do;
            $gesamtportionen_fr += $portionen2_fr;


            $gesamtportionen_mo += $portionen3_mo;
            $gesamtportionen_di += $portionen3_di;
            $gesamtportionen_mi += $portionen3_mi;
            $gesamtportionen_do += $portionen3_do;
            $gesamtportionen_fr += $portionen3_fr;

            $gesamtportionen_mo += $portionen4_mo;
            $gesamtportionen_di += $portionen4_di;
            $gesamtportionen_mi += $portionen4_mi;
            $gesamtportionen_do += $portionen4_do;
            $gesamtportionen_fr += $portionen4_fr;

            $portionen_array = array($portionen_mo, $portionen_di, $portionen_mi, $portionen_do, $portionen_fr);
            $portionen2_array = array($portionen2_mo, $portionen2_di, $portionen2_mi, $portionen2_do, $portionen2_fr);
            $portionen3_array = array($portionen3_mo, $portionen3_di, $portionen3_mi, $portionen3_do, $portionen3_fr);
            $portionen4_array = array($portionen4_mo, $portionen4_di, $portionen4_mi, $portionen4_do, $portionen4_fr);
            if ($_REQUEST['dev']) {
                /* echo '<pre>';     
                  var_dump($kunde->getAnzahlSpeisen(),$gesamtportionen_di, $gesamtportionen_di);
                  echo '</pre>'; */
            }

            echo '<pre>';
            $ports_array = array();
            $ports_array[1] = $portionen_array;
            $ports_array[2] = $portionen2_array;
            $ports_array[3] = $portionen3_array;
            $ports_array[4] = $portionen4_array;
            echo '</pre>';

            $ccc = 0;
            $tag_nr = 0;
            foreach ($tage_ts_woche_array as $tag_ts_check) {
                //continue; ///temp
                $d = date('d', $tag_ts_check);
                $m = date('m', $tag_ts_check);
                $y = date('y', $tag_ts_check);
                $check_auf_bestellung = $bestellungVerwaltung->findeAnhandVonTagMonatJahr(date('d', $tag_ts_check), date('m', $tag_ts_check), date('Y', $tag_ts_check));
                //var_dump($check_auf_bestellung);

                if (!$check_auf_bestellung->getId()) {
                    $check_auf_bestellung = new Bestellung();
                    $check_auf_bestellung->setTag($tag_ts_check);
                    $check_auf_bestellung->setTag2(date('d', $tag_ts_check));
                    $check_auf_bestellung->setMonat(date('m', $tag_ts_check));
                    $check_auf_bestellung->setJahr(date('Y', $tag_ts_check));
                    $bestellungVerwaltung->speichere($check_auf_bestellung);
                }
                if ($check_auf_bestellung->getId() && $kunde->getAktiv() == 1) {
                    //$abrechnungstage_check = $abrechnungstagVerwaltung->findeAlleZuKundeIdUndTagMonatJahr($kunde->getId(), date('d', $tag_ts_check), date('m', $tag_ts_check), date('Y', $tag_ts_check));

                    for ($check_m = 1; $check_m <= 4; $check_m++) {
                        $speisen_ids_array = array();
                        $abrechnungstag_check = $abrechnungstagVerwaltung->findeAnhandVonKundeIdUndTagMonatJahr($kunde->getId(), date('d', $tag_ts_check), date('m', $tag_ts_check), date('Y', $tag_ts_check), $check_m);
                        if (!$abrechnungstag_check->getId()) {
                            $abrechnungstag_check = new Abrechnungstag();
                            $abrechnungstag_check->setKundeId($kunde->getId());
                            $abrechnungstag_check->setTag($tag_ts_check);
                            $bestellung_has_speisen_check = $bestellung_has_speiseVerwaltung->findeAnhandVonBestellungIdUndSpeiseNr($check_auf_bestellung->getId(), $check_m);


                            foreach ($bestellung_has_speisen_check as $speisen_ids_aus_best) {
                                $speisen_ids_array[] = $speisen_ids_aus_best->getSpeiseId();
                            }
                            $abrechnungstag_check->setSpeisenIds(implode(', ', $speisen_ids_array));
                            $abrechnungstag_check->setTag2(date('d', $tag_ts_check));
                            $abrechnungstag_check->setMonat(date('m', $tag_ts_check));
                            $abrechnungstag_check->setJahr(date('Y', $tag_ts_check));
                            $abrechnungstag_check->setSpeiseNr($check_m);
                        }
                        $lieferschein_nummer = $y . $m . $d . '-' . $kunde->getId() . '-' . $check_m;
                        $abrechnungstag_check->setPortionen($ports_array[$check_m][$tag_nr]);
                        $abrechnungstag_check->setLieferschein($lieferschein_nummer);

                        $abrechnungstagVerwaltung->speichere($abrechnungstag_check);
                    }
                    $ccc++;
                }
                $tag_nr++;
            }
            ?>


            <?php include 'includes/portionen.php' ///temp  ?>

            <input type="hidden" name="kunde_id[]" value="<?php echo $kunde->getId() ?>" />



                                                                                                                                                                                                                                                                                                                                                               <!--<tr>
                                                                                                                                                                                                                                                                                                                                                                                    <td colspan="10"><?php ?></td>
                                                                                                                                                                                                                                                                                                                                                                                </tr>-->
        <?php }
        ?>

        
        <?php if ($action != 'cockpit') {
            ?>
            <tr class="gesamt_tr">
                <td></td>
                <td>Gesamt</td>
               <!-- <td><?php echo $gesamtportionen_mo ?></td>-->
                <td><?php echo $gesamt_portionen_array[1] ?></td>

                <td>Gesamt</td>
                <!--<td><?php echo $gesamtportionen_di ?></td>-->
                <td><?php echo $gesamt_portionen_array[2] ?> </td>

                <td>Gesamt</td>
                <!--<td><?php echo $gesamtportionen_mi ?></td>-->
                <td><?php echo $gesamt_portionen_array[3] ?></td>

                <td>Gesamt</td>
                <!--<td><?php echo $gesamtportionen_do ?></td>-->
                <td><?php echo $gesamt_portionen_array[4] ?> </td>

                <td>Gesamt</td>
                <!--<td><?php echo $gesamtportionen_fr ?></td>-->
                <td><?php echo $gesamt_portionen_array[5] ?> </td>

            </tr> 
    
        <?php }
        ?>
        <tr>
            <!--<td colspan="9" style="border: none;">
                <sup>1</sup>abweichend von Standardmenge
            </td>-->
        </tr>
    </table>
</form>
<script>

    $(document).ready(function () {

        $('.loader_cover').hide();
        $('.loader_cover div').hide();


        $('.portionen_input').removeAttr('disabled');

        $(".portionen_input").on('focus', function () {
            $(this).select();
        });

        $('.portionen_input').on('input', function (event) {

            saveData($(this));
            /*  setTimeout(function () {
             saveData($(this));
             }, 1000);*/


        });
        $('.portionen_input').on("keyup", function (event) {
            if ($(this).val() >= 100) {
                alert('Achtung! Es wurde eine Menge von über 100 eingestellt. Bitte prüfen!');
            }
        });
        $('.portionen_input').on("keydown", function (event) {
            if (event.keyCode == 13) {
                event.preventDefault();
                var focus_nr = $(this).data('focus');
                focus_nr = focus_nr + 1;

                $('.focus_' + focus_nr).focus();
                $('.focus_' + focus_nr).select();
            }

            //    
        });

        function saveData(el) {
            var loader = el.data('loader');
            //     $('#'+loader).html('<img src="images/load.gif">');
            var kunde_id = el.data('kid');
            var tag = el.data('tag');
            var speise_nr = el.data('speisenr');
            var starttag = el.data('starttag');
            var startmonat = el.data('startmonat');
            var startjahr = el.data('startjahr');
            var startts = el.data('startts');
            var tag_ts = el.data('tag_ts');
            var focus_nr = el.data('focus');
            var kundentyp = el.data('kundentyp');
            var portionen = el.val();
            var count_tag = el.data('counttag');
            var portionen_db = $('#portionen_in_db_' + focus_nr + ' span').html();
            $('#portionen_in_db_' + focus_nr).hide();
            $('#standardportionen_' + focus_nr).hide();
            $('#' + loader).html('<img src="images/load.gif">');

            var check_snr = 0;
            if (speise_nr == 3) {
                check_snr = 4;
            }
            if (speise_nr == 4) {
                check_snr = 3;
            }

            $('.portionen_alarm_' + count_tag + '_' + kunde_id + '_' + speise_nr).hide();
            $('.portionen_alarm_' + count_tag + '_' + kunde_id + '_' + check_snr).hide();
            if (kundentyp === 'stadt' && portionen > 0) {
                var inhalt_zweite_speise_stadt = $('.input_tag_' + kunde_id + '_' + count_tag + '_' + check_snr + ' div').html();
              
                if (inhalt_zweite_speise_stadt > 0) {
                    $('.portionen_alarm_' + count_tag + '_' + kunde_id + '_' + check_snr).show();
                    $('.portionen_alarm_' + count_tag + '_' + kunde_id + '_' + speise_nr).show();
                } else {
                    $('.portionen_alarm_' + count_tag + '_' + kunde_id + '_' + check_snr).hide();
                    $('.portionen_alarm_' + count_tag + '_' + kunde_id + '_' + speise_nr).hide();
                }
            }

            /* if (kundentyp === 'stadt' && portionen_db > 0) {
             alert('nur eine speise');
             }*/
            // $('#' + loader).show();
            $.ajax({
                url: 'includes/save_portionen.php',
                type: 'post',
                data: {kunde_id: kunde_id, tag_str: tag, speise_nr: speise_nr, starttag: starttag, startmonat: startmonat, startjahr: startjahr, startts: startts, portionen: portionen, tag_ts: tag_ts, kundentyp: kundentyp},
                success: function (response) {
                    setTimeout(function () {
                        //   $('#'+loader).html('<img src="images/load.gif">');
                        //  $('#' + loader).html('<img src="images/saved_icon.png">').delay(2000);
                        $('#' + loader + ' img').fadeOut();

                        $('#portionen_in_db_' + focus_nr).load('includes/portionen_check.php', {kunde_id: kunde_id, tag_str: tag, speise_nr: speise_nr, starttag: starttag, startmonat: startmonat, startjahr: startjahr, startts: startts, portionen: portionen, kundentyp: kundentyp});

                        $('#portionen_in_db_' + focus_nr).delay(200).fadeIn();
                        $('#standardportionen_' + focus_nr).delay(200).fadeIn();
                    }, 500);




                    // $('#'+loader).delay(1000).html('');
                }
            });
        }

        $('.kompos').hide();
        $('.show_kompos').on('click', function (e) {
            e.preventDefault();
            $('.kompos').hide();
            var show = $(this).data('show');
            if ($('.' + show).is(':visible')) {
                $('.' + show).hide();
            } else {
                $('.' + show).show();
            }
        });
    });
</script>