<script type="text/javascript">
    function changeAlarm(test) {
        test.style.color = "red";
    }
</script>
<?php if ($_REQUEST['info'] == 'kitafino_del') { ?>

    <p class="error_text">
        Die kitafino Zuordnng für <strong><?php echo $kunde->getName() ?></strong> wurde entfernt. Portionen prüfen!
    </p>
<?php } else { ?>
    <p>
        Die Standardportionen für <strong><?php echo $kunde->getName() ?></strong> wurden geändert.
    </p>
<?php } ?>
<table style="width: 280px;">
    <tr>
        <th colspan="6" style="background:#aaa;">Standardportionen</th>
    </tr>
    <tr style="background:#ccc;">
        <td>#</td>
        <td>MO</td>
        <td>DI</td>
        <td>MI</td>
        <td>DO</td>
        <td>FR</td>
    </tr>

    <?php if (!$kunde->getStaedtischerKunde() && !$kunde->getBioKunde()) { ?>
        <tr style="font-weight: bold;">
            <td>Speise 1</td>
            <td><?php echo $standardportionen->getPortionenMo() ?></td>
            <td><?php echo $standardportionen->getPortionenDi() ?></td>
            <td><?php echo $standardportionen->getPortionenMi() ?></td>
            <td><?php echo $standardportionen->getPortionenDo() ?></td>
            <td><?php echo $standardportionen->getPortionenFr() ?></td>
        </tr>
    <?php } ?>


    <?php if (!$kunde->getStaedtischerKunde() && !$kunde->getBioKunde() && $kunde->getAnzahlSpeisen() > 1) { ?>
        <tr style="font-weight: bold;">
            <td>Speise 2</td>
            <td><?php echo $standardportionen2->getPortionenMo() ?></td>
            <td><?php echo $standardportionen2->getPortionenDi() ?></td>
            <td><?php echo $standardportionen2->getPortionenMi() ?></td>
            <td><?php echo $standardportionen2->getPortionenDo() ?></td>
            <td><?php echo $standardportionen2->getPortionenFr() ?></td>
        </tr>
    <?php } ?>

    <?php if ($kunde->getStaedtischerKunde() || $kunde->getBioKunde()) { ?>
        <tr style="font-weight: bold;">
            <td>Speise 3</td>
            <td><?php echo $standardportionen3->getPortionenMo() ?></td>
            <td><?php echo $standardportionen3->getPortionenDi() ?></td>
            <td><?php echo $standardportionen3->getPortionenMi() ?></td>
            <td><?php echo $standardportionen3->getPortionenDo() ?></td>
            <td><?php echo $standardportionen3->getPortionenFr() ?></td>
        </tr>
    <?php } ?>

    <?php if ($kunde->getStaedtischerKunde() || ($kunde->getBioKunde() && $kunde->getAnzahlSpeisen() > 1)) { ?>
        <tr style="font-weight: bold;">
            <td>Speise 4</td>
            <td><?php echo $standardportionen4->getPortionenMo() ?></td>
            <td><?php echo $standardportionen4->getPortionenDi() ?></td>
            <td><?php echo $standardportionen4->getPortionenMi() ?></td>
            <td><?php echo $standardportionen4->getPortionenDo() ?></td>
            <td><?php echo $standardportionen4->getPortionenFr() ?></td>
        </tr>
    <?php } ?>

</table>
<p>
    Für diesen Kunden liegen für folgende Wochen bereits gespeicherte Portionen vor. Bitte prüfen Sie ob die folgenden Angaben korrekt sind.
