<?php
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
<?php if ($_REQUEST['dev']) { ?>
    <a class="action_links" href="index.php">zu alter Ansicht</a>    
<?php } else { ?>
    <a class="action_links" href="index.php?dev=1">zu neuer kitafino Ansicht</a>    
<?php } ?>
<br style="clear:both;" />
<br style="clear:both;" />
<hr />
<br />

<form method="post" action="index.php?action=speiseplaene">

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
    <a class="action_links" href="index.php?action=speiseplaene&starttag=<?php echo $starttag_vorige_woche ?>&startmonat=<?php echo $startmonat_vorige_woche ?>&startjahr=<?php echo $startjahr_vorige_woche ?>"><<< zurück</a> |
    <a class="action_links" href="index.php?action=speiseplaene">aktuelle Woche</a> |
    <a class="action_links" href="index.php?action=speiseplaene&starttag=<?php echo $starttag_naechste_woche ?>&startmonat=<?php echo $startmonat_naechste_woche ?>&startjahr=<?php echo $startjahr_naechste_woche ?>">vor >>></a>
</p>
<a class="action_links" style="background:#666;color:#fff;border: 2px solid #ddd; padding: 3px;" href="index.php?action=erzeuge_wochenmengenaufstellung&starttag=<?php echo $_REQUEST['starttag'] ?>&startmonat=<?php echo $_REQUEST['startmonat'] ?>&startjahr=<?php echo $_REQUEST['startjahr'] ?>&speise_id=<?php echo $speise_id ?>">Erzeuge Wochenmengenaufstellung als Excel</a>
<?php
$wochenstarttag = $ts_starttag_der_woche;
$dienstagts = $wochenstarttag + 86400 * 1;
$mittwochts = $wochenstarttag + 86400 * 2;
$donnerstagts = $wochenstarttag + 86400 * 3;
$freitagts = $wochenstarttag + 86400 * 4;

$woche_ts_array = array($wochenstarttag, $dienstagts, $mittwochts, $donnerstagts);

