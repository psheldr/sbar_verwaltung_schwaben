
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
        <?php if ($action != 'cockpit') { ?>
            <tr>
                <td style="background: none;border:none;">
                </td>
                <?php
                $speisen_strings = array();
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
                            <a class="action_links" target="_blank" href="index.php?action=bearbeite_tag&tag=<?php echo $tag ?>&monat=<?php echo $monat ?>&jahr=<?php echo $jahr ?>&starttag=<?php echo $start_tag_woche ?>&startmonat=<?php echo $start_monat_woche ?>&startjahr=<?php echo $start_jahr_woche ?>">Speisen bearbeiten</a>
                        <?php } else { ?>
                            <a class="action_links" target="_blank" href="index.php?action=bearbeite_tag&tag=<?php echo $tag ?>&monat=<?php echo $monat ?>&jahr=<?php echo $jahr ?>&starttag=<?php echo $start_tag_woche ?>&startmonat=<?php echo $start_monat_woche ?>&startjahr=<?php echo $start_jahr_woche ?>">Speisen festlegen</a>
                        <?php } ?>
                    </td>
                <?php } ?>
            </tr>
        <?php } ?>
    

        
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
                var inhalt_zweite_speise_stadt = $('.input_tag_' + kunde_id + '_' + count_tag + '_' + check_snr + ' span').html();
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