</p>
<?php
if (!$kunde->getStaedtischerKunde() && !$kunde->getBioKunde()) {
    $index_count = 0;
    foreach ($eingetragene_port_aenderungen as $eingetragene_port_aenderung) {
        $bereits_gespeichert = false;
        $edited_style ='';
        if ($_SESSION['edited_portaends'] && array_search($eingetragene_port_aenderung->getId(), $_SESSION['edited_portaends'][$kunde->getId()]) !== false) {
        $bereits_gespeichert = true;
        $edited_style ='';
            
        }
        ?>
<a name="<?php echo $eingetragene_port_aenderung->getWochenstarttagts() ?>"></a>
        <table>
            <tr>
                <th colspan="6">Woche ab <?php echo strftime('%d.%m.%Y', $eingetragene_port_aenderung->getWochenstarttagts()) ?></th>
                <?php if ($bereits_gespeichert) { ?>
                <th style="background: green;color:#fff;">
                    gespeichert
                </th>
                <?php } ?>
            </tr>
            <tr>
                <th>#</th>
                <th>Mo</th>
                <th>Di</th>
                <th>Mi</th>
                <th>Do</th>
                <th>Fr</th>
            </tr>
            <form method="post" action="index.php?action=pruefe_portionen&do=save">
                <input type="hidden" name="id" value="<?php echo $eingetragene_port_aenderung->getId() ?>" />
                <input type="hidden" name="snr" value="1" />
                <input type="hidden" name="kunde_id" value="<?php echo $eingetragene_port_aenderung->getKundeId() ?>" />
                <input type="hidden" name="wochenstarttagts" value="<?php echo $eingetragene_port_aenderung->getWochenstarttagts() ?>" />
                <tr>
                    <td>Speise 1</td>
                    <td>
                        <input onkeyup="changeAlarm(this)" class="portionen_input" type="text" name="portionen_mo" value="<?php echo $eingetragene_port_aenderung->getPortionenMo() ?>" />
                    </td>
                    <td>
                        <input onkeyup="changeAlarm(this)" class="portionen_input" type="text" name="portionen_di" value="<?php echo $eingetragene_port_aenderung->getPortionenDi() ?>" />
                    </td>
                    <td>
                        <input onkeyup="changeAlarm(this)" class="portionen_input" type="text" name="portionen_mi" value="<?php echo $eingetragene_port_aenderung->getPortionenMi() ?>" />
                    </td>
                    <td>
                        <input onkeyup="changeAlarm(this)" class="portionen_input" type="text" name="portionen_do" value="<?php echo $eingetragene_port_aenderung->getPortionenDo() ?>" />
                    </td>
                    <td>
                        <input onkeyup="changeAlarm(this)" class="portionen_input" type="text" name="portionen_fr" value="<?php echo $eingetragene_port_aenderung->getPortionenFr() ?>" />
                    </td>
                    <td>
                        <input type="hidden" name="starttag" value="<?php echo $eingetragene_port_aenderung->getStarttag() ?>" />
                        <input type="hidden" name="startmonat" value="<?php echo $eingetragene_port_aenderung->getStartmonat() ?>" />
                        <input type="hidden" name="startjahr" value="<?php echo $eingetragene_port_aenderung->getStartjahr() ?>" />
                        <input style="height: 25px;" type="submit" name="save_without" value="Mit hier eingetragenen Werten speichern" />
                    </td>
                </tr>
                <tr>
                    <td></td>
                    <td><?php echo $standardportionen->getPortionenMo() ?></td>
                    <td><?php echo $standardportionen->getPortionenDi() ?></td>
                    <td><?php echo $standardportionen->getPortionenMi() ?></td>
                    <td><?php echo $standardportionen->getPortionenDo() ?></td>
                    <td><?php echo $standardportionen->getPortionenFr() ?></td>
                    <td><input style="height: 25px;" type="submit" name="save_with" value="Mit neuen Standardportionen speichern" /></td>
                </tr>
            </form>

        </table>
        <?php
        $index_count++;
    }
}
?>



