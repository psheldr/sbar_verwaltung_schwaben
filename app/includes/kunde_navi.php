
                <br />
                <p>Aktionen für Kunde <strong><?php echo $kunde->getName() ?></strong></p>
                <a class="action_links action_links_btns" href="index.php?action=neuer_kunde&kid=<?php echo $kunde->getId() ?>&what=edit">bearbeiten</a>
            <a class="action_links action_links_btns" href="index.php?action=neuer_kunde_step2&kid=<?php echo $kunde->getId() ?>&what=edit">Portionen</a>
            <a class="action_links action_links_btns" href="index.php?action=faktoren_festlegen&id=<?php echo $kunde->getId() ?>">Faktoren/Bemerkungen</a>
     <!--       <a class="action_links action_links_btns" href="index.php?action=real_delete&what=kunde&id=<?php echo $kunde->getId() ?>">Kunde löschen</a>
            <?php if ($kunde->getAktiv() == 0) { ?>
                <a class="action_links action_links_btns btn_inaktiv" href="index.php?action=kunden_aktivieren&id=<?php echo $kunde->getId() ?>">Kunde aktivieren</a>
            <?php } elseif ($kunde->getAktiv() == 1) { ?>
                <a class="action_links action_links_btns btn_aktiv deaktivier_link" href="index.php?action=kunden_deaktivieren&id=<?php echo $kunde->getId() ?>" kid="<?php echo $kunde->getId() ?>" id="deaktivier_link" onclick="/*javascript: Absagegrund(<?php echo $kunde->getId() ?>); return false*/">deaktivieren</a>
            <?php } ?>  -->
                <br />
                <br />