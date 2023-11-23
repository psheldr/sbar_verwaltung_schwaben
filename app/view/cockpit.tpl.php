
<div class="loader_cover_cp" style="position:fixed;top:0;left: 0;width:100%;padding:500px;z-index:10000; background-color: rgba(200,200,200,0.8)">
    <div  style="text-align:center;position:fixed;top:25%;right: 25%;width:50%;">
        <h4 style="">Daten werden geladen. Bitte haben Sie etwas Geduld und laden Sie die Seite NICHT neu!</h4>
        <img src="images/load.gif"  />
    </div>
</div>

<?php
//kunden suchen nach name
//ggf zusammengehörige Gruppen (Einzelkunden)
//
$staedtische_ids_obj = $kundeVerwaltung->findeAlleStaedtischenIds();
$staedtische_ids_arr = array();
$staedtische_ids = '';
foreach ($staedtische_ids_obj as $obj) {
    $staedtische_ids_arr[] = $obj->getid();
}
$staedtische_ids = implode('-', $staedtische_ids_arr);

$bio_ids_obj = $kundeVerwaltung->findeAlleBioIds();
$bio_ids_arr = array();
$bio_ids = '';
foreach ($bio_ids_obj as $obj) {
    $bio_ids_arr[] = $obj->getid();
}
$bio_ids = implode('-', $bio_ids_arr);

$std_ids_obj = $kundeVerwaltung->findeAlleStandardIds();
$std_ids_arr = array();
$std_ids = '';
foreach ($std_ids_obj as $obj) {
    $std_ids_arr[] = $obj->getid();
}
$std_ids = implode('-', $std_ids_arr);

$kitafino_ids_obj = $kundeVerwaltung->findeAlleKitafinoIds();
$kitafino_ids_arr = array();
$kitafino_ids = '';
foreach ($kitafino_ids_obj as $obj) {
    $kitafino_ids_arr[] = $obj->getid();
}
$kitafino_ids = implode('-', $kitafino_ids_arr);
?>

<div class="" style="margin-left: auto;margin-right:auto; width: 80%;">
    <h1>Kundensuche</h1>
    <p>Nach Einrichtungsname suchen. <br />Mehrere Suchwörter verfeinern Suche. (z.B.: "scharrer gs" oder "scharrer gs 4").<br />
        Mit Eintrag "<span style="color:green;font-weight:bold;">Alle Ergebnisse aufrufen</span>" werden alle aufgelisteten Kunden aufgerufen. Z.B. um alle Gruppen aufzurufen. (Beispiel: "scharrer gs").
    </p>
    <form>
        <input type="text" name="kundensuche" class="kundensuche" value="" style="width:50%;margin-left: auto;margin-right:auto;" placeholder="nach Kunden suchen" />
    </form>
    <br />
    <div>Alle aufrufen:
        <a class="action_links_btns action_links <?php
        if ($_REQUEST['cat'] == 'stadt') {
            echo 'active_action_links';
        }
        ?>" href="index.php?action=cockpit&cat=stadt&kids=<?php echo $staedtische_ids ?>">städt. Kunden <img src="images/nbg_icon.png" style="height:12px;" /></a>

        <a class="action_links_btns action_links <?php
        if ($_REQUEST['cat'] == 'bio') {
            echo 'active_action_links';
        }
        ?>" href="index.php?action=cockpit&cat=bio&kids=<?php echo $bio_ids ?>">Bio Kunden <img src="../images/biosiegel_small.jpg" style="height:12px;" /></a>

        <a class="action_links_btns action_links <?php
        if ($_REQUEST['cat'] == 'standard') {
            echo 'active_action_links';
        }
        ?>" href="index.php?action=cockpit&cat=standard&kids=<?php echo $std_ids ?>">Standard Kunden</a>

        <a class="action_links_btns action_links <?php
        if ($_REQUEST['cat'] == 'kitafino') {
            echo 'active_action_links';
        }
        ?>" href="index.php?action=cockpit&cat=kitafino&kids=<?php echo $kitafino_ids ?>">kitafino Kunden <img src="images/kitafino_icon.png" style="height:12px;" /></a>
    </div>
