<?php
$kunde_bezeichnung = $kunde->getName();
$kuid = $kunde->getId();
$info_block = '';
$readonly = '';
$alert_kitafino_zahlen = '';



$zeilen_zu_kunde = array();
//Standard Kunde
if (!$kunde->getBioKunde() && !$kunde->getStaedtischerKunde()) {
    $kundentyp = 'standard';
    $zeilen_zu_kunde[0] = array(
        'kunde_id' => $kunde->getId(),
        'speise_nr' => 1
    );
}

//Standard Kunde mit 2 Speisen
if ($kunde->getAnzahlSpeisen() > 1 && !$kunde->getBioKunde() && !$kunde->getStaedtischerKunde()) {
    $kundentyp = 'standard';
    $zeilen_zu_kunde[1] = array(
        'kunde_id' => $kunde->getId(),
        'speise_nr' => 2
    );
}

//BIO KUNDE
if ($kunde->getBioKunde()) {
    $kundentyp = 'bio';
    $zeilen_zu_kunde[0] = array(
        'kunde_id' => $kunde->getId(),
        'speise_nr' => 3,
        'info_block' => '<img style="margin-top:5px;max-width: 20px;" src="../images/biosiegel_small.jpg" />'
    );
}
if ($kunde->getBioKunde() && $kunde->getAnzahlSpeisen() > 1) {
    $zeilen_zu_kunde[1] = array(
        'kunde_id' => $kunde->getId(),
        'speise_nr' => 4
    );
}

//STÄDTISCHER KUNDE
if ($kunde->getStaedtischerKunde()) {
    $kundentyp = 'stadt';
    $zeilen_zu_kunde[0] = array(
        'kunde_id' => $kunde->getId(),
        'speise_nr' => 3,
        'info_block' => '<img style = "margin-top:5px;max-width: 15px;" src = "images/nbg_icon.png" /><img style="margin-top:5px;max-width: 20px;" src="../images/biosiegel_small.jpg" />'
    );
    if ($kunde->getEinrichtungskategorieId() != 6) {
        $zeilen_zu_kunde[1] = array(
            'kunde_id' => $kunde->getId(),
            'speise_nr' => 4,
            'info_block' => '<img style = "margin-top:5px;max-width: 15px;" src = "images/nbg_icon.png" />'
        );
    }
}
// kitafino CONNECT
if ($kunde->getKundennummer() && $kunde->getKitafinoGruppen() == '') {
    $alert_kitafino_zahlen = 'border: 2px solid red;';
    $info_block .= '<strong style="color: red;font-size: 11px;">Achtung! Fehler: Zahlen konnten keinen Gruppen zugeordnet werden. In kitafino prüfen und Zahlen manuell eintragen.</strong>';
}
if ($kunde->getKundennummer()) { //IST KITAFINO KUNDE
    $readonly = 'readonly';
    $projekt_id_db = $kunde->getKundennummer();
    $count_orders = $portionen_tage[$projekt_id_db][$kunde->getId()][1][0];
    $info_block .= ' <img style="margin-top:5px;" src="images/kitafino_icon.png" /> <strong>' . $projekt_id_db . '</strong>';

    /* if ($portionen_mo != $count_orders || ($kunde->getKundennummer() && $kunde->getKitafinoGruppen() == '')) {
      $alert_kitafino_zahlen = 'border: 2px solid red;';
      $readonly = '';
      } */
}
?>

<?php
$zeilenzahl_max = count($zeilen_zu_kunde);
$count_zeilen = 0;



