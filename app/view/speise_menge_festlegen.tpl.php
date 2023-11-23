<h4>
    Mengen definieren für <?php echo $speise->getBezeichnung() ?>
</h4>
<p>
<a href="index.php?action=speisenverwaltung" class="action_links"><<< Zurück zur Übersicht</a>
</p>
<table border="1">
    <tr>
        <th>Speise</th>
        <th>Menge -> Einrichtung</th>
    </tr>
    <?php 
        $mengen_pro_portion_array = $menge_pro_portionVerwaltung->findeAnhandVonSpeiseId($speise->getId()); 
       
        if (count($mengen_pro_portion_array)) {
        $einheit = $mengen_pro_portion_array[0]->getEinheit();
            
        }
        ?>
    <tr>
        <td><?php echo $speise->getBezeichnung() ?></td>
        <td>
            <form method="post" action="index.php?action=mengen_festlegen">
            <table>
                <tr>
                    <td colspan="2">
                        
                         <select name="einheit">
                    <option value="g" <?php if ($einheit == 'g'){echo 'selected="selected"';}  ?>>&nbsp;g&nbsp;</option>
                    <option value="ml" <?php if ($einheit == 'ml'){echo 'selected="selected"';}  ?>>&nbsp;ml&nbsp;</option>
                    <option value="Stück" <?php if ($einheit == 'Stück'){echo 'selected="selected"';}  ?>>&nbsp;Stück&nbsp;</option>
                    <option value="Blech" <?php if ($einheit == 'Blech'){echo 'selected="selected"';}  ?>>&nbsp;Blech&nbsp;</option>
                    <option value="Beutel" <?php if ($einheit == 'Beutel'){echo 'selected="selected"';}  ?>>&nbsp;Beutel&nbsp;</option>
                    <option value="Flasche" <?php if ($einheit == 'Flasche'){echo 'selected="selected"';}  ?>>&nbsp;Flasche&nbsp;</option>
                </select>
                    </td>
                </tr>
                    <?php $einrichtungen = $einrichtungskategorieVerwaltung->findeAlle();
                    foreach($einrichtungen as $einrichtungskategorie) {
                        $menge_pro_portion = $menge_pro_portionVerwaltung->findeAnhandVonSpeiseIdUndEinrichtungskategorieId($speise->getId(),$einrichtungskategorie->getId());

                            ?>
                <tr>
                    <td><?php echo $einrichtungskategorie->getBezeichnung() ?></td>

                    <td>

 <input type="hidden" name="ids[]" value="<?php if ($menge_pro_portion->getId() > 0) { echo $menge_pro_portion->getId(); } else {echo '0';} ?>" />
 <input type="hidden" name="speise_id" value="<?php  echo $speise->getId() ?>" />

                    <input type="hidden" name="einrichtungskategorie_ids[]" value="<?php echo $einrichtungskategorie->getId() ?>" />
                    <input type="text" name="mengen[]" value="<?php echo $menge_pro_portion->getMenge(); ?>" />
                    <?php echo $menge_pro_portion->getEinheit() ?></td>
                </tr>
                    <?php } ?>
                <tr>
                    <td colspan="2"><input type="submit" value="speichern" /></td>
                </tr>
            </table>
            </form>

        </td>
    </tr>

 

</table>

<table class="mengen_tbl">
    <tr>
        <th>&nbsp;</th>
        <?php foreach($kategorien as $kategorie) { ?>
        <th>
            <?php echo $kategorie->getBezeichnung() ?>
        </th>
        <?php } ?>
    </tr>
    <?php $c = 1; ?>
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
    <?php $c++; ?>
</table>