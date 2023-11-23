
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
$fh = fopen('/is/htdocs/wp1055935_1F55KTNHIC/www/sbar/verwaltung_schwaben/logs/last_kf_import.log','r');
$info_import = '';
while ($line = fgets($fh)) {
  $info_import .= $line;
}
$info_arr = explode(';',$info_import);
fclose($fh);

//button zu kitafino stoßzeiten sperren


?>
<a class="action_links_btns action_links" style="padding: 10px 15px;float: right;border:2px solid red;" id="import_kf" href="index.php?action=cockpit&do=import_kitafino&cat=kitafino&kids=<?php echo $kitafino_ids ?>">kitafino Zahlen Importieren <br /><span style="font-size: 10px !important;">(zuletzt: <?php echo $info_arr[0] ?>)</span></a>

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
                echo '<li>' . $einrichtungsname ;

                    echo '<ul>';
            foreach ($fehler_zu_tagen as $datum_str => $meldungen) {
                echo '<li>' . $datum_str ;
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

        $('.loader_cover_cp').hide();
        $('.loader_cover_cp div').hide();

        $('#import_kf').on('click', function (e) {
              e.preventDefault();
            alert('Der Import der kitafino Zahlen wird gestartet. Vorgang nicht abbrechen oder Seite neu laden.');
            $('.loader_cover_cp').show();
            $('.loader_cover_cp div').show();
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