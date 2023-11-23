<a href="index.php?action=speiseplaene&starttag=<?php echo $starttag ?>&startmonat=<?php echo $startmonat ?>&startjahr=<?php echo $startjahr ?>" class="action_links"><<< Zurück zur Übersicht</a>

<h1>Speisen am <?php echo $_REQUEST['tag2'] . '.' . $_REQUEST['monat'] . '.' . $_REQUEST['jahr'] ?></h1>

<?php if (count($bestellungen) == 0) { ?>
    <p>Es wurden noch keine Speisen für diesen Tag festgelegt.</p>
    <p><a href="index.php?action=bearbeite_tag&tag=<?php echo $_REQUEST['tag2'] ?>&monat=<?php echo $_REQUEST['monat'] ?>&jahr=<?php echo $_REQUEST['jahr'] ?>&starttag=<?php echo $wochenstarttag ?>&ts=<?php echo $ts ?>" class="action_links">Jetzt Speisen festlegen.</a></p>
<?php } ?>


<a href="index.php?action=erzeuge_tagesmengenaufstellung&tag=<?php echo $_REQUEST['tag2'] ?>&monat=<?php echo $_REQUEST['monat'] ?>&jahr=<?php echo $_REQUEST['jahr'] ?>&starttag=<?php echo $starttag ?>&startmonat=<?php echo $startmonat ?>&startjahr=<?php echo $startjahr ?>" class="action_links">Tagesmengenübersicht</a><br /><br />


<a href="index.php?action=erzeuge_fahrerlisten&tag=<?php echo $_REQUEST['tag2'] ?>&monat=<?php echo $_REQUEST['monat'] ?>&jahr=<?php echo $_REQUEST['jahr'] ?>&starttag=<?php echo $starttag ?>&startmonat=<?php echo $startmonat ?>&startjahr=<?php echo $startjahr ?>" class="action_links">Fahrerlisten</a><br /><br />

<a href="index.php?action=erzeuge_sonderwunschliste&tag=<?php echo $_REQUEST['tag2'] ?>&monat=<?php echo $_REQUEST['monat'] ?>&jahr=<?php echo $_REQUEST['jahr'] ?>&starttag=<?php echo $starttag ?>&startmonat=<?php echo $startmonat ?>&startjahr=<?php echo $startjahr ?>" class="action_links">Sonderwünsche</a><br /><br />


<?php if ($_REQUEST['dev'] == 1) { ?>
    <a href="index.php?action=erzeuge_einrichtungsliste_xls&tag=<?php echo $_REQUEST['tag2'] ?>&monat=<?php echo $_REQUEST['monat'] ?>&jahr=<?php echo $_REQUEST['jahr'] ?>&starttag=<?php echo $starttag ?>&startmonat=<?php echo $startmonat ?>&startjahr=<?php echo $startjahr ?>" class="action_links">Boxenetiketten </a><br /><br />
<?php } ?>





