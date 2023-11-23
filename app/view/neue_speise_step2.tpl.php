
<script type="text/javascript">
var bools_array = new Array();
<?php $anzahl_felder = count($einrichtungskategorien); ?>
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

<h3><?php echo $speise->getBezeichnung() ?></h3>
<form method="post" action="index.php?action=neue_speise_step3" >
    <input type="hidden" name="speise_id" value="<?php echo $speise->getId() ?>" />
    <table border="1">
        <tr>
            <th>Einrichtungskategorie</th>
            <th>Standardmenge pro Kopf</th>
            <th>Einheit</th>
        </tr>
        <?php
        $x = 1;
        $anzahl = count($einrichtungskategorien);
        foreach ($einrichtungskategorien as $einrichtungskategorie) { ?>
        <tr>
            <td>
                <input type="hidden" name="einrichtungskategorie_id[]" value="<?php echo $einrichtungskategorie->getId() ?>" />
                    <?php echo $einrichtungskategorie->getBezeichnung() ?>
            </td>
            <td><input type="text" value="" name="menge[]" id="menge_<?php echo $x ?>" onkeyup="pruefeAlleFelderNacheinander(<?php echo $anzahl_felder ?>)"/></td>
                <?php if($x == 1) { ?>
            <td rowspan="<?php echo $anzahl ?>">
                <select name="einheit">
                    <option value="g">&nbsp;g&nbsp;</option>
                    <option value="ml">&nbsp;ml&nbsp;</option>
                    <option value="Stück">&nbsp;Stück&nbsp;</option>
                    <option value="Blech">&nbsp;Blech&nbsp;</option>
                </select>
            </td>
                <?php } ?>
        </tr>

            <?php $x++; } ?>
    </table>
    <input type="submit" value="speichern" disabled="disabled" id="speichere_menge_pro_kopf_btn" />
</form>