</div>
<style type="text/css">
    .error_ul li {
        margin-left: 10px;
    }
    .error_ul li li {
        margin-left: 20px;
    }
    .error_ul {
        border: 1px solid red;
    }
</style>

<?php
//read last import date
$tag_in_angezeigter_woche_ts2 = mktime(12, 0, 0, $monat_anzeigen, $tag_anzeigen, $jahr_anzeigen);
$wochentag_string = strftime('%a', $tag_in_angezeigter_woche_ts2);
switch ($wochentag_string) {
    case 'Sa':
        $start_tag_woche2 = date('d', $tag_in_angezeigter_woche_ts2 + 86400 * 2);
        $start_monat_woche2 = date('m', $tag_in_angezeigter_woche_ts2 + 86400 * 2);
        $start_jahr_woche2 = date('Y', $tag_in_angezeigter_woche_ts2 + 86400 * 2);
        break;
    case 'So':
        $start_tag_woche2 = date('d', $tag_in_angezeigter_woche_ts2 + 86400 * 1);
        $start_monat_woche2 = date('m', $tag_in_angezeigter_woche_ts2 + 86400 * 1);
        $start_jahr_woche2 = date('Y', $tag_in_angezeigter_woche_ts2 + 86400 * 1);
        break;
    case 'Mo':
        $start_tag_woche2 = date('d', $tag_in_angezeigter_woche_ts2 + 86400 * 0);
        $start_monat_woche2 = date('m', $tag_in_angezeigter_woche_ts2 + 86400 * 0);
        $start_jahr_woche2 = date('Y', $tag_in_angezeigter_woche_ts2 + 86400 * 0);
        break;
    case 'Di':
        $start_tag_woche2 = date('d', $tag_in_angezeigter_woche_ts2 - 86400 * 1);
        $start_monat_woche2 = date('m', $tag_in_angezeigter_woche_ts2 - 86400 * 1);
        $start_jahr_woche2 = date('Y', $tag_in_angezeigter_woche_ts2 - 86400 * 1);
        break;
    case 'Mi':
        $start_tag_woche2 = date('d', $tag_in_angezeigter_woche_ts2 - 86400 * 2);
        $start_monat_woche2 = date('m', $tag_in_angezeigter_woche_ts2 - 86400 * 2);
        $start_jahr_woche2 = date('Y', $tag_in_angezeigter_woche_ts2 - 86400 * 2);
        break;
    case 'Do':
        $start_tag_woche2 = date('d', $tag_in_angezeigter_woche_ts2 - 86400 * 3);
        $start_monat_woche2 = date('m', $tag_in_angezeigter_woche_ts2 - 86400 * 3);
        $start_jahr_woche2 = date('Y', $tag_in_angezeigter_woche_ts2 - 86400 * 3);
        break;
    case 'Fr':
        $start_tag_woche2 = date('d', $tag_in_angezeigter_woche_ts2 - 86400 * 4);
        $start_monat_woche2 = date('m', $tag_in_angezeigter_woche_ts2 - 86400 * 4);
        $start_jahr_woche2 = date('Y', $tag_in_angezeigter_woche_ts2 - 86400 * 4);
        break;
}
$ts_starttag_der_woche2 = mktime(12, 0, 0, $start_monat_woche2, $start_tag_woche2, $start_jahr_woche2);
$kw = date('W', $ts_starttag_der_woche2);
$fh = fopen('./logs/last_kf_import.log', 'r');
$info_lines = array();
while ($line = fgets($fh)) {
    $info_lines[] = explode(';', $line);
}
$info_arr_kws = array();
$info_string = '<span style="color: red;">Noch kein Import für KW' . $kw.'</span>';
$border_color = 'red';
foreach ($info_lines as $data) {
    $kw_log = $data[0];
    $log_str =  $kw_log . ' - ' . $data[3] . ' am ' . $data[1];
    $info_arr_kws[$kw_log] = $log_str;
    if ('KW' . $kw == $kw_log) {
        $info_string = $log_str;
        $border_color = 'green';
    }
}
fclose($fh);

?>

