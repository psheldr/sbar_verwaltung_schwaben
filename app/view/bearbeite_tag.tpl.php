<a class="action_links" href="index.php?action=speiseplaene"><<< Zurück zur Übersicht</a>
<?php
$bestellung = $bestellungVerwaltung->findeAnhandVonTagMonatJahr($tag, $monat, $jahr);
if ($bestellung->getId()) {
    $bestellung_has_speisen1 = $bestellung_has_speiseVerwaltung->findeAnhandVonBestellungIdUndSpeiseNr($bestellung->getId(), 1);
    $bestellung_has_speisen2 = $bestellung_has_speiseVerwaltung->findeAnhandVonBestellungIdUndSpeiseNr($bestellung->getId(), 2);
    $bestellung_has_speisen3 = $bestellung_has_speiseVerwaltung->findeAnhandVonBestellungIdUndSpeiseNr($bestellung->getId(), 3);
    $bestellung_has_speisen4 = $bestellung_has_speiseVerwaltung->findeAnhandVonBestellungIdUndSpeiseNr($bestellung->getId(), 4);
}
/*
  if (count($bestellung_has_speisen1) == 0) {
  $obj_s1 = new BestellungHasSpeise();
  $bestellung_has_speisen1 = array($obj_s1);
  }
  if (count($bestellung_has_speisen2) == 0) {
  $obj_s2 = new BestellungHasSpeise();
  $bestellung_has_speisen2 = array($obj_s2);
  } */
$menunamen = $menunamenVerwaltung->findeAlleZuDatum($tag, $monat, $jahr);
$menunamen_strings = array();
$menunamen_intern_strings = array();
$fahrer_extra_strings = array();
foreach ($menunamen as $menuname_obj) {
    $menunamen_strings[$menuname_obj->getSpeiseNr()] = $menuname_obj->getBezeichnung();
    $menunamen_intern_strings[$menuname_obj->getSpeiseNr()] = $menuname_obj->getBezeichnungIntern();
    $fahrer_extra_strings[$menuname_obj->getSpeiseNr()] = $menuname_obj->getFahrerExtra();
}
?>

