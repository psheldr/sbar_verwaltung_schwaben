<p>
    <a href="index.php?action=speisenverwaltung" class="action_links"><<< Zurück zur Übersicht</a>
</p>

<form method="post" action="index.php?action=rezept_speichern">
    <div class="form_box">
        <label class="form_label">Rezept/Bemerkung</label>
        <textarea  name="rezept" cols="50" rows="8"><?php echo $speise->getRezept() ?></textarea>
        <input type="hidden" name="id" value="<?php echo $id ?>" />
        <input type="submit" class="submit_btn" value="speichern" />
    </div>
</form>