<div style="width:48%;float:left;padding-right: 10px;border-right:2px solid #666;">
    <?php
    $sp_nr_check = 1;
    $speisen_vorhanden = array();
    $speisen_vorhanden[1] = true;
    $speisen_vorhanden[2] = true;
    $speisen_vorhanden[3] = true;
    $speisen_vorhanden[4] = true;
    foreach ($bestellungen as $bestellung) {
        $bestellung_has_speise_array = $bestellung_has_speiseVerwaltung->findeAnhandVonBestellungId($bestellung->getId());


        $speisen_tag_array = array();
        for ($s = 1; $s <= 4; $s++) {
            $speisen_tag_array[$s] = array(
                'speise_ids' => array(),
                'kitafino_info' => 0
            );
        }
        foreach ($bestellung_has_speise_array as $bestellung_has_speise) {
            $speise = $speiseVerwaltung->findeAnhandVonId($bestellung_has_speise->getSpeiseId());
            $speisen_tag_array[$bestellung_has_speise->getSpeiseNr()]['speise_ids'][$bestellung_has_speise->getSpeiseId()] = $speise->getBezeichnung();
            $speisen_tag_array[$bestellung_has_speise->getSpeiseNr()]['kitafino_info'] = $bestellung_has_speise->getKitafinoSpeiseNr();
        }


        foreach ($speisen_tag_array as $speise_nr => $speisen_bestellung) {
            $add_str = '';
            switch ($speise_nr) {
                case 3:
                    $add_str = ' - BIO und Stadt Menü 1';
                    break;
                case 4:
                    $add_str = ' - BIO und Stadt Menü 2';
                    break;
            }
            $speisen_vorhanden[$speise_nr] = true;
            ?>
            <?php if ($speise_nr == 3) { ?>
            </div>
            <div style="width:48%;float:left; padding-left: 10px;">
                <br />
                <a class="action_links" href="index.php?action=erzeuge_lieferscheine&tag=<?php echo $_REQUEST['tag2'] ?>&monat=<?php echo $_REQUEST['monat'] ?>&jahr=<?php echo $_REQUEST['jahr'] ?>&speisenr=<?php echo $speise_nr ?>">Lieferscheine <img style="max-width: 15px;" src="images/nbg_icon.png" /></a><br />
                <a class="action_links" href="index.php?action=erzeuge_lieferscheine&tag=<?php echo $_REQUEST['tag2'] ?>&monat=<?php echo $_REQUEST['monat'] ?>&jahr=<?php echo $_REQUEST['jahr'] ?>&speisenr=<?php echo $speise_nr ?>&qr=1">Lieferscheine mit QR-Code <img style="max-width: 15px;" src="images/nbg_icon.png" /></a><br />
            <?php } ?>
                
            <div style="border-bottom: 1px solid #ccc;font-weight:bold;margin-top: 15px;">Speise <?php echo $speise_nr . $add_str ?></div>

            <a href="index.php?action=erzeuge_einrichtungsliste_xls&speise_nr=<?php echo $speise_nr ?>&tag=<?php echo $_REQUEST['tag2'] ?>&monat=<?php echo $_REQUEST['monat'] ?>&jahr=<?php echo $_REQUEST['jahr'] ?>&starttag=<?php echo $starttag ?>&startmonat=<?php echo $startmonat ?>&startjahr=<?php echo $startjahr ?>" class="action_links">Boxenetiketten </a>

            <br />  <a style="color:red" href="index.php?action=erzeuge_einrichtungsliste_xls&test_prodsort=1&speise_nr=<?php echo $speise_nr ?>&tag=<?php echo $_REQUEST['tag2'] ?>&monat=<?php echo $_REQUEST['monat'] ?>&jahr=<?php echo $_REQUEST['jahr'] ?>&starttag=<?php echo $starttag ?>&startmonat=<?php echo $startmonat ?>&startjahr=<?php echo $startjahr ?>" class="action_links">TEST Einrichtungsliste Etiketten</a>

            <?php //if ($_REQUEST['test']) { ?>
                <br /><a style="color:green" href="index.php?action=erzeuge_einrichtungsliste_pdf_etiketten&test_prodsort=1&speise_nr=<?php echo $speise_nr ?>&tag=<?php echo $_REQUEST['tag2'] ?>&monat=<?php echo $_REQUEST['monat'] ?>&jahr=<?php echo $_REQUEST['jahr'] ?>&starttag=<?php echo $starttag ?>&startmonat=<?php echo $startmonat ?>&startjahr=<?php echo $startjahr ?>" class="action_links">[Einrichtungsliste Etiketten A4 Druck PDF (TEST)]</a>
            <?php //} ?>

            <br /><br />


            <?php
            foreach ($speisen_bestellung['speise_ids'] as $speise_id => $speise_str) {
                ?>
               <!--             <a href="index.php?action=erzeuge_tagesaufstellung_xls&tag=<?php echo $_REQUEST['tag2'] ?>&monat=<?php echo $_REQUEST['monat'] ?>&jahr=<?php echo $_REQUEST['jahr'] ?>&starttag=<?php echo $starttag ?>&startmonat=<?php echo $startmonat ?>&startjahr=<?php echo $startjahr ?>&speise_id=<?php echo $speise_id ?>&speise_nr=<?php echo $speise_nr ?>" class="action_links">XLS <strong style="color: #333;"><?php echo $speise_str ?></strong> </a><br />
                -->
                <a style="color:red;" href="index.php?action=erzeuge_tagesaufstellung_xls_test&test_prodsort=1&tag=<?php echo $_REQUEST['tag2'] ?>&monat=<?php echo $_REQUEST['monat'] ?>&jahr=<?php echo $_REQUEST['jahr'] ?>&starttag=<?php echo $starttag ?>&startmonat=<?php echo $startmonat ?>&startjahr=<?php echo $startjahr ?>&speise_id=<?php echo $speise_id ?>&speise_nr=<?php echo $speise_nr ?>" class="action_links">A4 Portionierliste <strong style="color: #333;"><?php echo $speise_str ?></strong> </a><br />
				
				<?php if ($_REQUEST['test']) { ?>
                <a style="color:red;" href="index.php?action=erzeuge_tagesaufstellung_xls_test_v2&test_prodsort=1&tag=<?php echo $_REQUEST['tag2'] ?>&monat=<?php echo $_REQUEST['monat'] ?>&jahr=<?php echo $_REQUEST['jahr'] ?>&starttag=<?php echo $starttag ?>&startmonat=<?php echo $startmonat ?>&startjahr=<?php echo $startjahr ?>&speise_id=<?php echo $speise_id ?>&speise_nr=<?php echo $speise_nr ?>" class="action_links">A4 Portionierliste (NEU) <strong style="color: #333;"><?php echo $speise_str ?></strong> </a><br />
 <?php } ?>
 

                            <!--<a href="index.php?action=erzeuge_tagesaufstellung_xls_etikettendruck&tag=<?php echo $_REQUEST['tag2'] ?>&monat=<?php echo $_REQUEST['monat'] ?>&jahr=<?php echo $_REQUEST['jahr'] ?>&starttag=<?php echo $starttag ?>&startmonat=<?php echo $startmonat ?>&startjahr=<?php echo $startjahr ?>&speise_id=<?php echo $speise_id ?>&spnr=<?php echo $speise_nr ?>" class="action_links">Etiketten <strong style="color: #333;"><?php echo $speise_str ?></strong> </a><br style="" />-->

                <a style="color:red;" href="index.php?action=erzeuge_tagesaufstellung_xls_etikettendruck&test_prodsort=1&tag=<?php echo $_REQUEST['tag2'] ?>&monat=<?php echo $_REQUEST['monat'] ?>&jahr=<?php echo $_REQUEST['jahr'] ?>&starttag=<?php echo $starttag ?>&startmonat=<?php echo $startmonat ?>&startjahr=<?php echo $startjahr ?>&speise_id=<?php echo $speise_id ?>&spnr=<?php echo $speise_nr ?>" class="action_links">kleine Etiketten <strong style="color: #333;"><?php echo $speise_str ?></strong> </a>

                <?php //if ($_REQUEST['test']) { ?>
                <br /><a style="color:green" href="index.php?action=erzeuge_tagesaufstellung_pdf_etiketten&test_prodsort=1&tag=<?php echo $_REQUEST['tag2'] ?>&monat=<?php echo $_REQUEST['monat'] ?>&jahr=<?php echo $_REQUEST['jahr'] ?>&starttag=<?php echo $starttag ?>&startmonat=<?php echo $startmonat ?>&startjahr=<?php echo $startjahr ?>&speise_id=<?php echo $speise_id ?>&spnr=<?php echo $speise_nr ?>" class="action_links">A4 ETIKETTEN <strong style="color: #333;"><?php echo $speise_str ?></strong> </a>
            <?php //} ?>
                
                <br style="margin-bottom: 10px; " />
                <?php
            }
        }
        ?>





        <?php /* if (!$speise2_vorhanden) { ?>
          <div class="error_text" style="border-bottom: 1px solid #ccc;margin-top:20px;">Speise 2</div>
          <p class="error_text" style="">Achtung: 2. Speise fehlt</p>
          <a href="index.php?action=bearbeite_tag&tag=<?php echo $_REQUEST['tag2'] ?>&monat=<?php echo $_REQUEST['monat'] ?>&jahr=<?php echo $_REQUEST['jahr'] ?>&starttag=<?php echo $starttag ?>&startmonat=<?php echo $startmonat ?>&startjahr=<?php echo $startjahr ?>">Speisen bearbeiten</a><br />
          <?php } */
        ?>
    <?php }
    ?>

