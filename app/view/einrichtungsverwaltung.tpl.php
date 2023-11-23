<?php
if (array_search(true, $fehler) !== FALSE) { ?>
<p class="error_text">
    Für einige Kategorien fehlen Angaben zu den Mengen pro Portion. <a class="action_links" href="index.php?action=speisenverwaltung">Jetzt überprüfen</a>
</p>
<?php } ?>
<?php
if ($_SESSION['fehler']) { ?>
<p class="error_text">
    <?php echo $_SESSION['fehler'] ?>
</p>
<?php
unset($_SESSION['fehler']);

} ?>

<table border="1">
    <tr>
    <th>Einrichtungskategorie</th>
    <th>Aktion</th>
    <th>Duplizieren</th>
    </tr>
<?php foreach($einrichtungskategorien as $einrichtungskategorie) {
    ?>
    <tr>
        <td><?php echo $einrichtungskategorie->getBezeichnung() ?></td>
        <td>
            <a class="action_links" href="index.php?action=real_delete&what=einrichtungskategorie&id=<?php echo $einrichtungskategorie->getId() ?>">löschen</a>
           
        </td>
        <td>
             
<?php //if ($_REQUEST['dev'] == 1) { ?>
            <form method="post" action="index.php?action=einrichtungskategorie_duplizieren">
                <input type="hidden" name="id" value="<?php echo $einrichtungskategorie->getId() ?>" />
               Als <input type="text" name="bezeichnung" value="" />
                <input type="submit"  value="duplizieren" />
            </form>
           
<?php // } ?>
        </td>
    </tr>
<?php } ?>
</table>


<?php require 'view/neue_kategorie.tpl.php' ?>