<?php
if (!$kunde->getStaedtischerKunde() && !$kunde->getBioKunde() && $kunde->getAnzahlSpeisen() > 1) {

    $index_count = 0;
    foreach ($eingetragene_port_aenderungen2 as $eingetragene_port_aenderung) {
        ?>
        <table>
            <tr>
                <th colspan="5">Woche ab <?php echo strftime('%d.%m.%Y', $eingetragene_port_aenderung->getWochenstarttagts()) ?></th>
            </tr>
            <tr>
                <th>#</th>
                <th>Mo</th>
                <th>Di</th>
                <th>Mi</th>
                <th>Do</th>
                <th>Fr</th>
            </tr>


            <form method="post" action="index.php?action=pruefe_portionen&do=save">
                <input type="hidden" name="snr" value="2" />
                <input type="hidden" name="id" value="<?php echo $eingetragene_port_aenderungen2[$index_count]->getId() ?>" />
                <input type="hidden" name="kunde_id" value="<?php echo $eingetragene_port_aenderungen2[$index_count]->getKundeId() ?>" />
                <input type="hidden" name="wochenstarttagts" value="<?php echo $eingetragene_port_aenderungen2[$index_count]->getWochenstarttagts() ?>" />
                <tr>
                    <td>Speise 2</td>
                    <td>
                        <input onkeyup="changeAlarm(this)" class="portionen_input" type="text" name="portionen_mo" value="<?php echo $eingetragene_port_aenderungen2[$index_count]->getPortionenMo() ?>" />
                    </td>
                    <td>
                        <input onkeyup="changeAlarm(this)" class="portionen_input" type="text" name="portionen_di" value="<?php echo $eingetragene_port_aenderungen2[$index_count]->getPortionenDi() ?>" />
                    </td>
                    <td>
                        <input onkeyup="changeAlarm(this)" class="portionen_input" type="text" name="portionen_mi" value="<?php echo $eingetragene_port_aenderungen2[$index_count]->getPortionenMi() ?>" />
                    </td>
                    <td>
                        <input onkeyup="changeAlarm(this)" class="portionen_input" type="text" name="portionen_do" value="<?php echo $eingetragene_port_aenderungen2[$index_count]->getPortionenDo() ?>" />
                    </td>
                    <td>
                        <input onkeyup="changeAlarm(this)" class="portionen_input" type="text" name="portionen_fr" value="<?php echo $eingetragene_port_aenderungen2[$index_count]->getPortionenFr() ?>" />
                    </td>
                    <td>
                        <input type="hidden" name="starttag" value="<?php echo $eingetragene_port_aenderungen2[$index_count]->getStarttag() ?>" />
                        <input type="hidden" name="startmonat" value="<?php echo $eingetragene_port_aenderungen2[$index_count]->getStartmonat() ?>" />
                        <input type="hidden" name="startjahr" value="<?php echo $eingetragene_port_aenderungen2[$index_count]->getStartjahr() ?>" />
                        <input style="height: 25px;" type="submit" name="save_without" value="Mit hier eingetragenen Werten speichern" />
                    </td>
                </tr>
                <tr>
                    <td></td>
                    <td><?php echo $standardportionen2->getPortionenMo() ?></td>
                    <td><?php echo $standardportionen2->getPortionenDi() ?></td>
                    <td><?php echo $standardportionen2->getPortionenMi() ?></td>
                    <td><?php echo $standardportionen2->getPortionenDo() ?></td>
                    <td><?php echo $standardportionen2->getPortionenFr() ?></td>
                    <td><input style="height: 25px;" type="submit" name="save_with" value="Mit neuen Standardportionen speichern" /></td>
                </tr>    
            </form>    


        </table>
        <?php
        $index_count++;
    }
}
?>



<?php
if ($kunde->getStaedtischerKunde() || $kunde->getBioKunde()) {

    $index_count = 0;
    foreach ($eingetragene_port_aenderungen3 as $eingetragene_port_aenderung) {
        ?>
        <table>
            <tr>
                <th colspan="5">Woche ab <?php echo strftime('%d.%m.%Y', $eingetragene_port_aenderung->getWochenstarttagts()) ?></th>
            </tr>
            <tr>
                <th>#</th>
                <th>Mo</th>
                <th>Di</th>
                <th>Mi</th>
                <th>Do</th>
                <th>Fr</th>
            </tr>


            <form method="post" action="index.php?action=pruefe_portionen&do=save">
                <input type="hidden" name="snr" value="3" />
                <input type="hidden" name="id" value="<?php echo $eingetragene_port_aenderungen3[$index_count]->getId() ?>" />
                <input type="hidden" name="kunde_id" value="<?php echo $eingetragene_port_aenderungen3[$index_count]->getKundeId() ?>" />
                <input type="hidden" name="wochenstarttagts" value="<?php echo $eingetragene_port_aenderungen3[$index_count]->getWochenstarttagts() ?>" />
                <tr>
                    <td>Speise 3</td>
                    <td>
                        <input onkeyup="changeAlarm(this)" class="portionen_input" type="text" name="portionen_mo" value="<?php echo $eingetragene_port_aenderungen3[$index_count]->getPortionenMo() ?>" />
                    </td>
                    <td>
                        <input onkeyup="changeAlarm(this)" class="portionen_input" type="text" name="portionen_di" value="<?php echo $eingetragene_port_aenderungen3[$index_count]->getPortionenDi() ?>" />
                    </td>
                    <td>
                        <input onkeyup="changeAlarm(this)" class="portionen_input" type="text" name="portionen_mi" value="<?php echo $eingetragene_port_aenderungen3[$index_count]->getPortionenMi() ?>" />
                    </td>
                    <td>
                        <input onkeyup="changeAlarm(this)" class="portionen_input" type="text" name="portionen_do" value="<?php echo $eingetragene_port_aenderungen3[$index_count]->getPortionenDo() ?>" />
                    </td>
                    <td>
                        <input onkeyup="changeAlarm(this)" class="portionen_input" type="text" name="portionen_fr" value="<?php echo $eingetragene_port_aenderungen3[$index_count]->getPortionenFr() ?>" />
                    </td>
                    <td>
                        <input type="hidden" name="starttag" value="<?php echo $eingetragene_port_aenderungen3[$index_count]->getStarttag() ?>" />
                        <input type="hidden" name="startmonat" value="<?php echo $eingetragene_port_aenderungen3[$index_count]->getStartmonat() ?>" />
                        <input type="hidden" name="startjahr" value="<?php echo $eingetragene_port_aenderungen3[$index_count]->getStartjahr() ?>" />
                        <input style="height: 25px;" type="submit" name="save_without" value="Mit hier eingetragenen Werten speichern" />
                    </td>
                </tr>
                <tr>
                    <td></td>
                    <td><?php echo $standardportionen3->getPortionenMo() ?></td>
                    <td><?php echo $standardportionen3->getPortionenDi() ?></td>
                    <td><?php echo $standardportionen3->getPortionenMi() ?></td>
                    <td><?php echo $standardportionen3->getPortionenDo() ?></td>
                    <td><?php echo $standardportionen3->getPortionenFr() ?></td>
                    <td><input style="height: 25px;" type="submit" name="save_with" value="Mit neuen Standardportionen speichern" /></td>
                </tr>    
            </form>    


        </table>
        <?php
        $index_count++;
    }
}
?>




