<p>
    <a href="index.php?action=neuer_kunde" class="action_links">neuen Kunden anlegen</a><br />
    <a href="index.php?action=kundenverwaltung#trenner" class="action_links">zu Tourtrennungen springen</a>

    <?php //if ($_REQUEST['dev'] == 1) { ?>
    <br />
    <a href="index.php?action=kundenliste_xls" class="submit_btn" style="text-align: center;margin: 5px; padding: 5px;">Kundenliste erzeugen</a>

    <?php
    //} 
    $fehler_zu_kunden = array();
    foreach ($kunden as $kunde) {
        $kid = $kunde->getId();
        if ($kunde->getKundennummer()) {
            if ($kunde->getKitafinoGruppen() == NULL) {
                $fehler_zu_kunden[$kid][] = 'Es wurden noch keine kitafino Gruppen zugewiesen.';
            }
        }
        if (isset($fehler_zu_kunden[$kid]) && count($fehler_zu_kunden[$kid])) {
            echo '<br />' . $kunde->getName() . ':';
            foreach ($fehler_zu_kunden[$kid] as $fehler_mssg) {
                echo ' - ' . $fehler_mssg . '<a href="index.php?action=neuer_kunde&kid=' . $kid . '&what=edit">bearbeiten</a><br />';
            }
        }
    }
    ?>

