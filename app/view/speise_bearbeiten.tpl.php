<p>
<a href="index.php?action=speisenverwaltung" class="action_links"><<< Zurück zur Übersicht</a>
</p>

<form method="post" action="index.php?action=speise_bearbeitung_speichern">
    <div class="form_box">
        <label class="form_label">Speise bearbeiten</label>
        <input type="hidden" name="id" value="<?php echo $speise_id ?>" />
        <input class="form_input" type="text" name="bezeichnung" value="<?php echo $speise->getBezeichnung() ?>" />
        <input type="submit" class="submit_btn" value="weiter" />
    </div>
</form>
