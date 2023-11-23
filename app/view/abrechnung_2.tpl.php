<p>
    <?php for($i = 2013;$i <= date('Y');$i++) { ?>
    <a <?php if($jahr == $i) {echo 'class="active_action_links"';} else {echo 'class="action_links"';} ?> href="index.php?action=abrechnung&jahr=<?php echo $i ?>"><?php echo $i ?></a>
    <?php } ?>
</p>
<form method="post" action="index.php?action=abrechnung">
<select name="monat">
    <?php for($i = 1; $i <= 12; $i++) { ?>
    <option value="<?php echo $i ?>"><?php echo $i ?></option>
    <?php } ?>

</select>
<select name="jahr">
    <?php for($i = 2012; $i <= date('Y'); $i++) { ?>
    <option value="<?php echo $i ?>"><?php echo $i ?></option>
    <?php } ?>
</select>
    <input type="submit" value="anzeigen" />
    </form>

<?php

    $okt_add = 0;
    switch ($monat) {
        case 1:
            $tage = 31;
            break;
        case 2:
            if($jahr % 400 == 0 || ($jahr % 4 == 0 && $jahr % 100 != 0)) {
                $tage = 29;
            } else {
                $tage = 28;
            }
            break;
        case 3:
            $tage = 31;
            break;
        case 4:
            $tage = 30;
            break;
        case 5:
            $tage = 31;
            break;
        case 6:
            $tage = 30;
            break;
        case 7:
            $tage = 31;
            break;
        case 8:
            $tage = 31;
            break;
        case 9:
            $tage = 30;
            break;
        case 10:
            $tage = 31;
            $okt_add = 3600;
            break;
        case 11:
            $tage = 30;
            break;
        case 12:
            $tage = 31;
            break;
    }
    $monat_start = mktime(0,0,0,$monat,1,$jahr);
    $von = $monat_start;
    $bis = ($monat_start + (($tage-1) * 86400) )+ $okt_add;
    $bestellungen = $bestellungVerwaltung->findeAlleZuZeitraum($von, $bis);
    //  echo count($bestellungen).'<br />'.'<br />';

    if (count($bestellungen) > 0) {
        ?>
<table style="float: left; margin-right: 10px;">
    <tr>
        <th colspan="3"><?php echo strftime('%m/%Y',$von); ?></th>
    </tr>
            <?php foreach($kunden as $kunde) {
                $abrechnungstage = $abrechnungstagVerwaltung->findeAlleZuZeitraumUndKunde($von,$bis,$kunde->getId());
                $gesamtportionen_monat = 0;
                foreach($abrechnungstage as $abrechnungstag) {
                    $gea_portionen = findeGeaendertePortionenZuTagUndKunde($kunde->getId(), $abrechnungstag->getTag(), $kundeVerwaltung, $standardportionenVerwaltung, $portionenaenderungVerwaltung);

                    $gesamtportionen_monat += $gea_portionen;
                }
                ?>
    <tr>
        <td><?php echo $kunde->getName() ?></td>
        <td><?php echo $gesamtportionen_monat ?></td>
        <td>
            <a class="action_links" href="index.php?action=erzeuge_abrechnung_xls&von=<?php echo $von ?>&bis=<?php echo $bis ?>&kunde_id=<?php echo $kunde->getId() ?>">Abrechnung</a>
        </td>
    </tr>
            <?php } ?>
</table>
    <?php
    }


//echo strftime('%d.%m.%Y, %H:%M',$monat_start);
?>
