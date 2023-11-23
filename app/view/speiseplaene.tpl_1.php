<?php
$wochenstarttag_ts = time();
$heute = mktime(0,0,0,$anzeige_monat,$anzeige_tag,$anzeige_jahr);

$wochentag_string = strftime('%a', $heute);

//testing
//$heute = mktime(0,0,0,2,25,2013);
//$wochentag_string = strftime('%a', $heute);
//testing

$wochenstarttage = array();
switch($wochentag_string) {
    case 'Sa':
        $wochenstarttage['woche1'] = $wochenstarttag_ts = $heute + 86400*2;
        break;
    case 'So':
        $wochenstarttage['woche1'] = $wochenstarttag_ts = $heute + 86400*1;
        break;
    case 'Mo':
        $wochenstarttage['woche1'] = $wochenstarttag_ts = $heute + 86400*0;
        break;
    case 'Di':
        $wochenstarttage['woche1'] = $wochenstarttag_ts = $heute - 86400*1;
        break;
    case 'Mi':
        $wochenstarttage['woche1'] = $wochenstarttag_ts = $heute - 86400*2;
        break;
    case 'Do':
        $wochenstarttage['woche1'] = $wochenstarttag_ts = $heute - 86400*3;
        break;
    case 'Fr':
        $wochenstarttage['woche1'] = $wochenstarttag_ts = $heute - 86400*4;
        break;
}
if (isset($_REQUEST['woche_mit_start_anzeigen'])) {
                $woche_mit_start_anzeigen = $_REQUEST['woche_mit_start_anzeigen'];
            } else {
                $woche_mit_start_anzeigen = $wochenstarttag_ts;
            }

$wochenstarttage['woche2'] = $folgewochestarttag_ts = $wochenstarttag_ts + 86400*7;
$wochenstarttage['woche3'] = $folgewoche2starttag_ts = $wochenstarttag_ts + 86400*14;
$wochenstarttage['woche4'] = $folgewoche3starttag_ts = $wochenstarttag_ts + 86400*21;
$wochenstarttage['woche5'] = $folgewoche4starttag_ts = $wochenstarttag_ts + 86400*28;
$wochenstarttage['woche0'] = $folgewoche0starttag_ts = $wochenstarttag_ts - 86400*7;
asort($wochenstarttage);
?>
<form method="post" action="index.php?action=speiseplaene&monat_anzeigen=<?php echo $_REQUEST['monat_anzeigen']?>&jahr_anzeigen=<?php echo $_REQUEST['jahr_anzeigen'] ?>">

    <select name="monat_anzeigen">
        <?php for($m = 1; $m <= 12; $m++) {
            switch($m) {
                case 1:
                    $monat = 'Januar';
                    break;
                case 2:
                    $monat = 'Februar';
                    break;
                case 3:
                    $monat = 'MÃ¤rz';
                    break;
                case 4:
                    $monat = 'April';
                    break;
                case 5:
                    $monat = 'Mai';
                    break;
                case 6:
                    $monat = 'Juni';
                    break;
                case 7:
                    $monat = 'Juli';
                    break;
                case 8:
                    $monat = 'August';
                    break;
                case 9:
                    $monat = 'September';
                    break;
                case 10:
                    $monat = 'Oktober';
                    break;
                case 11:
                    $monat = 'November';
                    break;
                case 12:
                    $monat = 'Dezember';
                    break;
            }
            ?>


        <option value="<?php echo $m ?>" <?php if($m == $anzeige_monat) {echo 'selected="selected"';} ?>><?php echo $monat ?></option>

        <?php } ?>
    </select>
    <select name="jahr_anzeigen">
        <?php for($j = 2012; $j <= date('Y'); $j++) { ?>
        <option value="<?php echo $j ?>" <?php if($j == $anzeige_jahr) {echo 'selected="selected"';} ?>><?php echo $j ?></option>
        <?php } ?>
    </select>
    <input type="submit" value="anzeigen" />
</form>
<p>
<?php foreach ($wochenstarttage as $wochenstarttag) { ?>
<a <?php if($wochenstarttag == $woche_mit_start_anzeigen) {echo 'class="active_action_links"';} else { ?>class="action_links"<?php } ?> href="index.php?action=speiseplaene&monat_anzeigen=<?php echo $anzeige_monat ?>&jahr_anzeigen=<?php echo $anzeige_jahr ?>&woche_mit_start_anzeigen=<?php echo $wochenstarttag ?>">Woche ab <?php echo strftime('%d.%m.%y', $wochenstarttag) ?></a><br />
<?php } ?>
</p>


