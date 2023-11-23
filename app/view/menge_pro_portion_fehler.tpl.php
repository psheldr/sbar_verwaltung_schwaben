<?php
foreach ($speisen as $speise) {
    ?>
<table>
    <tr>
        <th colspan="3"><?php echo $speise->getBezeichnung() ?></th>
    </tr>
    <?php $einrichtungskategorien = $einrichtungskategorieVerwaltung->findeAlle() ?>
    <?php foreach($einrichtungskategorien as $einrichtungskategorie) { 
        $menge_pro_portion = $menge_pro_portionVerwaltung->findeAnhandVonSpeiseIdUndEinrichtungskategorieId($speise->getId(), $einrichtungskategorie->getId());
        ?>

    <tr>
        <td><?php echo $einrichtungskategorie->getBezeichnung() ?></td>
        <td><input type="text" name="menge" value="<?php echo $menge_pro_portion->getMenge() ?>" /></td>
        <td><?php echo $menge_pro_portion->getEinheit() ?></td>
    </tr>
    <?php } ?>
</table>
<?php
}
?>