</p>
<table border="1" class="kunden_tbl" style="width:100%;">
    <tr>
        <th><a href="index.php?action=kundenverwaltung&sort=name">Name</a></th>
        <th><a href="index.php?action=kundenverwaltung">LR</a></th>
        <th>Einrichtungsart</th>
        <th>Mengenkategorie</th>
        <th>Aktion</th>
        <th>Anz. Sp.</th>
        <th>Anz. Boxen</th>
        <th>Besteck</th>
    </tr>
    <?php
    foreach ($kunden as $kunde) {
        $master_slave_info = '';
        $master_color = '#6C806C';
        if ($kunde->getMaster()) {
            $master_color = '#0C800C';
            $master_slave_info = 'Masterkunde ' . $kunde->getMasternummer();
        }
        if (!$kunde->getMaster() && $kunde->getMasternummer()) {
            $masterkunde = $kundeVerwaltung->findeMasterkundeZuMasternummer($kunde->getMasternummer());
            if (!count($masterkunde)) {
                $master_slave_info = 'Unterkunde zu ' . $kunde->getMasternummer().' MASTERKUNDE FEHLT!';                
            } else {
                $master_slave_info = 'Unterkunde zu ' . $masterkunde[0]->getName() . ' ' . $kunde->getMasternummer();
            }
        }
        $einrichtungskategorie = $einrichtungskategorieVerwaltung->findeAnhandVonId($kunde->getEinrichtungskategorieId());
        if ($kunde->getTourId() == 0 && $kunde->getLieferreihenfolge() > 1) {
            $tour_id_zu_lieferreihenfolge = $kundeVerwaltung->findeTourZuKundenReihenfolge($kunde->getLieferreihenfolge());
            if (count($tour_id_zu_lieferreihenfolge)) {
                $kunde->setTourId($tour_id_zu_lieferreihenfolge[0]->getId());
                $kundeVerwaltung->speichere($kunde);
            }
        }
        ?>
        <tr>
            <td style="width: 180px;"><?php echo $kunde->getName() ?>

                <?php if ($kunde->getStaedtischerKunde()) { ?>
                    <br/>
                    <img style="margin-top:5px;max-width: 20px;" src="images/nbg_icon.png" />

                <?php } ?>

                <?php if ($kunde->getBioKunde()) { ?>
                    <br/>
                    <img style="margin-top:5px;max-width: 30px;" src="../images/biosiegel_small.jpg" />

                <?php } ?>

                <?php if ($kunde->getKundennummer()) { ?>		
                    <br /><img src="images/kitafino_icon.png" />
                    <?php
                    echo $kunde->getKundennummer();
                }
                if ($fehler_zu_kunden[$kunde->getName()]) {
                    foreach ($fehler_zu_kunden[$kunde->getName()] as $fehler_mssg) {
                        echo '<br />' . $fehler_mssg;
                    }
                }
                ?>

                <?php if ($master_slave_info) { ?>
                    <br />
                    <span style="font-size: 11px;font-weight: bold; color: <?php echo $master_color ?>;">
                        <?php echo $master_slave_info ?>
                    </span>
                <?php } ?>

            </td>
            <td><?php echo $kunde->getLieferreihenfolge() ?></td>
            <td><?php echo $kunde->getEinrichtungsart() ?></td>
            <td><?php echo $einrichtungskategorie->getBezeichnung() ?></td>
            <td style="line-height: 26px;">
                <a class="action_links action_links_btns" href="index.php?action=neuer_kunde&kid=<?php echo $kunde->getId() ?>&what=edit">bearbeiten</a>
                <a class="action_links action_links_btns" href="index.php?action=neuer_kunde_step2&kid=<?php echo $kunde->getId() ?>&what=edit">Portionen</a>
                <a class="action_links action_links_btns" href="index.php?action=faktoren_festlegen&id=<?php echo $kunde->getId() ?>">Faktoren/Bemerkungen</a>
                <a class="action_links action_links_btns" href="index.php?action=real_delete&what=kunde&id=<?php echo $kunde->getId() ?>">löschen</a>
                <?php if ($kunde->getAktiv() == 0) { ?>
                    <a class="action_links action_links_btns btn_inaktiv" href="index.php?action=kunden_aktivieren&id=<?php echo $kunde->getId() ?>">aktivieren</a>
                <?php } elseif ($kunde->getAktiv() == 1) { ?>
                    <a class="action_links action_links_btns btn_aktiv deaktivier_link" href="index.php?action=kunden_deaktivieren&id=<?php echo $kunde->getId() ?>" kid="<?php echo $kunde->getId() ?>" id="deaktivier_link" onclick="/*javascript: Absagegrund(<?php echo $kunde->getId() ?>); return false*/">deaktivieren</a>


                <?php } ?>
            </td>
            <td>
                <?php echo $kunde->getAnzahlSpeisen() ?>

                <a class="action_links action_links_btns" href="index.php?action=neuer_kunde_step2&kid=<?php echo $kunde->getId() ?>&what=edit">bearb.</a>
            </td>
            <td>
                <?php echo $kunde->getAnzahlBoxen() ?>

                <a class="action_links action_links_btns" href="index.php?action=neuer_kunde_step2&kid=<?php echo $kunde->getId() ?>&what=edit">bearb.</a>
            </td>
            <td>
                <?php
                $besteck_int = $kunde->getBesteck();
                if ($besteck_int == 0) {
                    ?>
                    <a class="action_links action_links_btns" href="index.php?action=kunde_set_besteck&kid=<?php echo $kunde->getId() ?>&set=1">Nein</a>                
                <?php } else { ?>
                    <a class="action_links action_links_btns" style="color:green;" href="index.php?action=kunde_set_besteck&kid=<?php echo $kunde->getId() ?>&set=0">Ja</a>               
                <?php }
                ?>

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
    <?php
    foreach ($tourenden as $tourende) {
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








<div class="prompts dialog2" id="dialog_box" title="Endtag angeben">
    <p>
        Sie müssen angeben, bis zu welchem Tag (einschließlich!) der Kunde aktiv war. 
        Bitte geben Sie das Datum des <strong>letzten aktiven Tages</strong> des Kunden an.
    </p>
    <form method="post" action="index.php?action=kunden_deaktivieren" id="endtag_form">
        <!--
        <input  class="text ui-widget-content ui-corner-all" id="test" type="text" name="endtag" style="width: 40px;"/>
        <input  class="text ui-widget-content ui-corner-all" type="text" name="endmonat" style="width: 40px;" />
        <input  class="text ui-widget-content ui-corner-all" type="text" name="endjahr" style="width: 80px;"/>
        -->
        <select name="endtag" style="width: 40px;">
            <?php for ($i = 1; $i <= 31; $i++) { ?>
                <option value="<?php echo $i ?>"><?php echo $i ?></option>
            <?php } ?>
        </select>
        <select name="endmonat" style="width: 40px;">
            <?php for ($i = 1; $i <= 12; $i++) { ?>
                <option value="<?php echo $i ?>"><?php echo $i ?></option>
            <?php } ?>
        </select>
        <select name="endjahr" style="width: 80px;">
            <?php for ($i = date('Y') - 2; $i <= date('Y'); $i++) { ?>
                <option value="<?php echo $i ?>" <?php
            if ($i == date('Y')) {
                echo 'selected="selected"';
            }
                ?>><?php echo $i ?></option>
                    <?php } ?>
        </select>
        (T)T/(M)M/JJJJ
        <input type="submit" style="display: none;" />
        <input type="hidden" id="hidden_kid" name="id" value="" />
    </form>
</div>

<div class="confirms dialog1" id="check_deaktivierung" title='Sind Sie sicher?'>
    <p>
        Sind Sie sicher, dass Sie diesen Kunden deaktivieren möchten? <br />
        <strong>HINWEIS: Es werden alle Abrechnungstage ab dem eingegebenen Datum gelöscht und können nicht wieder hergestellt werden.</strong>
    </p>
</div>