<?php if ($kitafino_ids == '') { ?> 
    <p style=" padding: 10px 15px;float: right;border:2px solid <?php echo $border_color ?>;">kitafino-Import: Es wurden noch keine kitafino Kunden angelegt.</p>
    <?php } else { ?>
<a class="action_links_btns action_links" style=" padding: 10px 15px;float: right;border:2px solid <?php echo $border_color ?>;" id="import_kf" href="index.php?action=cockpit&do=import_kitafino&cat=kitafino&starttag=<?php echo $start_tag_woche2 ?>&startmonat=<?php echo $start_monat_woche2 ?>&startjahr=<?php echo $start_jahr_woche2 ?>&kids=<?php echo $kitafino_ids ?>">kitafino Zahlen für KW <?php echo $kw ?> Importieren <br /><span style="font-size: 10px !important;">(zuletzt: <?php echo $info_string ?>)</span></a>

<?php } ?>
<!--
<p style=" padding: 10px 15px;float: right;border:2px solid <?php echo $border_color ?>;">Import vorrübergehend deaktiviert (bis ca. SA 22.01. 0 Uhr)</p>
 -->
<?php if ($_REQUEST['show_import_info']) { ?>
    <p class="success_text" style="float: right; margin-right: 20px;">
        <?php echo $info_string ?>
    </p>
    <?php
}
?>
<?php
/*  echo '<pre>';
  var_dump($kunden);
  echo '</pre>'; */
if (count($kunden)) {
    include 'view/speiseplaene.tpl.php';
} else {

    if ($_REQUEST['dev1'] == 1) {
        $fehler_check = checkeAufFehler($kundeVerwaltung, $standardportionenVerwaltung, $portionenaenderungVerwaltung);
        foreach ($fehler_check as $einrichtungsname => $fehler_zu_tagen) {
            echo '<ul class="error_ul">';
            echo '<li>' . $einrichtungsname;

            echo '<ul>';
            foreach ($fehler_zu_tagen as $datum_str => $meldungen) {
                echo '<li>' . $datum_str;
                echo '<ul>';

                foreach ($meldungen as $meldung) {
                    echo '<li>' . $meldung . '</li>';
                }
                echo '</ul>';
                echo '</li>';
            }
            echo '</ul>';
            echo '</li>';
            echo '</ul>';
        }
        /* echo '<pre>';
          var_dump($fehler_check);
          echo '</pre>'; */
    }
}
?>



<style type="text/css">
    .ui-autocomplete {
        z-index:1001 !important
            display: block !important;
    }
    .ui-autocomplete-input {
        background:url('../images/search-icon.gif') no-repeat right #fff !important;
        z-index:1001 !important
    }
    .ui-autocomplete-loading {
        background:url('../images/small_loader.gif') no-repeat right #fff !important;
    }
    .ui-menu-item {
        width: 100%;

    }
    .ui-menu {
        padding-top: 10px;
    }
    .ui-menu-item-wrapper {
        display: block;
        font-size: 14px;
        line-height: 20px;
    }
</style>
<script type="text/javascript">

    $(document).ready(function () {
        $('.loader_cover_cp').hide();
        $('.loader_cover_cp div').hide();


        $('#import_kf').on('click', function (e) {
            //  e.preventDefault();
            if (confirm('Soll der Import der kitafino Zahlen gestartet werden? \n Der Vorgang kann nicht abgebrochen werden.')) {
                $('.loader_cover_cp').show();
                $('.loader_cover_cp div').show();
            } else {
                e.preventDefault();

            }
        });
    });

    $(function () {
        $(".kundensuche").keypress(function (e) {
            var code = (e.keyCode ? e.keyCode : e.which);
            var show_all_url = $('');
            if (code == 13) { //Enter keycode
                e.preventDefault();
                return false;
            }
        });

        $(".kundensuche").each(function () {
            var $el = $(this);
            // var source_add = $el.attr('data-zahlungsid');
            // var go_to = $el.attr('data-goto');

            $el.autocomplete({
                autoFocus: true,
                source: "includes/kunden_list.php",
                html: true,
                minLength: 3,
                delay: 300,
                position: {
                    my: "right top",
                    at: "right bottom"
                },
                select: function (event, ui) {
                    window.location = ui.item.url;
                }
            }).focus(function () {
                //Use the below line instead of triggering keydown
                $(this).autocomplete("search");
            });
        });
    });
</script>