<?php foreach($wochenstarttage as $wochenstarttag) {
    $dienstagts =$wochenstarttag+86400*1;
    $mittwochts =$wochenstarttag+86400*2;
    $donnerstagts =$wochenstarttag+86400*3;
    $freitagts =$wochenstarttag+86400*4;
    if ($woche_mit_start_anzeigen == $wochenstarttag) {


    ?>

<table border="1" class="speiseplan_tbl">
    <tr>
        <th colspan="2" <?php if($heute == $wochenstarttag) {echo 'class="heute_tag"';} ?>><a title="TagesÃ¼bersicht" href="index.php?action=uebersicht_tag&ts=<?php echo $wochenstarttag ?>&starttag=<?php echo $wochenstarttag ?>"><?php echo strftime('%a %d.%m.', $wochenstarttag) ?></a></th>
        <th colspan="2" <?php if($heute == $dienstagts) {echo 'class="heute_tag"';} ?>><a title="TagesÃ¼bersicht" href="index.php?action=uebersicht_tag&ts=<?php echo $dienstagts ?>&starttag=<?php echo $wochenstarttag ?>"><?php echo strftime('%a %d.%m.', $dienstagts) ?></a></th>
        <th colspan="2" <?php if($heute == $mittwochts) {echo 'class="heute_tag"';} ?>><a title="TagesÃ¼bersicht" href="index.php?action=uebersicht_tag&ts=<?php echo $mittwochts ?>&starttag=<?php echo $wochenstarttag ?>"><?php echo strftime('%a %d.%m.', $mittwochts) ?></a></th>
        <th colspan="2" <?php if($heute == $donnerstagts) {echo 'class="heute_tag"';} ?>><a title="TagesÃ¼bersicht" href="index.php?action=uebersicht_tag&ts=<?php echo $donnerstagts ?>&starttag=<?php echo $wochenstarttag ?>"><?php echo strftime('%a %d.%m.', $donnerstagts) ?></a></th>
        <th colspan="2" <?php if($heute == $freitagts) {echo 'class="heute_tag"';} ?>><a title="TagesÃ¼bersicht" href="index.php?action=uebersicht_tag&ts=<?php echo $freitagts ?>&starttag=<?php echo $wochenstarttag ?>"><?php echo strftime('%a %d.%m.', $freitagts) ?></a></th>
        <th ></th>
    </tr>
    <tr>
            <?php for ($i = 1; $i <= 5; $i++) {
                switch ($i) {
                    case 1:
                        $tag_ts = $wochenstarttag;
                        break;
                    case 2:
                        $tag_ts = $dienstagts;
                        break;
                    case 3:
                        $tag_ts = $mittwochts;
                        break;
                    case 4:
                        $tag_ts = $donnerstagts;
                        break;
                    case 5:
                        $tag_ts = $freitagts;
                        break;
                }
                ?>

        <td colspan="2">
                    <?php
                    $bestellung = $bestellungVerwaltung->findeAnhandVonTag($tag_ts);
                    if ($bestellung->getId() && count($bestellung_has_speiseVerwaltung->findeAnhandVonBestellungId($bestellung->getId())) > 0) {
                        $bestellung_has_speisen = $bestellung_has_speiseVerwaltung->findeAnhandVonBestellungId($bestellung->getId());
                        foreach ($bestellung_has_speisen as $bestellung_has_speise) {
                            $speise = $speiseVerwaltung->findeAnhandVonId($bestellung_has_speise->getSpeiseId()); ?>
            <strong><?php echo $speise->getBezeichnung() ?></strong> <br />
                        <?php } ?>

            <a class="action_links" href="index.php?action=bearbeite_tag&ts=<?php echo $tag_ts ?>&starttag=<?php echo $wochenstarttag ?>">Speisen bearbeiten</a>
                    <?php } else { ?>
            <a class="action_links" href="index.php?action=bearbeite_tag&ts=<?php echo $tag_ts ?>&starttag=<?php echo $wochenstarttag ?>">Speisen festlegen</a>
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
        foreach($kunden as $kunde) {
            $standardportionen = $standardportionenVerwaltung->findeAnhandVonKundenId($kunde->getId());
            $portionenaenderung = $portionenaenderungVerwaltung->findeAnhandVonKundenIdUndWochenstarttagts($kunde->getId(), $wochenstarttag);
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
            $gesamtportionen_mo += $portionen_mo;
            $gesamtportionen_di += $portionen_di;
            $gesamtportionen_mi += $portionen_mi;
            $gesamtportionen_do += $portionen_do;
            $gesamtportionen_fr += $portionen_fr;
            ?>
    <form method="post" action="index.php?action=speichere_portionenaenderung">
        <input type="hidden" name="id" value="<?php echo $portionenaenderung->getId() ?>" />
        <input type="hidden" name="wochenstarttagts" value="<?php echo $wochenstarttag ?>" />
        <input type="hidden" name="kunde_id" value="<?php echo $kunde->getId() ?>" />



        <tr>
            <td><?php echo $kunde->getName() ?>
            </td>
            <td>
                <input class="portionen_input" type="text" name="portionen_mo" value="<?php echo $portionen_mo ?>" />
                        <?php if($portionen_mo != $standardportionen->getPortionenMo()) { echo '<sup>1</sup> ('.$standardportionen->getPortionenMo().')'; } ?>

            </td>

            <td><?php echo $kunde->getName() ?></td>
            <td>
                <input class="portionen_input" type="text" name="portionen_di" value="<?php echo $portionen_di ?>" />
                        <?php if($portionen_di != $standardportionen->getPortionenDi()) { echo '<sup>1</sup> ('.$standardportionen->getPortionenDi().')'; } ?>
            </td>

            <td><?php echo $kunde->getName() ?></td>
            <td>
                <input class="portionen_input" type="text" name="portionen_mi" value="<?php echo $portionen_mi ?>" />
                        <?php if($portionen_mi != $standardportionen->getPortionenMi()) { echo '<sup>1</sup> ('.$standardportionen->getPortionenMi().')'; } ?>
            </td>

            <td><?php echo $kunde->getName() ?></td>
            <td>
                <input class="portionen_input" type="text" name="portionen_do" value="<?php echo $portionen_do ?>" />
                        <?php if($portionen_do != $standardportionen->getPortionenDo()) { echo '<sup>1</sup> ('.$standardportionen->getPortionenDo().')'; } ?>
            </td>

            <td><?php echo $kunde->getName() ?></td>
            <td>
                <input class="portionen_input" type="text" name="portionen_fr" value="<?php echo $portionen_fr ?>" />
                        <?php if($portionen_fr != $standardportionen->getPortionenFr()) { echo '<sup>1</sup> ('.$standardportionen->getPortionenFr().')'; } ?>
            </td>

            <td><input type="submit" value="" class="save_btn"/></td>
        </tr>
    </form>
        <?php } ?>

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

<?php }} ?>