foreach ($zeilen_zu_kunde as $zeile_zu_kunde) {

    $count_zeilen++;
    $speise_nr = $zeile_zu_kunde['speise_nr'];
    ?>
    <tr class="<?php echo $highlight_class ?>" > 

        <?php
        $count_tag = 1;
        $tage_strs = array('mo', 'di', 'mi', 'do', 'fr');
        foreach ($tage_ts_woche_array as $tag_ts) {
            $speisen_string = '';
            $tag_str = $tage_strs[$count_tag - 1];
            $focus_count++;
            if (count($speisen_strings[$count_tag][$speise_nr]['speise_ids'])) {
                $speisen_string = implode('|', $speisen_strings[$count_tag][$speise_nr]['speise_ids']);
            } else {

                $speisen_string = 'SPEISE fehlt';
            }


            $menuname_obj = $menunamenVerwaltung->findeAnhandVonTagMonatJahrSpeiseNr(date('d', $tag_ts), date('m', $tag_ts), date('Y', $tag_ts), $speise_nr);
            $menuname = $menuname_obj->getBezeichnung();

            if (trim($menuname) == '') {
                $menuname = '<span style="color: red;">kein Menuname</span>';
            }

                $portionen = $ports_array[$speise_nr][$count_tag - 1];

            if (count($speisen_strings[$count_tag][$speise_nr]['speise_ids']) && $kunde->getEinrichtungskategorieId() != 6 && $kunde->getEinrichtungskategorieId() != 5) {
            $gesamt_portionen_array[$count_tag] += $portionen;
            }

            ?>
            <?php
            if ($count_tag == 1) {
                ?>
                <td>
                    <?php echo $kunde_bezeichnung ?> <br />
                    <?php echo $info_block . ' ' . $zeile_zu_kunde['info_block']; ?>
                </td>
            <?php }
            ?>
            <td class="<?php echo $highlight_col_arr[$count_tag] ?>"  <?php
            if ($kunde->getEinrichtungskategorieId() == 6) {
                ?>colspan="2"<?php } ?>>
                <div style="position:relative; padding-right: 25px;">

                    <?php /*  if ($kunde->getEinrichtungskategorieId() == 6 || $count_tag == 1) {
                      ?>
                      <?php echo $kunde_bezeichnung ?> <br />
                      <?php } */ ?> 



                    <?php
                    if ($kunde->getEinrichtungskategorieId() != 6) {
                        ?>
                        <?php echo $echo_start_array[$count_tag] ?>
                        <span style="font-weight: bold; font-size: 13px;color:#888;position:absolute;top:3px;right:3px;">
                            S<?php echo $speise_nr ?> 
                            
                        </span>
                        <span style="font-size: 11px;">
                            <?php echo $menuname ?> </span>
                    <?php } ?> 
                </div>

                <?php if ($kunde->getStaedtischerKunde()) { ?>
                    <div class="portionen_alarm_<?php echo $count_tag ?>_<?php echo $kunde->getId() ?>_<?php echo $speise_nr ?>" style="color:red;font-weight:bold;display:none;">
                        Achtung! Nur eine Speise erlaubt!
                    </div>
                <?php } ?> 
            </td>


            <?php if ($kunde->getEinrichtungskategorieId() != 6) { ?>
                <td style="min-width:65px; max-width: 100px;" class="<?php echo $highlight_col_arr[$count_tag] ?>">

                    <?php if (!$kunde->getKundennummer()) { ?>
                        <input disabled style="float:right; color: #aaa; <?php echo $alert_kitafino_zahlen ?>" class="portionen_input focus_<?php echo $focus_count ?> " data-counttag="<?php echo $count_tag ?>" 
                               data-loader="<?php echo $kuid . '_' . $count_tag . '_' . $speise_nr ?>"  data-focus="<?php echo $focus_count ?>"

                               data-tag_ts="<?php echo $tag_ts ?>" data-starttag="<?php echo $start_tag_woche ?>" data-startmonat="<?php echo $start_monat_woche ?>" data-startjahr="<?php echo $start_jahr_woche ?>"  data-startts="<?php echo $wochenstarttag ?>"
                               data-kundentyp="<?php echo $kundentyp ?>"
                               data-kid="<?php echo $kuid ?>" data-speisenr="<?php echo $speise_nr ?>" data-tag="<?php echo $tage_strs[$count_tag - 1] ?>" 
                               type="text" name="portionen_mo[<?php echo $kuid ?>][<?php echo $speise_nr ?>]" <?php echo $readonly ?> value="<?php echo $portionen ?>" />

                    <?php } ?>

                    <span style="float:left;font-size: 14px;font-weight: bold; "  class="show_portionen_db input_tag_<?php echo $kuid . '_' . $count_tag . '_' . $speise_nr ?>" id="portionen_in_db_<?php echo $focus_count ?>">
                        <?php include "portionen_check.php" ?>
                    </span>
                    <span  class="" style="float:left; max-width: 15px;" id="<?php echo $kuid . '_' . $count_tag . '_' . $speise_nr ?>">

                    </span >  




                    <?php if (!$kunde->getKundennummer() && $standardports_array[$speise_nr][$count_tag - 1] !== NULL) { ?>
                        <?php
                        $standard_color = 'cecece';
                        if ($standardports_array[$speise_nr][$count_tag - 1] != $portionen_db && $standardports_array[$speise_nr][$count_tag - 1] != 0) {
                            $standard_color = '888';
                        }
                        ?>
                        <span style="padding-left:3px;font-size: 12px;color: #<?php echo $standard_color ?>;font-weight: bold;"  class="show_portionen_db" id="standardportionen_<?php echo $focus_count ?>">
                            <span style="font-size: 10px;">Stand.:</span> <?php echo $standardports_array[$speise_nr][$count_tag - 1] ?>

                        <?php } ?>



                        <?php if ($kunde->getKundennummer() && $alert_kitafino_zahlen) { ?>
                            <br />
                            <input style="float: left; background: url(images/kitafino_icon.png) no-repeat  left center ;background-size: 30%; padding-left: 15px; border:none;<?php echo $alert_kitafino_zahlen ?>" type="text" disabled="disabled" class="portionen_input" value="<?php echo $count_orders ?>" />
                        <?php } ?>

                </td>
            <?php } ?>

            <?php
            $count_tag++;
        }
        ?>

    </tr>
<?php } ?>

