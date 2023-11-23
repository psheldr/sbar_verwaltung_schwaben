<?php
if (isset($_REQUEST['resetsort'])) {
    unset($_SESSION['sort']);
    unset($_SESSION['sort_dir']);
}
if (isset($_REQUEST['amtid']) && $_REQUEST['amtid']) {
    $traeger_selected = $sozialamtVerwaltung->findeAnhandVonId($_REQUEST['amtid']);
    $amtids_arr = array($_REQUEST['amtid']);
    //  $benutzer_zu_sozialamt = $benutzerHasSozialAmtVerwaltung->findeAlleZuSozialamtIds($amtids_arr);
    $neues_amt = $sozialamtVerwaltung->findeAnhandVonId($_REQUEST['amtid']);
} else {
    $neues_amt = new Traeger($_SESSION['neues_amt_request']);
}

if (!isset($_REQUEST['sort']) && !isset($_SESSION['sort'])) {

    $_SESSION['sort'] = 'bezeichnung';
}
if (!isset($_REQUEST['sort_dir']) && !isset($_SESSION['sort_dir'])) {

    $_SESSION['sort_dir'] = 'ASC';
}

if (isset($_REQUEST['sort'])) {
    $_SESSION['sort'] = $_REQUEST['sort'];
}
if (isset($_REQUEST['sort_dir'])) {
    $_SESSION['sort_dir'] = $_REQUEST['sort_dir'];
}
$sozialaemter = $aemter = $sozialamtVerwaltung->findeAlle($_SESSION['sort'], $_SESSION['sort_dir']);

$sozialaemter_list = $sozialamtVerwaltung->findeAlle();
?>

<div class="column small-12">     

 <br /> <br />
    <a class="action_links action_links_btns" href="index.php?action=traeger&do=new">Neuen RE-Empfänger/Sozialamt anlegen <i class="fi-plus size-21"></i></a>     <br /> <br />
    <form method="post" action="index.php?action=traeger&do=new">

        <div class="column small-20">
            <label>
                Nach Sozialamt suchen...
                <input type="text" id="aemter_search_input" value="" name="aemter_search_input" />
            </label>            
            <label class="small-15 left">
                <select name="amtid" id="" required="">
                    <option value="0">
                        --- Amt wählen ---
                    </option>
                    <?php foreach ($sozialaemter_list as $traeger) { ?>
                        <option <?php
                        if ($traeger->getId() == $_REQUEST['amtid']) {
                            echo 'selected';
                        }
                        ?> value="<?php echo $traeger->getId() ?>">
                                <?php echo $traeger->getBezeichnung() ?>

                        </option>
                    <?php } ?>
                </select> 
            </label>
            <button class="button" type="submit">
                aufrufen
            </button>
        </div>


    </form>


    <div style="width: 45%;  float: left; padding: 10px;">
        <table style="width: 100%;">
            <!--<tr>
                <td colspan="4">
            <?php
            ?>
                    <a href="index.php?action=export_traeger&sort=<?php echo $_SESSION['sort'] ?>&sort_dir=<?php echo $_SESSION['sort_dir'] ?>">als XLS exportieren</a>
                </td>
            </tr>-->
            <tr>
                <th class="small-2">ID 
                    <a href="index.php?action=traeger&sort_dir=ASC&sort=id"><i class="fi-arrow-up"></i> <a href="index.php?action=traeger&sort_dir=DESC&sort=id"><i class="fi-arrow-down"></i></a>
                </th>
                <th style="width: 50%;">
                    Amt
                    <a href="index.php?action=traeger&sort_dir=ASC&sort=bezeichnung"><i class="fi-arrow-up"></i></a> <a href="index.php?action=traeger&sort_dir=DESC&sort=bezeichnung"><i class="fi-arrow-down"></i></a>

                </th>
                <th>Kunden</th>
                <th class="small-2">&nbsp;&nbsp;&nbsp;</th>
            </tr>
            <?php
            foreach ($sozialaemter_list as $amt) {
                $kunden_zu_amt = $kundeVerwaltung->findeAlleZuTraegerId($amt->getId());
                $amt_gehoert_zu_kunden = count($kunden_zu_amt);
//Kunden zu Träger ermitteln
                ?>
                <tr>
                    <td>
                        <?php echo $amt->getId(); ?>
                    </td>
                    <td>
                        <?php echo $amt->getBezeichnung(); ?>
                    </td>
                    <td>
                        <?php if ($amt_gehoert_zu_kunden > 0) { ?>
                        <a href="index.php?action=traeger&do=show_kunden&amtid=<?php echo $amt->getId()?>"><?php echo $amt_gehoert_zu_kunden ?></a>
                        <?php } else {
                            echo $amt_gehoert_zu_kunden;
                        } ?>

                    </td>
                    <td >
                        <a class="action_links action_links_btns" title="Details" href="index.php?action=traeger&do=edit&amtid=<?php echo $amt->getId() ?>">
                            bearbeiten
                        </a>
                        <?php if ($amt_gehoert_zu_kunden == 0) { ?>
                            <a class="action_links action_links_btns"
                               data-confirm='{"title":"Eintrag löschen?","body":"Soll der RE-Empfänger <strong><?php echo $amt->getBezeichnung(); ?></strong> gelöscht werden?","ok":"löschen","cancel":"abbrechen","ok_class":"link-class prevent_new_win button success","cancel_class":"button alert"}'
                               title="löschen" href="index.php?action=delete_sozialamt&aid=<?php echo $amt->getId() ?>">löschen</a>
                           <?php } else { ?>
                            <span  class=" " title="löschen - gesperrt solange User verknüpft"  disabled class="disabled">löschen gesp.</span>
                        <?php } ?>
                    </td>
                </tr>
            <?php } ?>
        </table>


    </div>


</div>




<div style="width: 45%; float: left;  padding: 10px;">
    <div class="column small-8">
        <?php if (( $_REQUEST['amtid'] && $_REQUEST['do'] == 'edit') || $_REQUEST['do'] == 'new') { ?>
            <div class="panel">
                <?php include_once 'edit_sozialamt.tpl.php'; ?>
            </div>
        <?php } ?>
        <?php if ($_REQUEST['amtid'] && $_REQUEST['do'] == 'show_kunden') { 
                $kunden_zu_amt = $kundeVerwaltung->findeAlleZuTraegerId($_REQUEST['amtid']);
            $traeger = $traegerVerwaltung->findeAnhandVonId($_REQUEST['amtid']);
                   
            ?>
            <div class="panel">
                <table style="width:100%">
                    
                    <tr>
                        <th colspan="2">Kunden zu Träger <?php echo $traeger->getBezeichnung() ?></th>
                    </tr>
                
                <?php foreach($kunden_zu_amt as $knd) { ?>
                    <tr>
                        <td><?php echo $knd->getName() ?></td>
                        <td>
                            <a href="index.php?action=neuer_kunde&kid=<?php echo $knd->getId() ?>&what=edit">Details...</a>
                        </td>
                    </tr>
                    <?php
                } ?>
                    </table>
            </div>
        <?php } ?>


    </div>


</div>

<script type="text/javascript">
    $(document).ready(function () {
        $("#aemter_search_input").autocomplete({
            source: "functions/aemter_list.php?goto=traeger",
            html: true,
            minLength: 3,
            delay: 500,
            position: {
                my: "right top",
                at: "right bottom"
            },
            select: function (event, ui) {
                window.location = ui.item.url;
            }
        }).focus(function () {
            $(this).autocomplete("search");
        });
    });
</script>