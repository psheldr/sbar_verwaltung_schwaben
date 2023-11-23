<?php
$master_slave_info = '';
$master_color = '#6C806C';
if ($kunde->getMaster()) {
    $master_color = '#0C800C';
    $master_slave_info = 'Masterkunde ' . $kunde->getMasternummer();
}
if (!$kunde->getMaster() && $kunde->getMasternummer()) {
    $masterkunde_objs = $kundeVerwaltung->findeMasterkundeZuMasternummer($kunde->getMasternummer());
    $masterkunde = $masterkunde_objs[0];
    $master_slave_info = 'Unterkunde zu ' . $masterkunde->getName() . ' ' . $kunde->getMasternummer();
}
?>

<?php if ($_REQUEST['what'] == 'edit') { ?>
    <h1>Kunden bearbeiten

        <?php if ($master_slave_info) { ?>
            <span style="font-size: 14px;font-weight: bold; color: <?php echo $master_color ?>;">
                <?php echo $master_slave_info ?>
            </span>
        <?php } ?>
    </h1>
    <?php include 'includes/kunde_navi.php' ?>
<?php } else { ?>
    <h1>Neuen Kunden anlegen

    </h1>
<?php } ?>



<?php if ($_REQUEST['do'] == 'neue_kategorie') { ?>
    <div id="neue_kat_box" style="float: left;width:50%;">
        <?php require 'view/neue_kategorie.tpl.php' ?>
    </div>
<?php } ?>