</div>
<!--
<a  href="index.php?action=erzeuge_tagesmengenaufstellung&ts=<?php echo $ts ?>&wochenstarttag=<?php echo $wochenstarttag ?>">TAGESMENGEN</a>
-->      <br /><br />
<a style="font-size:18px;font-weight:bold;" href="index.php?action=bearbeite_tag&tag=<?php echo $_REQUEST['tag2'] ?>&monat=<?php echo $_REQUEST['monat'] ?>&jahr=<?php echo $_REQUEST['jahr'] ?>&starttag=<?php echo $starttag ?>&startmonat=<?php echo $startmonat ?>&startjahr=<?php echo $startjahr ?>">Speisen bearbeiten</a><br />

<?php
foreach ($bestellungen as $bestellung) {

    $gesamt_subarray = array();
    $gesamtmengen_array = array();
    $bestellung_has_speise_array = $bestellung_has_speiseVerwaltung->findeAnhandVonBestellungId($bestellung->getId());
    $c = 1;
    $sp_nr_check = 1;

    foreach ($bestellung_has_speise_array as $bestellung_has_speise) {

        $speise_nr_aktiv = $bestellung_has_speise->getSpeiseNr();

        if ($bestellung_has_speise->getSpeiseNr() > $sp_nr_check) {

            $speise2_vorhanden = true;
            $sp_nr_check = $bestellung_has_speise->getSpeiseNr();
        }

        $gesamtmenge_tag_speise = 0;
        $speise = $speiseVerwaltung->findeAnhandVonId($bestellung_has_speise->getSpeiseId());
        $speise_id = $speise->getId();

        $gesamtmenge_kunde = 0;
        foreach ($kunden as $kunde) {
            if ($kunde->getEinrichtungskategorieId() == 5 || $kunde->getEinrichtungskategorieId() == 6) {
                continue;
            }
            if (($kunde->getBioKunde() || $kunde->getStaedtischerKunde()) && $speise_nr_aktiv <= 2) {
                continue;
            }
            if (!$kunde->getBioKunde() && !$kunde->getStaedtischerKunde() && $speise_nr_aktiv > 2) {
                continue;
            }
            if ($kunde->getBioKunde() && $kunde->getAnzahlSpeisen() == 1 && $speise_nr_aktiv > 3) {
                continue;
            }

            if ($sp_nr_check == 2 && $kunde->getAnzahlSpeisen() == 1) {
                continue;
            }

            $einrichtungskategorie_id = $kunde->getEinrichtungskategorieId();
            $menge_pro_portion = $menge_pro_portionVerwaltung->findeAnhandVonSpeiseIdUndEinrichtungskategorieId($speise_id, $einrichtungskategorie_id);
            $bemerkung_zu_speise = $bemerkungzuspeiseVerwaltung->findeAnhandVonKundeIdUndSpeiseId($kunde->getId(), $speise_id);

            // $standardportionen = $standardportionenVerwaltung->findeAnhandVonKundenId($kunde->getId());
            //$portionenaenderung = $portionenaenderungVerwaltung->findeAnhandVonKundenIdUndWochenstartdatum($kunde->getId(), $starttag, $startmonat, $startjahr);


            $standardportionen = $standardportionenVerwaltung->findeAnhandVonKundenIdUndSpeiseNr($kunde->getId(), $sp_nr_check);
            $portionenaenderung = $portionenaenderungVerwaltung->findeAnhandVonKundenIdUndWochenstartdatum($kunde->getId(), $starttag, $startmonat, $startjahr, $sp_nr_check);

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
            //hier weitermachen! 

            $wochentag_string = strftime('%a', mktime(12, 0, 0, $monat, $tag, $jahr));
            if ($kunde->getStartdatum() > mktime(12, 0, 0, $monat, $tag, $jahr)) {
                $portionen_mo = 0;
            }
            if ($kunde->getStartdatum() > mktime(12, 0, 0, $monat, $tag, $jahr)) {
                $portionen_di = 0;
            }
            if ($kunde->getStartdatum() > mktime(12, 0, 0, $monat, $tag, $jahr)) {
                $portionen_mi = 0;
            }
            if ($kunde->getStartdatum() > mktime(12, 0, 0, $monat, $tag, $jahr)) {
                $portionen_do = 0;
            }
            if ($kunde->getStartdatum() > mktime(12, 0, 0, $monat, $tag, $jahr)) {
                $portionen_fr = 0;
            }
            switch ($wochentag_string) {
                case 'Mo':
                    $portionen = $portionen_mo;
                    if ($portionen_mo != $standardportionen->getPortionenMo()) {
                        $portionen_str .= '<sup>1</sup> (' . $standardportionen->getPortionenMo() . ')';
                    }
                    break;
                case 'Di':
                    $portionen = $portionen_di;
                    if ($portionen_di != $standardportionen->getPortionenDi()) {
                        $portionen_str .= '<sup>1</sup> (' . $standardportionen->getPortionenDi() . ')';
                    }
                    break;
                case 'Mi':
                    $portionen = $portionen_mi;
                    if ($portionen_mi != $standardportionen->getPortionenMi()) {
                        $portionen_str .= '<sup>1</sup> (' . $standardportionen->getPortionenMi() . ')';
                    }
                    break;
                case 'Do':
                    $portionen = $portionen_do;
                    if ($portionen_do != $standardportionen->getPortionenDo()) {
                        $portionen_str .= '<sup>1</sup> (' . $standardportionen->getPortionenDo() . ')';
                    }
                    break;
                case 'Fr':
                    $portionen = $portionen_fr;
                    if ($portionen_fr != $standardportionen->getPortionenFr()) {
                        $portionen_str .= '<sup>1</sup> (' . $standardportionen->getPortionenFr() . ')';
                    }
                    break;
            }
            $indi_faktor = $indifaktorVerwaltung->findeAnhandVonSpeiseIdUndKundeId($speise_id, $kunde->getId());
            if ($indi_faktor->getId()) {
                $faktor = $indi_faktor->getFaktor();
            } else {
                $faktor = 1;
            }
            $gesamtmenge_kunde = $portionen * ($menge_pro_portion->getMenge() * $faktor);

            if ($menge_pro_portion->getEinheit() === 'Blech') {
                $gesamtmenge_kunde = ceil($gesamtmenge_kunde * 2) / 2;
            }
            /*  if ($menge_pro_portion->getEinheit() === 'Flasche' || $menge_pro_portion->getEinheit() === 'Beutel') {
              $gesamtmenge_kunde = ceil($gesamtmenge_kunde);
              } */


            if ($menge_pro_portion->getEinheit() === 'Flasche' || $menge_pro_portion->getEinheit() === 'Beutel') {
                $komma = fmod($gesamtmenge_kunde, 1);
                if ($gesamtmenge_kunde < 1) {
                    $gesamtmenge_kunde = ceil($gesamtmenge_kunde);
                } else {
                    if ($komma < 0.4 && $komma != 0) {
                        //abrunden
                        $gesamtmenge_kunde = floor($gesamtmenge_kunde);
                    }
                    if ($komma >= 0.4) {
                        //abrunden
                        $gesamtmenge_kunde = ceil($gesamtmenge_kunde);
                    }
                }
            }

            $gesamtmenge_tag_speise += $gesamtmenge_kunde;
            ?>

        <?php } ?>

        <?php
        $gesamt_subarray = array();
        $gesamt_subarray[] = $speise->getBezeichnung();
        $gesamt_subarray[] = $gesamtmenge_tag_speise;
        $gesamt_subarray[] = $menge_pro_portion->getEinheit();
        $gesamtmengen_array[$sp_nr_check][] = $gesamt_subarray;



        $c++;
    }
}
?>
<?php
/* echo '<pre>';
  var_dump($gesamtmengen_array);
  echo '</pre>'; */
$_SESSION['gesamtmengen_array'] = $gesamtmengen_array;
?>