$tage_ts_woche_array = array($wochenstarttag, $dienstagts, $mittwochts, $donnerstagts, $freitagts);
?>
<form method="post" action="index.php?action=speichere_portionenaenderung">
    <table border="1" class="speiseplan_tbl">
        <tr>
            <th colspan="2" <?php
            if (date('m') == date('m', $wochenstarttag) && date('d') == date('d', $wochenstarttag) && date('Y') == date('Y', $wochenstarttag)) {
                echo 'class="heute_tag"';
            }
            ?>><a title="Tagesübersicht" href="index.php?action=uebersicht_tag&tag2=<?php echo date('d', $wochenstarttag) ?>&monat=<?php echo date('m', $wochenstarttag) ?>&jahr=<?php echo date('Y', $wochenstarttag) ?>&starttag=<?php echo date('d', $wochenstarttag) ?>&startmonat=<?php echo date('m', $wochenstarttag) ?>&startjahr=<?php echo date('Y', $wochenstarttag) ?>"><?php echo strftime('%a %d.%m.', $wochenstarttag) ?></a></th>

            <th colspan="2" <?php
            if (date('m') == date('m', $dienstagts) && date('d') == date('d', $dienstagts) && date('Y') == date('Y', $dienstagts)) {
                echo 'class="heute_tag"';
            }
            ?>><a title="Tagesübersicht" href="index.php?action=uebersicht_tag&tag2=<?php echo date('d', $dienstagts) ?>&monat=<?php echo date('m', $dienstagts) ?>&jahr=<?php echo date('Y', $dienstagts) ?>&starttag=<?php echo date('d', $wochenstarttag) ?>&startmonat=<?php echo date('m', $wochenstarttag) ?>&startjahr=<?php echo date('Y', $wochenstarttag) ?>"><?php echo strftime('%a %d.%m.', $dienstagts) ?></a></th>
            <th colspan="2" <?php
            if (date('m') == date('m', $mittwochts) && date('d') == date('d', $mittwochts) && date('Y') == date('Y', $mittwochts)) {
                echo 'class="heute_tag"';
            }
            ?>><a title="Tagesübersicht" href="index.php?action=uebersicht_tag&tag2=<?php echo date('d', $mittwochts) ?>&monat=<?php echo date('m', $mittwochts) ?>&jahr=<?php echo date('Y', $mittwochts) ?>&starttag=<?php echo date('d', $wochenstarttag) ?>&startmonat=<?php echo date('m', $wochenstarttag) ?>&startjahr=<?php echo date('Y', $wochenstarttag) ?>"><?php echo strftime('%a %d.%m.', $mittwochts) ?></a></th>
            <th colspan="2" <?php
            if (date('m') == date('m', $donnerstagts) && date('d') == date('d', $donnerstagts) && date('Y') == date('Y', $donnerstagts)) {
                echo 'class="heute_tag"';
            }
            ?>><a title="Tagesübersicht" href="index.php?action=uebersicht_tag&tag2=<?php echo date('d', $donnerstagts) ?>&monat=<?php echo date('m', $donnerstagts) ?>&jahr=<?php echo date('Y', $donnerstagts) ?>&starttag=<?php echo date('d', $wochenstarttag) ?>&startmonat=<?php echo date('m', $wochenstarttag) ?>&startjahr=<?php echo date('Y', $wochenstarttag) ?>"><?php echo strftime('%a %d.%m.', $donnerstagts) ?></a></th>
            <th colspan="2" <?php
            if (date('m') == date('m', $freitagts) && date('d') == date('d', $freitagts) && date('Y') == date('Y', $freitagts)) {
                echo 'class="heute_tag"';
            }
            ?>><a title="Tagesübersicht" href="index.php?action=uebersicht_tag&tag2=<?php echo date('d', $freitagts) ?>&monat=<?php echo date('m', $freitagts) ?>&jahr=<?php echo date('Y', $freitagts) ?>&starttag=<?php echo date('d', $wochenstarttag) ?>&startmonat=<?php echo date('m', $wochenstarttag) ?>&startjahr=<?php echo date('Y', $wochenstarttag) ?>"><?php echo strftime('%a %d.%m.', $freitagts) ?></a></th>
            <th ></th>
        </tr>
        <tr>
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

                    if ($bestellung->getId() && count($bestellung_has_speiseVerwaltung->findeAnhandVonBestellungId($bestellung->getId())) > 0) {
                        $bestellung_has_speisen = $bestellung_has_speiseVerwaltung->findeAnhandVonBestellungId($bestellung->getId());
                        $sp_nr_check = 1;
                        $speise2_vorhanden = false;

                        $speisen_kitafino_info = array();



                        $cc = 0;

                        foreach ($bestellung_has_speisen as $bestellung_has_speise) {
                            $speisen_kitafino_info[$bestellung_has_speise->getSpeiseNr()] = $bestellung_has_speise->getKitafinoSpeiseNr();
                            if ($cc == 0) {
                                ?>
                                <div style="border-bottom: 1px solid #ccc;">Speise 1
                                    <?php
                                    if ($speisen_kitafino_info[1] == 1) {
                                        echo '<span class="success_text sup">[kitafino Fleischgericht (Sp 1)]</span>';
                                    } elseif ($speisen_kitafino_info[1] == 0) {
                                        echo '<span class="error_text sup">[kitafino Zuordnung fehlt!]</span>';
                                    }
                                    ?>
                                </div>
                                <div style="height:140px;">

                                    <?php
                                }
                                $cc++;



                                if ($bestellung_has_speise->getSpeiseNr() > $sp_nr_check) {
                                    $speise2_vorhanden = true;
                                    ?>
                                </div><div style="border-bottom: 1px solid #ccc;">Speise 2
                                    <?php
                                    if ($speisen_kitafino_info[2] == 1) {
                                        echo '<span class="success_text sup">[kitafino Fleischgericht (Sp 1)]</span>';
                                    } elseif ($speisen_kitafino_info[2] == 0) {
                                        echo '<span class="error_text sup">[kitafino Zuordnung fehlt!]</span>';
                                    }
                                    ?>
                                </div><div style="height:140px;">
                                    <?php
                                    $sp_nr_check = $bestellung_has_speise->getSpeiseNr();
                                }
                                $speise = $speiseVerwaltung->findeAnhandVonId($bestellung_has_speise->getSpeiseId());
                                ?>
                                <strong style="font-size: 11px;"><?php echo $speise->getBezeichnung();
                                ?></strong> <br />
                                <?php
                            }
                            if (!$speise2_vorhanden) {
                                ?>
                            </div><div style="border-bottom: 1px solid #ccc;">Speise 2</div><div style="height:140px;">
                                <div class="error_text" style="border-top: 1px solid #ccc; padding: 5px; color: #fff; background: red;">Achtung: 2. Speise fehlt</div>
                            <?php }
                            ?>

                        </div>
                        <a class="action_links" href="index.php?action=bearbeite_tag&tag=<?php echo $tag ?>&monat=<?php echo $monat ?>&jahr=<?php echo $jahr ?>&starttag=<?php echo $start_tag_woche ?>&startmonat=<?php echo $start_monat_woche ?>&startjahr=<?php echo $start_jahr_woche ?>">Speisen bearbeiten</a>
                    <?php } else { ?>
                        <a class="action_links" href="index.php?action=bearbeite_tag&tag=<?php echo $tag ?>&monat=<?php echo $monat ?>&jahr=<?php echo $jahr ?>&starttag=<?php echo $start_tag_woche ?>&startmonat=<?php echo $start_monat_woche ?>&startjahr=<?php echo $start_jahr_woche ?>">Speisen festlegen</a>
                    <?php } ?>
                </td>
            <?php } ?>
            <td rowspan="2"></td>
        </tr>
        <tr class="th2">
            <th>Kunde</th>
            <th>Port.</th>

            <th>Kunde</th>
            <th>Port.</th>

            <th>Kunde</th>
            <th>Port.</th>

            <th>Kunde</th>
            <th>Port.</th>

            <th>Kunde</th>
            <th>Port.</th>
        </tr>
        <?php
        $gesamtportionen_mo = 0;
        $gesamtportionen_di = 0;
        $gesamtportionen_mi = 0;
        $gesamtportionen_do = 0;
        $gesamtportionen_fr = 0;





        if ($_REQUEST['dev']) {
            $kunden_kitafino = $kundeVerwaltung->findeAlleMitKundennummerKitafino();
            $bestellzahlen = ermittleZahlenZuKundeWoche($tage_ts_woche_array, $kunden_kitafino);

            /*
              if ($_REQUEST['dev2']) {
              echo '<pre>';
              var_dump($bestellzahlen);
              echo '</pre>';
              } */


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

                        if ($kunde->getAnzahlSpeisen() > 1) {

                            $bestellung = $bestellungVerwaltung->findeAnhandVonTagMonatJahr($d, $m, $y);


                            if ($bestellung->getId()) {

                                $bestellung_has_speisen = $bestellung_has_speiseVerwaltung->findeAnhandVonBestellungIdUndSpeiseNr($bestellung->getId(), $speise_nr);
                            }
                            //$bestellung_has_speisen2 = $bestellung_has_speiseVerwaltung->findeAnhandVonBestellungIdUndSpeiseNr($bestellung->getId(), 2);


                            if (count($bestellung_has_speisen) && $bestellung_has_speisen[0]->getKitafinoSpeiseNr()) {
                                //  var_dump($pid, $d, $m, $y, 'Nr sbar: ' . $speise_nr, $bestellungen_menunr);
                                // var_dump('Nr kf ' . $bestellung_has_speisen[0]->getKitafinoSpeiseNr());
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
        /* echo '<pre>';
          var_dump($portionen_tage);
          echo '</pre>'; */
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
        foreach ($kunden as $kunde) {



            $highlight_class = '';
            if ($rowcount % 2 == 0) {
                $highlight_class = 'highlight_row';
            }
            if ($kunde->getEinrichtungskategorieId() == 5) {
                $highlight_class .= ' tourtrenner';
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


            if ($portionenaenderung->getId()) {
                $portionen_mo = $portionenaenderung->getPortionenMo();
                $portionen_di = $portionenaenderung->getPortionenDi();
                $portionen_mi = $portionenaenderung->getPortionenMi();
                $portionen_do = $portionenaenderung->getPortionenDo();
                $portionen_fr = $portionenaenderung->getPortionenFr();
                $aenderung = true;
            } else {
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

            if ($portionenaenderung2->getId()) {
                $portionen2_mo = $portionenaenderung2->getPortionenMo();
                $portionen2_di = $portionenaenderung2->getPortionenDi();
                $portionen2_mi = $portionenaenderung2->getPortionenMi();
                $portionen2_do = $portionenaenderung2->getPortionenDo();
                $portionen2_fr = $portionenaenderung2->getPortionenFr();
                $aenderung2 = true;
            } else {
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
                $portionen4_mo = $standardportionen4->getPortionenMo();
                $portionen4_di = $standardportionen4->getPortionenDi();
                $portionen4_mi = $standardportionen4->getPortionenMi();
                $portionen4_do = $standardportionen4->getPortionenDo();
                $portionen4_fr = $standardportionen4->getPortionenFr();
                $aenderung4 = false;
            }


            $echo_mo = $echo_di = $echo_mi = $echo_do = $echo_fr = '';
            if ($kunde->getStartdatum() > $wochenstarttag) {
                $echo_mo = '<p style="color: red;font-size: 10px;">Startet am ' . strftime('%d.%m.%Y', $kunde->getStartdatum()) . '</p>';
                $portionen_mo = 0;
                $portionen2_mo = 0;
                $portionen3_mo = 0;
                $portionen4_mo = 0;
            }
            if ($kunde->getStartdatum() > $dienstagts) {
                $echo_di = '<p style="color: red;font-size: 10px;">Startet am ' . strftime('%d.%m.%Y', $kunde->getStartdatum()) . '</p>';
                $portionen_di = 0;
                $portionen2_di = 0;
                $portionen3_di = 0;
                $portionen4_di = 0;
            }
            if ($kunde->getStartdatum() > $mittwochts) {
                $echo_mi = '<p style="color: red;font-size: 10px;">Startet am ' . strftime('%d.%m.%Y', $kunde->getStartdatum()) . '</p>';
                $portionen_mi = 0;
                $portionen2_mi = 0;
                $portionen3_mi = 0;
                $portionen4_mi = 0;
            }
            if ($kunde->getStartdatum() > $donnerstagts) {
                $echo_do = '<p style="color: red;font-size: 10px;">Startet am ' . strftime('%d.%m.%Y', $kunde->getStartdatum()) . '</p>';
                $portionen_do = 0;
                $portionen2_do = 0;
                $portionen3_do = 0;
                $portionen4_do = 0;
            }
            if ($kunde->getStartdatum() > $freitagts) {
                $echo_fr = '<p style="color: red;font-size: 10px;">Startet am ' . strftime('%d.%m.%Y', $kunde->getStartdatum()) . '</p>';
                $portionen_fr = 0;
                $portionen2_fr = 0;
                $portionen3_fr = 0;
                $portionen4_fr = 0;
            }
            $gesamtportionen_mo += $portionen_mo;
            $gesamtportionen_di += $portionen_di;
            $gesamtportionen_mi += $portionen_mi;
            $gesamtportionen_do += $portionen_do;
            $gesamtportionen_fr += $portionen_fr;

            if ($kunde->getAnzahlSpeisen() > 1) {
                $gesamtportionen_mo += $portionen2_mo;
                $gesamtportionen_di += $portionen2_di;
                $gesamtportionen_mi += $portionen2_mi;
                $gesamtportionen_do += $portionen2_do;
                $gesamtportionen_fr += $portionen2_fr;
            }

            $portionen_array = array($portionen_mo, $portionen_di, $portionen_mi, $portionen_do, $portionen_fr);
            $portionen2_array = array($portionen2_mo, $portionen2_di, $portionen2_mi, $portionen2_do, $portionen2_fr);
            $portionen3_array = array($portionen3_mo, $portionen3_di, $portionen3_mi, $portionen3_do, $portionen3_fr);
            $portionen4_array = array($portionen4_mo, $portionen4_di, $portionen4_mi, $portionen4_do, $portionen4_fr);
            if ($_REQUEST['dev']) {
                /* echo '<pre>';     
                  var_dump($kunde->getAnzahlSpeisen(),$gesamtportionen_di, $gesamtportionen_di);
                  echo '</pre>'; */
            }

            $ccc = 0;
            foreach ($tage_ts_woche_array as $tag_ts_check) {
                $check_auf_bestellung = $bestellungVerwaltung->findeAnhandVonTagMonatJahr(date('d', $tag_ts_check), date('m', $tag_ts_check), date('Y', $tag_ts_check));
                //var_dump($check_auf_bestellung);
                if ($check_auf_bestellung->getId()) {
                    $abrechnungstag_check = $abrechnungstagVerwaltung->findeAnhandVonKundeIdUndTagMonatJahr($kunde->getId(), date('d', $tag_ts_check), date('m', $tag_ts_check), date('Y', $tag_ts_check));

                    if (!$abrechnungstag_check->getId()) {
                        $bestellung_check = $bestellungVerwaltung->findeAnhandVonTagMonatJahr(date('d', $tag_ts_check), date('m', $tag_ts_check), date('Y', $tag_ts_check));

                        $bestellung_has_speisen_check = $bestellung_has_speiseVerwaltung->findeAnhandVonBestellungIdUndSpeiseNr($bestellung_check->getId(), 1);
                        $bestellung_has_speisen_check2 = $bestellung_has_speiseVerwaltung->findeAnhandVonBestellungIdUndSpeiseNr($bestellung_check->getId(), 2);
                        $bestellung_has_speisen_check3 = $bestellung_has_speiseVerwaltung->findeAnhandVonBestellungIdUndSpeiseNr($bestellung_check->getId(), 3);
                        $bestellung_has_speisen_check4 = $bestellung_has_speiseVerwaltung->findeAnhandVonBestellungIdUndSpeiseNr($bestellung_check->getId(), 4);
                        if (count($bestellung_has_speisen_check) > 0 && ($kunde->getStartdatum() <= $tag_ts_check || !$kunde->getStartdatum())) {
                            $speisen_ids_array = array();
                            $speisen2_ids_array = array();
                            $speisen3_ids_array = array();
                            $speisen4_ids_array = array();
                            foreach ($bestellung_has_speisen_check as $speisen_ids_aus_best) {
                                $speisen_ids_array[] = $speisen_ids_aus_best->getSpeiseId();
                            }
                            foreach ($bestellung_has_speisen_check2 as $speisen2_ids_aus_best) {
                                $speisen2_ids_array[] = $speisen2_ids_aus_best->getSpeiseId();
                            }
                            foreach ($bestellung_has_speisen_check3 as $speisen3_ids_aus_best) {
                                $speisen3_ids_array[] = $speisen3_ids_aus_best->getSpeiseId();
                            }
                            foreach ($bestellung_has_speisen_check4 as $speisen4_ids_aus_best) {
                                $speisen4_ids_array[] = $speisen4_ids_aus_best->getSpeiseId();
                            }

                            $daten_abrechnungstag['kunde_id'] = $kunde->getId();
                            $daten_abrechnungstag['tag'] = $tag_ts_check;
                            $daten_abrechnungstag['portionen'] = $portionen_array[$ccc];
                            $daten_abrechnungstag['speisen_ids'] = implode(', ', $speisen_ids_array);
                            $daten_abrechnungstag['tag2'] = date('d', $tag_ts_check);
                            $daten_abrechnungstag['monat'] = date('m', $tag_ts_check);
                            $daten_abrechnungstag['jahr'] = date('Y', $tag_ts_check);
                            $neuer_abrechnungstag = new Abrechnungstag($daten_abrechnungstag);
                            $abrechnungstagVerwaltung->speichere($neuer_abrechnungstag);

                            if ($kunde->getAnzahlSpeisen() > 1 && $kunde->getBioKunde() == 0) {
                                $daten_abrechnungstag2['kunde_id'] = $kunde->getId();
                                $daten_abrechnungstag2['tag'] = $tag_ts_check;
                                $daten_abrechnungstag2['portionen'] = $portionen2_array[$ccc];
                                $daten_abrechnungstag2['speisen_ids'] = implode(', ', $speisen2_ids_array);
                                $daten_abrechnungstag2['tag2'] = date('d', $tag_ts_check);
                                $daten_abrechnungstag2['monat'] = date('m', $tag_ts_check);
                                $daten_abrechnungstag2['jahr'] = date('Y', $tag_ts_check);
                                $daten_abrechnungstag2['speise_nr'] = 2;
                                $neuer_abrechnungstag2 = new Abrechnungstag($daten_abrechnungstag2);
                                $abrechnungstagVerwaltung->speichere($neuer_abrechnungstag2);
                            }

                            if ($kunde->getBioKunde() || $kunde->getStaedtischerKunde()) {
                                $daten_abrechnungstag3['kunde_id'] = $kunde->getId();
                                $daten_abrechnungstag3['tag'] = $tag_ts_check;
                                $daten_abrechnungstag3['portionen'] = $portionen3_array[$ccc];
                                $daten_abrechnungstag3['speisen_ids'] = implode(', ', $speisen3_ids_array);
                                $daten_abrechnungstag3['tag2'] = date('d', $tag_ts_check);
                                $daten_abrechnungstag3['monat'] = date('m', $tag_ts_check);
                                $daten_abrechnungstag3['jahr'] = date('Y', $tag_ts_check);
                                $daten_abrechnungstag3['speise_nr'] = 3;
                                $daten_abrechnungstag3 = new Abrechnungstag($daten_abrechnungstag3);
                                $abrechnungstagVerwaltung->speichere($neuer_abrechnungstag3);
                            }

                            if ($kunde->getAnzahlSpeisen() > 1) {
                                $daten_abrechnungstag4['kunde_id'] = $kunde->getId();
                                $daten_abrechnungstag4['tag'] = $tag_ts_check;
                                $daten_abrechnungstag4['portionen'] = $portionen4_array[$ccc];
                                $daten_abrechnungstag4['speisen_ids'] = implode(', ', $speisen4_ids_array);
                                $daten_abrechnungstag4['tag2'] = date('d', $tag_ts_check);
                                $daten_abrechnungstag4['monat'] = date('m', $tag_ts_check);
                                $daten_abrechnungstag4['jahr'] = date('Y', $tag_ts_check);
                                $daten_abrechnungstag4['speise_nr'] = 4;
                                $neuer_abrechnungstag4 = new Abrechnungstag($daten_abrechnungstag4);
                                $abrechnungstagVerwaltung->speichere($neuer_abrechnungstag4);
                            }
                        }
                    }
                    $ccc++;
                }
            }
            ?>


            <input type="hidden" name="wochenstarttagts[]" value="<?php echo $wochenstarttag ?>" />
            <input type="hidden" name="starttag[]" value="<?php echo $start_tag_woche ?>" />
            <input type="hidden" name="startmonat[]" value="<?php echo $start_monat_woche ?>" />
            <input type="hidden" name="startjahr[]" value="<?php echo $start_jahr_woche ?>" />
            <input type="hidden" name="kunde_id[]" value="<?php echo $kunde->getId() ?>" />
            <?php
            $display = 'none';
            if ($kunde->getStaedtischerKunde() == 0 && $kunde->getBioKunde() == 0) {
                $display = 'block';
                ?>
                <?php include 'includes/portionen1.php' ?>
            <?php } ?> 




            <?php if ($kunde->getAnzahlSpeisen() > 1 && $kunde->getStaedtischerKunde() == 0 && $kunde->getBioKunde() == 0) { ?>
                <?php include 'includes/portionen2.php' ?>
            <?php } ?> 

            <?php if ($kunde->getStaedtischerKunde() == 1 || $kunde->getBioKunde() == 1) { ?>
                <?php include 'includes/portionen3.php' ?>
            <?php } ?> 
            <?php if ($kunde->getStaedtischerKunde() == 1 || ($kunde->getBioKunde() == 1 && $kunde->getAnzahlSpeisen() > 1)) { ?>
                <?php include 'includes/portionen4.php' ?>
            <?php } ?> 

                                                                                                                                                               <!--<tr>
                                                                                                                                                                                    <td colspan="10"><?php ?></td>
                                                                                                                                                                                </tr>-->
        <?php }
        ?>


        <tr class="gesamt_tr">
            <td>Gesamt</td>
            <td><?php echo $gesamtportionen_mo ?></td>

            <td>Gesamt</td>
            <td><?php echo $gesamtportionen_di ?></td>

            <td>Gesamt</td>
            <td><?php echo $gesamtportionen_mi ?></td>

            <td>Gesamt</td>
            <td><?php echo $gesamtportionen_do ?></td>

            <td>Gesamt</td>
            <td><?php echo $gesamtportionen_fr ?></td>

            <td>&nbsp;</td>
        </tr>
        <tr>
            <td colspan="10" style="border: none;">
                <sup>1</sup>abweichend von Standardmenge
            </td>
        </tr>
    </table>
</form>
<?php var_dump($count_queries); ?>