<?php
if ($kunde->getStaedtischerKunde() || ($kunde->getBioKunde() && $kunde->getAnzahlSpeisen() > 1)) {

    $index_count = 0;
    foreach ($eingetragene_port_aenderungen4 as $eingetragene_port_aenderung) {
        ?>
        <table>
            <tr>
                <th colspan="5">Woche ab <?php echo strftime('%d.%m.%Y', $eingetragene_port_aenderung->getWochenstarttagts()) ?></th>
            </tr>
            <tr>
                <th>#</th>
                <th>Mo</th>
                <th>Di</th>
                <th>Mi</th>
                <th>Do</th>
                <th>Fr</th>
            </tr>


            <form method="post" action="index.php?action=pruefe_portionen&do=save">
                <input type="hidden" name="snr" value="4" />
                <input type="hidden" name="id" value="<?php echo $eingetragene_port_aenderungen4[$index_count]->getId() ?>" />
                <input type="hidden" name="kunde_id" value="<?php echo $eingetragene_port_aenderungen4[$index_count]->getKundeId() ?>" />
                <input type="hidden" name="wochenstarttagts" value="<?php echo $eingetragene_port_aenderungen4[$index_count]->getWochenstarttagts() ?>" />
                <tr>
                    <td>Speise 4</td>
                    <td>
                        <input onkeyup="changeAlarm(this)" class="portionen_input" type="text" name="portionen_mo" value="<?php echo $eingetragene_port_aenderungen4[$index_count]->getPortionenMo() ?>" />
                    </td>
                    <td>
                        <input onkeyup="changeAlarm(this)" class="portionen_input" type="text" name="portionen_di" value="<?php echo $eingetragene_port_aenderungen4[$index_count]->getPortionenDi() ?>" />
                    </td>
                    <td>
                        <input onkeyup="changeAlarm(this)" class="portionen_input" type="text" name="portionen_mi" value="<?php echo $eingetragene_port_aenderungen4[$index_count]->getPortionenMi() ?>" />
                    </td>
                    <td>
                        <input onkeyup="changeAlarm(this)" class="portionen_input" type="text" name="portionen_do" value="<?php echo $eingetragene_port_aenderungen4[$index_count]->getPortionenDo() ?>" />
                    </td>
                    <td>
                        <input onkeyup="changeAlarm(this)" class="portionen_input" type="text" name="portionen_fr" value="<?php echo $eingetragene_port_aenderungen4[$index_count]->getPortionenFr() ?>" />
                    </td>
                    <td>
                        <input type="hidden" name="starttag" value="<?php echo $eingetragene_port_aenderungen4[$index_count]->getStarttag() ?>" />
                        <input type="hidden" name="startmonat" value="<?php echo $eingetragene_port_aenderungen4[$index_count]->getStartmonat() ?>" />
                        <input type="hidden" name="startjahr" value="<?php echo $eingetragene_port_aenderungen4[$index_count]->getStartjahr() ?>" />
                        <input style="height: 25px;" type="submit" name="save_without" value="Mit hier eingetragenen Werten speichern" />
                    </td>
                </tr>
                <tr>
                    <td></td>
                    <td><?php echo $standardportionen4->getPortionenMo() ?></td>
                    <td><?php echo $standardportionen4->getPortionenDi() ?></td>
                    <td><?php echo $standardportionen4->getPortionenMi() ?></td>
                    <td><?php echo $standardportionen4->getPortionenDo() ?></td>
                    <td><?php echo $standardportionen4->getPortionenFr() ?></td>
                    <td><input style="height: 25px;" type="submit" name="save_with" value="Mit neuen Standardportionen speichern" /></td>
                </tr>    
            </form>    


        </table>
        <?php
        $index_count++;
    }
}
?>