<form method="post" action="index.php?action=bestellung_anlegen&tag2=<?php echo $tag ?>&monat=<?php echo $monat ?>&jahr=<?php echo $jahr ?>&starttag=<?php echo $starttag ?>&startmonat=<?php echo $startmonat ?>&startjahr=<?php echo $startjahr ?>">
    <input type="hidden" name="kunde_id" value="0" />
    <input type="hidden" name="tag" value="<?php echo mktime(12, 0, 0, $monat, $tag, $jahr) ?>" />
    <input type="hidden" name="tag2" value="<?php echo $tag ?>" />
    <input type="hidden" name="monat" value="<?php echo $monat ?>" />
    <input type="hidden" name="jahr" value="<?php echo $jahr ?>" />
    <input type="hidden" name="id" value="<?php echo $bestellung->getId() ?>" />
    <table border="1" id="table_s1_s2">
        <tr>
            <th colspan="2"><?php echo strftime('%a %d.%m.', mktime(12, 0, 0, $monat, $tag, $jahr)) ?></th>
        </tr>
        <tr>
            <th>Speise 1</th>
            <th>Speise 2</th>
        </tr>

        <tr>
            <td style="border-bottom: 2px solid #444; background: <?php echo $color_speisen[1] ?>;">
                <?php 
                
                ?>
                Menüname:
                <input style="width:100%;" type="text"  value="<?php echo $menunamen_strings[1] ?>" name="menunamen[1]" />
            </td>
            <td style="border-bottom: 2px solid #444; background: <?php echo $color_speisen[2] ?>;">
                Menüname:
                <input style="width:100%;" type="text"  value="<?php echo $menunamen_strings[2] ?>" name="menunamen[2]"/>
            </td>
        </tr>
        <tr>
            <td style="border-bottom: 2px solid #444; background: <?php echo $color_speisen[1] ?>;">
                <?php 
                
                ?>
                Menüname intern:
                <input style="width:100%;" type="text"  value="<?php echo $menunamen_intern_strings[1] ?>" name="menunamen_intern[1]"/>
            </td>
            <td style="border-bottom: 2px solid #444; background: <?php echo $color_speisen[2] ?>;">
                Menüname intern:
                <input style="width:100%;" type="text"  value="<?php echo $menunamen_intern_strings[2] ?>" name="menunamen_intern[2]"/>
            </td>
        </tr>
        <tr>
            <td style="border-bottom: 2px solid #444; background: <?php echo $color_speisen[1] ?>;">
                <?php 
                
                ?>
                Fahrer Extra (max. 35 Zeichen):
                <input style="width:100%;" type="text"  value="<?php echo $fahrer_extra_strings[1] ?>" name="fahrer_extra[1]" maxlength="35"/>
            </td>
            <td style="border-bottom: 2px solid #444; background: <?php echo $color_speisen[2] ?>;">
                Fahrer Extra (max. 35 Zeichen):
                <input style="width:100%;" type="text"  value="<?php echo $fahrer_extra_strings[2] ?>" name="fahrer_extra[2]" maxlength="35"/>
            </td>
        </tr>

        <tr>
            <td>
                <label>
                    <input
                    <?php
                    if ($bestellung->getId() && isset($bestellung_has_speisen1[0]) && $bestellung_has_speisen1[0]->getKitafinoSpeiseNr() == 1) {
                        echo 'checked';
                    }
                    ?>
                        type="radio" name="fleischgericht" value="1" />
                    Fleischgericht (kitafino Speise 1)
                </label>
            </td>
            <td>
                <label>
                    <input
                    <?php
                    if ($bestellung->getId() && isset($bestellung_has_speisen2[0]) && $bestellung_has_speisen2[0]->getKitafinoSpeiseNr() == 1) {
                        echo 'checked';
                    }
                    ?> type="radio" name="fleischgericht" value="2" />
                    Fleischgericht (kitafino Speise 1)
                </label>
            </td>
        </tr>



        <?php for ($i = 0; $i <= 10; $i++) {
            ?>
            <tr>
                <td >
                    <select name="speise_ids[]" style="">
                        <option value="0">--- Speise oder Komponente wählen ---</option>
                        <?php foreach ($speisen as $speise) {
                            ?>
                            <option value="<?php echo $speise->getId() ?>" <?php
                            if (isset($bestellung_has_speisen1[$i])) {
                                if ($bestellung_has_speisen1[$i]->getSpeiseId() == $speise->getId() && $bestellung_has_speisen1[$i]->getSpeiseNr() == 1) {
                                    echo 'selected="selected"';
                                }
                            }
                            ?>><?php echo $speise->getBezeichnung() ?></option>
                                <?php } ?>
                    </select>
                </td>
                <td >
                    <select name="speise2_ids[]" style="">
                        <option value="0">--- Speise oder Komponente wählen ---</option>
                        <?php foreach ($speisen as $speise) { ?>
                            <option value="<?php echo $speise->getId() ?>" <?php
                            if (isset($bestellung_has_speisen2[$i])) {
                                if ($bestellung_has_speisen2[$i]->getSpeiseId() == $speise->getId() && $bestellung_has_speisen2[$i]->getSpeiseNr() == 2) {
                                    echo 'selected="selected"';
                                }
                            }
                            ?>><?php echo $speise->getBezeichnung() ?></option>
                                <?php } ?>
                    </select>

                </td>
            </tr>
        <?php } ?>

    </table>
    
    
    
    
    <table border="1" id="table_s3_s4" >
        
        <tr>
            <th>Speise 3 - BIO und Stadt Nürnberg - Menü 1</th>
            <th>Speise 4  -  BIO und Stadt Nürnberg Alternative - Menü 2</th>
        </tr>


       
        <tr>
            <td style="border-bottom: 2px solid #444;background: <?php echo $color_speisen[3] ?>;">
                Menüname:
                <input style="width:100%;" type="text"  value="<?php echo $menunamen_strings[3] ?>" name="menunamen[3]"/>
            </td>
            <td style="border-bottom: 2px solid #444;background: <?php echo $color_speisen[4] ?>;">
                Menüname:
                <input style="width:100%;" type="text"  value="<?php echo $menunamen_strings[4] ?>" name="menunamen[4]"/>
            </td>
        </tr>
        <tr>
            <td style="border-bottom: 2px solid #444;background: <?php echo $color_speisen[3] ?>;">
                Menüname intern:
                <input style="width:100%;" type="text"  value="<?php echo $menunamen_intern_strings[3] ?>" name="menunamen_intern[3]"/>
            </td>
            <td style="border-bottom: 2px solid #444;background: <?php echo $color_speisen[4] ?>;">
                Menüname intern:
                <input style="width:100%;" type="text"  value="<?php echo $menunamen_intern_strings[4] ?>" name="menunamen_intern[4]"/>
            </td>
        </tr>
        <tr>
            <td style="border-bottom: 2px solid #444; background: <?php echo $color_speisen[3] ?>;">
                Fahrer Extra (max. 35 Zeichen):
                <input style="width:100%;" type="text"  value="<?php echo $fahrer_extra_strings[3] ?>" name="fahrer_extra[3]" maxlength="35"/>
            </td>
            <td style="border-bottom: 2px solid #444; background: <?php echo $color_speisen[4] ?>;">
                Fahrer Extra (max. 35 Zeichen):
                <input style="width:100%;" type="text"  value="<?php echo $fahrer_extra_strings[4] ?>" name="fahrer_extra[4]" maxlength="35"/>
            </td>
        </tr>



        <?php for ($i = 0; $i <= 10; $i++) {
            ?>
            <tr>
                <td >
                    <select name="speise3_ids[]" style="">
                        <option value="0">--- Speise oder Komponente wählen ---</option>
                        <?php foreach ($speisen as $speise) {
                            ?>
                            <option value="<?php echo $speise->getId() ?>" <?php
                            if (isset($bestellung_has_speisen3[$i])) {
                                if ($bestellung_has_speisen3[$i]->getSpeiseId() == $speise->getId() && $bestellung_has_speisen3[$i]->getSpeiseNr() == 3) {
                                    echo 'selected="selected"';
                                }
                            }
                            ?>><?php echo $speise->getBezeichnung() ?></option>
                                <?php } ?>
                    </select>
                </td>
                <td >
                    <select name="speise4_ids[]" style="">
                        <option value="0">--- Speise oder Komponente wählen ---</option>
                        <?php foreach ($speisen as $speise) { ?>
                            <option value="<?php echo $speise->getId() ?>" <?php
                            if (isset($bestellung_has_speisen4[$i])) {
                                if ($bestellung_has_speisen4[$i]->getSpeiseId() == $speise->getId() && $bestellung_has_speisen4[$i]->getSpeiseNr() == 4) {
                                    echo 'selected="selected"';
                                }
                            }
                            ?>><?php echo $speise->getBezeichnung() ?></option>
                                <?php } ?>
                    </select>

                </td>
            </tr>
        <?php } ?>

    </table>
    
    <input type="submit" value="speichern" style="width: 500px;" />
</form>