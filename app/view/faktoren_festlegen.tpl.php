<p>
    <a href="index.php?action=kundenverwaltung" class="action_links"><< Zurück zur Übersicht</a>
    </p>
<form method="post" action="index.php?action=do_faktoren_festlegen">
    <h4>Faktoren für <?php echo $kunde->getName() ?></h4>
        <?php include 'includes/kunde_navi.php' ?>
    <input type="submit" value="speichern" class="button" style="height:auto;padding: 5px;font-size: 18px;font-weight:bold;background: green;color: #fff;margin-right: 20px;margin-bottom: 10px;"/>
    <table>
        <tr>
            <th>Speise</th>
            <th>Faktor</th>
            <th>Standardmenge</th>
            <th>Bemerkung</th>
        </tr>
        <?php
        foreach ($speisen as $speise) {
            $menge_pro_portion = $menge_pro_portionVerwaltung->findeAnhandVonSpeiseIdUndEinrichtungskategorieId($speise->getId(), $einrichtungskategorie->getId());
            $indi_faktor = $indifaktorVerwaltung->findeAnhandVonSpeiseIdUndKundeId($speise->getId(), $kunde->getId());
            $bemerkung_zu_speise = $bemerkungzuspeiseVerwaltung->findeAnhandVonKundeIdUndSpeiseId($kunde->getId(), $speise->getId());
            ?>
        <tr>
            <td>
                
            <?php echo $speise->getBezeichnung() ?>
                <input type="hidden" name="speise_ids[]" value="<?php echo $speise->getId() ?>" />
            </td>
                <?php if($indi_faktor->getId()) { ?>
            <td><input type="text" name="faktoren[]" value="<?php echo $indi_faktor->getFaktor() ?>" />
                
            </td>
                <?php } else { ?>
            <td><input type="text" name="faktoren[]" value="1" /></td>
                <?php } ?>
            <td>
                <?php echo $menge_pro_portion->getMenge() ?> <?php echo $menge_pro_portion->getEinheit() ?>
            </td>
            <td><input type="text" name="bemerkungen[]" value="<?php echo $bemerkung_zu_speise->getBemerkung() ?>" />
                <input type="hidden" name="bemerkungen_ids[]" value="<?php echo $bemerkung_zu_speise->getId() ?>" />
                <a class="action_links" href="index.php?action=bemerkung_zu_tag_loeschen&kid=<?php echo $kunde->getId() ?>&id=<?php echo $bemerkung_zu_speise->getId() ?>" >Bemerk. löschen</a>
            </td>
        </tr>
        <?php
        }
        ?>
    </table>
    
    <input type="hidden" name="kunde_id" value="<?php echo $kunde->getId() ?>" />  
    <input type="submit" value="speichern" class="button" style="height:auto;padding: 5px;font-size: 18px;font-weight:bold;background: green;color: #fff;margin-right: 20px;margin-bottom: 10px;"/>
  
</form>
