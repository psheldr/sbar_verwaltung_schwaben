<?php if ($action == 'neuer_kunde') { ?>
<form method="post" action="index.php?action=neue_einrichtung&go=neuer_kunde">
<?php } else { ?>
<form method="post" action="index.php?action=neue_einrichtung">
<?php } ?>

    
        <label class="form_label">Neue Einrichtungsart</label>
        <input type="text" class="form_input" name="bezeichnung" value="" /><br />
        <input class="submit_btn" type="submit" value="speichern" /><br />
    
</form>