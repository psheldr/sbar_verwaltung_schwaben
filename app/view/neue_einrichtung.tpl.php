
<script type="text/javascript">
var bools_array = new Array();
<?php $anzahl_felder = count($speisen); ?>
    function pruefeAlleFelderNacheinander(anzahl) {
        buttonid = 'speichere_menge_pro_kopf_btn';
        for (i = 1; i <= anzahl; i++) {
            var key = i;
            pruefeInhalt('menge_'+i, key);
        }
        if(jQuery.inArray(false, bools_array) == -1) {
            enableButton(buttonid);
        } else {
            disableButton(buttonid);
        }
    }

    function pruefeInhalt(feldid,key) {
        if(document.getElementById(feldid).value == '') {
            bools_array.splice(key,1,false);
        } else {
            bools_array.splice(key,1,true);			
        }
    }

    function disableButton(buttonid) {
        $("#"+buttonid).attr("disabled", "disabled");
    }
    function enableButton(buttonid) {
        $("#"+buttonid).removeAttr("disabled");
    }
</script>

<h4>
    Mengen definieren für <?php echo $einrichtungskategorie->getBezeichnung() ?>
</h4>
<form method="post" action="index.php?action=neue_einrichtung_mengen&go=<?php echo $go ?>">
<table>
<?php

$x = 1;
var_dump($anzahl_felder);
foreach ($speisen as $speise) { ?>
    
    <tr>
        <td>
            <input type="hidden" name="einrichtungskategorie_id[]" value="<?php echo $einrichtungskategorie->getId() ?>" />
            <input type="hidden" name="speise_id[]" value="<?php echo $speise->getId() ?>"  />
           <?php echo $speise->getBezeichnung() ?>
        </td>
        <td><input type="text" name="menge[]" id="menge_<?php echo $x ?>" onkeyup="pruefeAlleFelderNacheinander(<?php echo $anzahl_felder ?>)" /></td>
        <td>
            <select name="einheit[]">
                    <option value="g">&nbsp;g&nbsp;</option>
                    <option value="ml">&nbsp;ml&nbsp;</option>
                    <option value="Stk">&nbsp;Stück&nbsp;</option>
                    <option value="Blech">&nbsp;Blech&nbsp;</option>
                </select>
        </td>
    </tr>
    
<?php $x++; }
?>
</table>
    <input type="submit" id="speichere_menge_pro_kopf_btn" value="speichern" disabled="disabled" />
    </form>

<table class="mengen_tbl">
    <tr>
        <th>&nbsp;</th>
        <?php foreach($kategorien as $kategorie) { ?>
        <th>
            <?php echo $kategorie->getBezeichnung() ?>
        </th>
        <?php } ?>
    </tr>
    <?php $c = 1; foreach($speisen as $speise) { ?>
    <tr>
        <td <?php if($c % 2) {echo 'class="bg_tr"';}?>><?php echo $speise->getBezeichnung() ?></td>
        <?php foreach($kategorien as $kategorie) {
            $menge_pro_portion = $menge_pro_portionVerwaltung->findeAnhandVonSpeiseIdUndEinrichtungskategorieId($speise->getId(), $kategorie->getId());
            ?>
        <td <?php if($c % 2) {echo 'class="bg_tr txt_right"';}else {echo 'class="txt_right"';}?> >
            <?php echo $menge_pro_portion->getMenge() . ' ' . $menge_pro_portion->getEinheit() ?>
        </td>
        <?php } ?>
    </tr>
    <?php $c++; } ?>
</table>