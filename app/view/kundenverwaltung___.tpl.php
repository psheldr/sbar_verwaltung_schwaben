<p>
    <a href="index.php?action=neuer_kunde" class="action_links">neuen Kunden anlegen</a><br />
    <a href="index.php?action=kundenverwaltung#trenner" class="action_links">zu Tourtrennungen springen</a>
</p>
<table border="1" class="kunden_tbl">
    <tr>
        <th><a href="index.php?action=kundenverwaltung&sort=name">Name</a></th>
        <th><a href="index.php?action=kundenverwaltung">LR</a></th>
        <th>Kategorie</th>
        <th>Aktion</th>
    </tr>
    <?php foreach($kunden as $kunde) {
        $einrichtungskategorie = $einrichtungskategorieVerwaltung->findeAnhandVonId($kunde->getEinrichtungskategorieId());
        ?>
    <tr>
        <td style="width: 180px;"><?php echo $kunde->getName() ?></td>
        <td><?php echo $kunde->getLieferreihenfolge() ?></td>
        <td><?php echo $einrichtungskategorie->getBezeichnung() ?></td>
        <td>
            <a class="action_links action_links_btns" href="index.php?action=neuer_kunde&kid=<?php echo $kunde->getId() ?>&what=edit">bearbeiten</a>
            <a class="action_links action_links_btns" href="index.php?action=neuer_kunde_step2&kid=<?php echo $kunde->getId() ?>&what=edit">Portionen</a>
            <a class="action_links action_links_btns" href="index.php?action=faktoren_festlegen&id=<?php echo $kunde->getId() ?>">Faktoren/Bemerkungen</a>
            <a class="action_links action_links_btns" href="index.php?action=real_delete&what=kunde&id=<?php echo $kunde->getId() ?>">löschen</a>
            <?php if ($kunde->getAktiv() == 0) { ?>
            <a class="action_links action_links_btns btn_inaktiv" href="index.php?action=kunden_aktivieren&id=<?php echo $kunde->getId() ?>">aktivieren</a>
            <?php } elseif ($kunde->getAktiv() == 1) { ?>
            <a class="action_links action_links_btns btn_aktiv" href="index.php?action=kunden_deaktivieren&id=<?php echo $kunde->getId() ?>">deaktivieren</a>
            <?php } ?>
        </td>
    </tr>
    <?php } ?>
</table>

<h3>Tourtrenner</h3>
<table border="1" class="kunden_tbl">
    <a name="trenner"></a>
    <tr>
        <th><a href="index.php?action=kundenverwaltung&sort=name">Name</a></th>
        <th><a href="index.php?action=kundenverwaltung">LR</a></th>
        <th>Kategorie</th>
        <th>Aktion</th>
    </tr>
    <?php foreach($tourenden as $tourende) {
        $einrichtungskategorie = $einrichtungskategorieVerwaltung->findeAnhandVonId($tourende->getEinrichtungskategorieId());
        ?>
    <tr>
        <td style="width: 180px;"><?php echo $tourende->getName() ?></td>
        <td><?php echo $tourende->getLieferreihenfolge() ?></td>
        <td><?php echo $einrichtungskategorie->getBezeichnung() ?></td>
        <td>
            <a class="action_links action_links_btns" href="index.php?action=neuer_kunde&kid=<?php echo $tourende->getId() ?>&what=edit">bearbeiten</a>
            <a class="action_links action_links_btns" href="index.php?action=real_delete&what=kunde&id=<?php echo $tourende->getId() ?>">löschen</a>
            <?php if ($tourende->getAktiv() == 0) { ?>
            <a class="action_links action_links_btns btn_inaktiv" href="index.php?action=kunden_aktivieren&id=<?php echo $tourende->getId() ?>">aktivieren</a>
            <?php } elseif ($tourende->getAktiv() == 1) { ?>
            <a class="action_links action_links_btns btn_aktiv" href="index.php?action=kunden_deaktivieren&id=<?php echo $tourende->getId() ?>">deaktivieren</a>
            <?php } ?>
        </td>
    </tr>
    <?php } ?>
</table>