<form method="post" action="index.php?action=neuer_kunde&step=2<?php
if ($_REQUEST['what']) {
    echo '&what=edit&kid=' . $kunde->getId();
}
?>">

    <input type="submit" value="speichern" class="button" style="height:auto;padding: 5px;font-size: 18px;font-weight:bold;background: green;color: #fff;float:right;margin-right: 20px;margin-bottom: 10px;"/>
    <br />
    <div class="form_box" style="width:46%;float:left;margin-right:10px;margin-bottom:10px;">

        <h3>Stammdaten</h3>
        <p class="error_text">
            <?php echo $kunde->zeigeFehler() ?>
        </p>

        <?php
        $einrichtungsarten = array('Kindergarten', 'Hort', 'Krippe', 'Hort groß', 'Erwachsene');
        sort($einrichtungsarten);
        ?>
        <label class="form_label" for="">Einrichtungsart*</label>
        <select class="form_input" name="einrichtungsart">
            <option value="0">---Einrichtungsart wählen---</option>
            <?php foreach ($einrichtungsarten as $einrichtungsart) { ?>
                <option <?php
                if ($kunde->getEinrichtungsart() == $einrichtungsart) {
                    echo 'selected="selected"';
                }
                ?> value="<?php echo $einrichtungsart ?>" ><?php echo $einrichtungsart ?></option>
                <?php } ?>
        </select>
        <br />

        <label class="form_label" for="">Mengenkategorie*</label>
        <select class="form_input" name="einrichtungskategorie_id">
            <option value="0">---Mengenkategorie wählen---</option>
            <?php foreach ($einrichtungskategorien as $einrichtungskategorie) { ?>
                <option <?php
                if ($kunde->getEinrichtungskategorieId() == $einrichtungskategorie->getId()) {
                    echo 'selected="selected"';
                }
                ?> value="<?php echo $einrichtungskategorie->getId() ?>" ><?php echo $einrichtungskategorie->getBezeichnung() ?></option>
                <?php } ?>
        </select>
        <br />
        <a class="action_links " style="float: right;" href="index.php?action=neuer_kunde&do=neue_kategorie">Neue Kategorie</a>
        <br />


        <label class="form_label " for="">Standard</label>
        <input type="radio" name="kundentyp" value="1" class="cb_radio cb_radio_1" <?php
        if (!$kunde->getStaedtischerKunde() && !$kunde->getBioKunde()) {
            echo 'checked="checked"';
        }
        ?>  />
        <br />
        <label class="form_label " for="">Städt. Kunde</label>
        <input type="radio" name="kundentyp" value="2" class="cb_radio cb_radio_1" <?php
        if ($kunde->getStaedtischerKunde()) {
            echo 'checked="checked"';
        }
        ?>  />
        <br />
        <label class="form_label" for="">Bio Kunde</label>
        <input type="radio" name="kundentyp" value="3" class="cb_radio cb_radio_2" <?php
        if ($kunde->getBioKunde()) {
            echo 'checked="checked"';
        }
        ?> />
        <br />

        <label class="form_label">Startdatum</label>
        <input name='tbxDatum' type='text' id='c_datum' class='felddatum' size='13' maxlength='20' value="<?php
        if ($kunde->getStartdatum() > 0) {
            echo strftime('%d.%m.%Y', $kunde->getStartdatum());
        } else {
            echo '';
        }
        ?>"  />
        <input type='image' src="images/cal.gif" width="19" height="16" id='c_submit' name='btCalendar' alt='...' />
        <script type="text/javascript" language="javascript">
            Calendar.setup({
                inputField: "c_datum", // ID of the input field
                ifFormat: "%d.%m.%Y", // the date format
                button: "c_submit" // ID of the button
            });


        </script>
        <br />

        <label class="form_label" for="">Anzahl der angebotenen Speisen</label>
        <select name="anzahl_speisen">
            <?php for ($i = 1; $i <= 2; $i++) { ?>
                <option <?php
                if ($i == $kunde->getAnzahlSpeisen()) {
                    echo 'selected="selected"';
                }
                ?> value="<?php echo $i ?>"><?php echo $i ?></option>
                <?php } ?>
        </select>

        <br />


        <label class="form_label" for="">Einrichtungsname*</label>
        <input class="form_input" type="text" name="name" value="<?php echo $kunde->getName() ?>" /><br />

        <hr /> 
        <?php
        $sozialaemter_list = $sozialamtVerwaltung->findeAlle();
        ?>
        <h3>Träger</h3>
      
        <?php   $traeger = new Traeger();
        if ($kunde->getTraegerId()) {
            $traeger = $traegerVerwaltung->findeAnhandVonId($kunde->getTraegerId());
            ?>
            <p>Aktueller Träger: <strong><?php echo $traeger->getBezeichnung() ?></strong></p>
            <?php } else { ?>
            <p>Aktuell <strong>kein</strong> Träger zugeordnet</p>
            <?php }
        ?>

        <label class="small-15 left">
            <select name="traeger_id" id="" required="" style="width:100%;">
                <option value="0">
                    --- Träger wählen ---
                </option>
                <?php foreach ($sozialaemter_list as $traeger_s) { ?>
                    <option <?php
                    if ($traeger_s->getId() == $traeger->getId()) {
                        echo 'selected';
                    }
                    ?> value="<?php echo $traeger_s->getId() ?>">
                            <?php echo $traeger_s->getBezeichnung() ?>

                    </option>
                <?php } ?>
            </select> 
        </label>
        <br />
        <br />
        <a href="index.php?action=traeger&do=new" target="_blank" class="action_links_btns action_links">neuen Träger anlegen</a>
        <br />

        <br />
        <hr />


        <?php
        //finde alle Kundennummern für Unterkunden

        $master_kunden = $kundeVerwaltung->findeAlleMasternummern();
        $masternummern = array();
        foreach ($master_kunden as $master) {
            $masternummern[$master->getName()] = $master->getMasternummer();
        }

        if ($kunde->getMaster()) {
            //finde Unterkunden
            $unterkunden = $kundeVerwaltung->findeUnterkundenZuMaster($kunde->getMasternummer());
        }
        if (!$kunde->getMaster() && $kunde->getMasternummer()) {
            //finde Masterkunde
            //$masterkunde = $kundeVerwaltung->findeMasterkundeZuMasternummer($kunde->getMasternummer());
        }
        ?>
        <span id="hauptkunde_box">
            <label class="form_label" for="">Hauptkunde</label>
            <label class="form_input" style="border:none;<?php if ($kunde->getMaster()) { ?>font-size: 14px;font-weight: bold;color:<?php echo $master_color ?><?php } ?>">
                <input id="cb_masterkunde" class="" type="checkbox" name="master" value="1" <?php
                if ($kunde->getMaster()) {
                    echo 'checked="checked"';
                }
                ?> />
                Dies ist der Hauptkunde zu weiteren separaten "Unterkunden" (z.B. Gruppen/Klassen etc.). 
            </label>
            <br />
            <?php if (isset($unterkunden) && count($unterkunden)) { ?>
                <strong>Unterkunden:</strong><br />
                <?php foreach ($unterkunden as $unterkunde) {
                    ?>
                    <strong><?php echo $unterkunde->getName() ?> [<?php echo $unterkunde->getMasternummer() ?>]</strong><br />
                    <?php
                }
            }
            ?>
            <br />
        </span>



        <div class="<?php
        if (!$kunde->getMaster()) {
            echo 'hidden';
        }
        ?>" id="masternummer_box">
            <label class="form_label" for="">Kundennummer</label>
            <input class="form_input" type="text" id="masternummer_master" name="masternummer" value="<?php echo $kunde->getMasternummer() ?>" />
            <br />
            <p>Es muss eine Kundennummer vergeben werden. Diese steht dann zur Zuordnung für Unterkunden zur Verfügung.</p>
        </div>




        <span class="<?php
        if ($kunde->getMaster()) {
            echo 'hidden';
        }
        ?>" id="unterkunde_box">
            <label class="form_label" for="">Kundennummer</label>
            <select name="masternummer_unterkunde">
                <option value="">---Kundennummer wählen/kein Unterkunde---</option>
                <?php foreach ($masternummern as $kunde_name => $nummer) { ?>
                    <option <?php
                    if ($kunde->getMasternummer() == $nummer) {
                        echo 'selected';
                    }
                    ?> value="<?php echo $nummer ?>"><?php echo $nummer ?> - <?php echo $kunde_name ?></option>
                    <?php } ?>
            </select>
            <br />
            <p>
                Hier einen vorhandenen Masterkunden auswählen um <strong><?php echo $kunde->getname() ?></strong> als Unterkunden festzulegen.
            </p>
            <?php if (!$kunde->getMaster() && $kunde->getMasternummer()) { ?>
                <span class="" style="font-size: 14px;font-weight: bold;color:<?php echo $master_color ?>">Dies ist ein Unterkunde zur gewählten Kundennummer.</span>
                <br />
            <?php } ?>
            <?php if ($masterkunde !== NULL && $masterkunde->getId()) { ?>
                <strong>Hauptkunde: <?php echo $masterkunde->getName() ?> [<?php echo $masterkunde->getMasternummer() ?>]</strong><br />
            <?php } ?>

        </span>


        <script type="text/javascript">
            $(document).ready(function () {
                $('#cb_masterkunde').on('input', function () {
                    var status = $(this).prop('checked');
                    if (status === true) {
                        $('#masternummer_master').val('');
                        $('#masternummer_box').show();
                        $('#unterkunde_box').hide();
                    } else {
                        $('#masternummer_box').hide();
                        $('#unterkunde_box').show();
                    }
                });
            });
        </script>
        <br />
        <hr /> 



        <label class="form_label" for="">kitafino-ID

            <?php if ($kunde->getKundennummer()) { ?>
                <img src="images/kitafino_icon.png" />
            <?php } ?>
        </label>
        <input class="form_input" type="text" name="kundennummer" value="<?php echo $kunde->getKundennummer() ?>" /><br />

        <?php
        if ($kunde->getKundennummer()) {
            $kunden_zu_kitafino_id = $kundeVerwaltung->findeAlleMitKundennummer($kunde->getKundennummer());
            ?>

            <?php if (count($kunden_zu_kitafino_id) > 1) { ?>
                <label class="form_label" for="">weitere zu dieser kitafino-Nr</label>
                <div class="form_input">
                    <?php
                    $gruppen_zuordnungen = array();
                    foreach ($kunden_zu_kitafino_id as $weiterer_sbar_kunde_zu_pid) {
                        $gruppen_ids = explode(',', $weiterer_sbar_kunde_zu_pid->getKitafinoGruppen());
                        if ($weiterer_sbar_kunde_zu_pid->getKitafinoGruppen()) {
                            foreach ($gruppen_ids as $grid) {
                                $gruppen_zuordnungen[$grid] = $weiterer_sbar_kunde_zu_pid->getName();
                            }
                        } else {
                            $err = ' <span style="color: red;">Keine Gruppen zugewiesen' . '</span> ';
                        }
                        if ($weiterer_sbar_kunde_zu_pid->getId() == $kunde->getId()) {
                            continue;
                        }
                        ?>

                        <?php
                        echo $weiterer_sbar_kunde_zu_pid->getName() .
                        $err .
                        ' <a class="action_links" href="index.php?action=neuer_kunde&kid=' . $weiterer_sbar_kunde_zu_pid->getId() . '&what=edit">bearbeiten</a>'
                        . '<br />';
                        ?>

                    <?php }
                    ?>
                </div><br />
            <?php }
            ?>

            <?php
            $kitafino_starttermin = checkeKitafinoStarttermin($kunde->getKundennummer());
            $kitafino_starttermin2 = '';
            if (!$kitafino_starttermin['starttermin']) {
                $kitafino_starttermin2 = 'keine Angabe';
            }
            ?>        
            <label class="form_label" for="">Start bei kitafino</label>
            <input class="form_input" type="text" disabled value="<?php
            if ($kitafino_starttermin['starttermin']) {
                echo strftime('%d.%m.%Y', $kitafino_starttermin['starttermin']);
            } echo $kitafino_starttermin2
            ?>" id="" />
            <br />

            <label class="form_label" for="">kitafino Gruppen</label>
            <div class="form_input" >
                <?php
                $checkbox = '';
                $kitafino_gruppen_sbar = $kunde->getKitafinoGruppen();
                $kitafino_gruppen_sbar_arr = explode(',', $kitafino_gruppen_sbar);
                $kitafino_gruppen = findeGruppenAusKitafino($kunde->getKundennummer());
                $anzahl_kitafino_gruppen = count($kitafino_gruppen);
                $anzahl_gruppen_kunden_sbar = count($kunden_zu_kitafino_id);
                // if ($anzahl_kitafino_gruppen === 1 && $anzahl_kitafino_gruppen === $anzahl_gruppen_kunden_sbar) {
                //    $checkbox = 'checked';
                //}
                echo '<strong>Zugewiesen:</strong><br/>';
                $zugewiesene_grps = array();
                $count_zug = 0;
                foreach ($kitafino_gruppen as $kitafino_gruppe) {
                    $kitafino_gid = $kitafino_gruppe['id'];
                    foreach ($kitafino_gruppen_sbar_arr as $kitafino_grp_sbar) {
                        if ($kitafino_grp_sbar == $kitafino_gid) {
                            $zugewiesene_grps[] = $kitafino_gid;
                            $count_zug++;
                            echo '<span style="color: green;font-weight:bold;">' . $kitafino_gruppe['bezeichnung'] . '</span><br />';
                        }
                    }
                }
                if ($count_zug == 0) {
                    echo ' <span style="color: red;">Keine Gruppen zugewiesen' . '</span><br />';
                }
                echo '<strong>Auswahl:</strong><br/>';
                ?>

                <?php
                if ($anzahl_gruppen_kunden_sbar > $anzahl_kitafino_gruppen) {
                    echo '<strong style="color: red;">ACHTUNG! Es sind mehr S-Bar Kunden vorhanden als kitafino Gruppen. Bitte mit kitafino Rücksprache halten. (' . $anzahl_gruppen_kunden_sbar . '-' . $anzahl_kitafino_gruppen . ')</strong>';
                } else {
                    ?>
                    <?php
                    foreach ($kitafino_gruppen as $kitafino_gruppe) {
                        $checkbox = '';
                        if (array_search($kitafino_gruppe['id'], $zugewiesene_grps) !== false) {
                            $checkbox = 'checked';
                        }
                        if ($gruppen_zuordnungen[$kitafino_gruppe['id']] && $gruppen_zuordnungen[$kitafino_gruppe['id']] != $kunde->getName()) {
                            $checkbox = 'checked disabled';
                        }
                        ?>
                        <label><input type="checkbox" name="kitafino_gruppen[]" value="<?php echo $kitafino_gruppe['id'] ?>" <?php echo $checkbox ?>/>
                            <?php echo $kitafino_gruppe['bezeichnung'] ?> 
                            <?php ?>
                            (<?php echo $gruppen_zuordnungen[$kitafino_gruppe['id']] ?>)
                            <?php ?>
                        </label><br />
                        <?php
                    }
                    echo $anzahl_gruppen_kunden_sbar . ' - ' . $anzahl_kitafino_gruppen;
                    ?>
                    <input type="submit" value="ausgewählte Gruppen zuweisen"/>


                <?php } ?>
            </div><br />
            <?php
        }
        ?>

        <label class="form_label" for="">Ansprechpartner</label>
        <input class="form_input" type="text" name="asp" value="<?php echo $kunde->getAsp() ?>" id="" /><br />

        <label class="form_label" for="">Straße</label>
        <input class="form_input" type="text" name="strasse" value="<?php echo $kunde->getStrasse() ?>" id="" /><br />

        <label class="form_label" for="">PLZ</label>
        <input class="form_input" type="text" name="plz" value="<?php echo $kunde->getPlz() ?>" id="" /><br />

        <label class="form_label" for="">Ort</label>
        <input class="form_input" type="text" name="ort" value="<?php echo $kunde->getOrt() ?>" id="" /><br />

        <label class="form_label" for="">Telefon</label>
        <input class="form_input" type="text" name="telefon" value="<?php echo $kunde->getTelefon() ?>" id="" /><br />

        <label class="form_label" for="">Telefon 2</label>
        <input class="form_input" type="text" name="telefon_2" value="<?php echo $kunde->getTelefon2() ?>" id="" /><br />

        <label class="form_label" for="">Fax</label>
        <input class="form_input" type="text" name="fax" value="<?php echo $kunde->getFax() ?>" id="" /><br />

        <label class="form_label" for="">E-Mail</label>
        <input class="form_input" type="text" name="email" value="<?php echo $kunde->getEmail() ?>" id="" /><br />





        <input type="hidden" name="tour_id" value="<?php echo $kunde->getTourId() ?>" />
        <input type="hidden" name="lieferreihenfolge" value="<?php echo $kunde->getLieferreihenfolge() ?>" />
        <br />
    </div>

    <div class="form_box" style="width:48%;float:left;margin-bottom:10px;">

        <h3>Essenszeit und Informationen</h3>
        <label class="form_label" for="">Essenszeit</label>
        <input type="text" name="essenszeit_h" value="<?php echo substr($kunde->getEssenszeit(), 0, 2) ?>" id="essenszeit_h" />
        <input type="text" name="essenszeit_m" value="<?php echo substr($kunde->getEssenszeit(), 2, 2) ?>" id="essenszeit_m" />
        <br />   <hr />

        <label class="form_label" style="width:100%;margin-top: 10px;margin-bottom:-2px;" for="">Kundeninfo</label>
        <textarea rows="15" cols="15" class="form_input" style="width:100%;" name="kundeninfo" ><?php echo $kunde->getKundeninfo() ?></textarea>
        <br />

        <label class="form_label" style="width:100%;margin-top: 10px;margin-bottom:-2px;" for="">Fahrerinfo</label>
        <textarea rows="10" cols="15" class="form_input" style="width:100%;" name="fahrerinfo" ><?php echo $kunde->getFahrerinfo() ?></textarea>


        <label class="form_label" style="width:100%;margin-top: 10px;margin-bottom:-2px;" for="">Wird Wärmeplatte benötigt? <a class="action_links_btns action_links" onclick="var txt = $('#bemerkung_kunde_txt').html();txt = txt+'+ Wärmeplatte';$('#bemerkung_kunde_txt').html(txt);">Ja, Notiz ergänzen.*</a>*Kunde muss gespeichert werden!</label>
		
        <textarea rows="3" cols="10" id="bemerkung_kunde_txt" style="width:100%;" class="form_input" name="bemerkung_kunde" ><?php echo $kunde->getBemerkungKunde() ?></textarea><br />


         <label class="form_label" style="display:none;width:100%;margin-top: 10px;margin-bottom:-2px;" for="">Diäten/Bemerkung bezgl. Speisen etc. (erscheint auf XLS)</label>
        <textarea rows="3" cols="10"  style="display:none; width:100%;" class="form_input" name="bemerkung" ><?php echo $kunde->getBemerkung() ?></textarea><br /> 


    </div>

    <div class="form_box" style="width:46%;float:left;margin-right:10px;">
        <h3>Rechnungsinformationen</h3>
        <label class="form_label" for="">Lexware-Notizen</label>
        <input class="form_input" type="text" name="lexware" value="<?php echo $kunde->getLexware() ?>" /><br />

        <label class="form_label" for="">Preis pro Portion</label>
        <input class="form_input" type="text" name="preis" value="<?php echo $kunde->getPreis() ?>" /><br />

        <label class="form_label" for="">Artikelbezeichnung</label>
        <input class="form_input" type="text" name="artikelbezeichnung" value="<?php echo $kunde->getArtikelbezeichnung() ?>" /><br />
    </div>
    <div class="form_box" style="width:48%;float:left">
        <h3>Rechnungsadresse</h3>
        <input type="hidden" name="rechad_id" value="<?php echo $rechnungsadresse->getId() ?>" />
        <input type="hidden" name="rechad_kunde_id" value="<?php echo $kunde->getId() ?>" />

        <p>
            <input type="checkbox" name="rechad_aktiv" value="1" id="rechad_aktiv" <?php
            if ($rechnungsadresse->getAktiv() == 1) {
                echo 'checked="checked"';
            }
            ?> />
            <label for="rechad_aktiv">Diese Rechnungsadresse verwenden</label>
        </p>

        <label class="form_label" for="">Firma</label>
        <input class="form_input" type="text" name="rechad_firma" value="<?php echo $rechnungsadresse->getFirma() ?>" /><br />

        <label class="form_label" for="">Vorname</label>
        <input class="form_input" type="text" name="rechad_vorname" value="<?php echo $rechnungsadresse->getVorname() ?>" /><br />

        <label class="form_label" for="">Nachname</label>
        <input class="form_input" type="text" name="rechad_nachname" value="<?php echo $rechnungsadresse->getNachname() ?>" /><br />

        <label class="form_label" for="">Straße</label>
        <input class="form_input" type="text" name="rechad_strasse" value="<?php echo $rechnungsadresse->getStrasse() ?>" /><br />

        <label class="form_label" for="">PLZ</label>
        <input class="form_input" type="text" name="rechad_plz" value="<?php echo $rechnungsadresse->getPlz() ?>" /><br />

        <label class="form_label" for="">Ort</label>
        <input class="form_input" type="text" name="rechad_ort" value="<?php echo $rechnungsadresse->getOrt() ?>" /><br />

        <label class="form_label" for="">E-Mail</label>
        <input class="form_input" type="text" name="rechad_email" value="<?php echo $rechnungsadresse->getEmail() ?>" /><br />
    </div><br />



    <input type="hidden" name="id" value="<?php echo $kunde->getId() ?>" /><input type="submit" value="speichern" class="button" style="height:auto;padding: 5px;font-size: 18px;font-weight:bold;background: green;color: #fff;float:right;margin-right: 20px;margin-bottom: 10px;"/>

</form>
<div class="clear"></div>


<script>

</script>