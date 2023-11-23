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


    <a class="button small_button success" href="index.php?action=traeger&do=new">Neuen RE-Empfänger/Sozialamt anlegen <i class="fi-plus size-21"></i></a>     <br /> <br />
    <form method="post" action="index.php?action=traeger">

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
                    <?php foreach ($traeger_list as $traeger) { ?>
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

    <table style="width: 100%;">
        <tr>
            <td colspan="4">
                <?php 
                
                ?>
                <a href="index.php?action=export_traeger&sort=<?php echo $_SESSION['sort'] ?>&sort_dir=<?php echo $_SESSION['sort_dir'] ?>">als XLS exportieren</a>
            </td>
        </tr>
        <tr>
            <th class="small-2">ID 
                <a href="index.php?action=traeger&sort_dir=ASC&sort=id"><i class="fi-arrow-up"></i> <a href="index.php?action=traeger&sort_dir=DESC&sort=id"><i class="fi-arrow-down"></i></a>
            </th>
            <th style="width: 50%;">
                Amt
                <a href="index.php?action=traeger&sort_dir=ASC&sort=bezeichnung"><i class="fi-arrow-up"></i></a> <a href="index.php?action=traeger&sort_dir=DESC&sort=bezeichnung"><i class="fi-arrow-down"></i></a>

            </th>
            <th>User</th>
            <th>Matchcode</th>
            <th class="small-2">&nbsp;&nbsp;&nbsp;</th>
        </tr>
        <?php
        
        foreach ($aemter as $amt) {
            $amt_gehoert_zu_benutzer = $benutzerHasSozialAmtVerwaltung->findeAlleZuSozialamtId($amt->getId());
            ?>
            <tr>
                <td>
                    <?php echo $amt->getId(); ?>
                </td>
                <td>
                    <?php echo $amt->getBezeichnung(); ?>
                </td>
                <td>
                    <?php echo count($amt_gehoert_zu_benutzer) ?>

                </td>
                <td>
                    <?php echo $amt->getMatchcode() ?>
                </td>
                <td >
                    <a  title="Details" href="index.php?action=traeger&amtid=<?php echo $amt->getId() ?>">
                        <i class="fi-wrench size-21"></i>
                    </a>
                    <?php if (count($amt_gehoert_zu_benutzer) == 0) { ?>
                        <a
                            data-confirm='{"title":"Eintrag löschen?","body":"Soll der RE-Empfänger <strong><?php echo $amt->getBezeichnung(); ?></strong> gelöscht werden?","ok":"löschen","cancel":"abbrechen","ok_class":"link-class prevent_new_win button success","cancel_class":"button alert"}'
                            title="löschen" href="index.php?action=delete_sozialamt&aid=<?php echo $amt->getId() ?>"><i class="kf_red fi-trash size-21"></i></a>
                        <?php } else { ?>
                        <span title="löschen - gesperrt solange User verknüpft"  disabled class="disabled"><i class="kf_grey disabled fi-trash size-21"></i></span>
                    <?php } ?>
                </td>
            </tr>


        <?php } ?>
    </table>

</div>

<div class="column small-8">
    <?php if ($_REQUEST['amtid'] || $_REQUEST['do'] == 'new') { ?>
        <div class="panel">
            <?php include_once 'edit_sozialamt.tpl.php'; ?>

        </div>
    <?php } ?>
    <?php if ($_REQUEST['amtid']) { ?>


        <table>
            <tr>
                <th colspan="4"><?php echo count($benutzer_zu_sozialamt) ?> Benutzer zu <?php echo $traeger_selected->getBezeichnung() ?></th>
            </tr>
            <tr>
                <th>#</th>
                <th>ID</th>
                <th>Name</th>
                <th>Aktion</th>
            </tr>
            <?php
            $c = 0;
            foreach ($benutzer_zu_sozialamt as $benutzer_has_sozialamt) {
                $c++;
                $projekt_id = $benutzer_has_sozialamt->getProjektId();
                require 'lib/switch_db.php';
                $benutzer = $benutzerVerwaltung->findeAnhandVonId($benutzer_has_sozialamt->getBenutzerId());
                ?>
                <tr>
                    <td>
                        <?php echo $c ?>
                    </td>
                    <td><?php echo $benutzer->getNachname() ?>, <?php echo $benutzer->getVorname() ?></td>
                    <td>
                        <?php echo $projekt_id ?>-<?php echo $benutzer->getId(); ?>
                    </td>
                    <td>

                        <a target="_blank" class="button small_button" href="index.php?action=but_verwaltung&uid=<?php echo $benutzer->getId() ?>&pid=<?php echo $projekt_id ?>">BuT</a>
                        <a target="_blank" class="button small_button"class="button small_button"  href="index.php?action=cockpit_benutzer&uid=<?php echo $benutzer->getId() ?>&pid=<?php echo $projekt_id ?>">Konto</a>
                    </td>
                </tr>
            <?php } ?>
        </table>
    <?php } ?>
</div>



<script type="text/javascript">
    $(document).ready(function () {
        $("#aemter_search_input").autocomplete({
            source: "includes/aemter_list.php?goto=traeger",
            html: true,
            minLength: 3,
            delay: 1000,
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