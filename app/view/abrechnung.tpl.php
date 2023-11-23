<p>Abrechnungen für 
<form method="post" action="index.php?action=abrechnung">
    <select name="monat">
        <?php for ($i = 1; $i <= 12; $i++) { ?>
            <option <?php if ($monat == $i) {
            echo 'selected="selected"';
        } ?> value="<?php echo $i ?>"><?php echo $i ?></option>
<?php } ?>

    </select>
    <select name="jahr">
        <?php for ($i = 2013; $i <= date('Y'); $i++) { ?>
            <option <?php if ($jahr == $i) {
            echo 'selected="selected"';
        } ?> value="<?php echo $i ?>"><?php echo $i ?></option>
<?php } ?>
    </select>
    <input type="submit" value="anzeigen" />
</form>
</p>
<?php
$okt_add = 0;
switch ($monat) {
    case 1:
        $tage = 31;
        break;
    case 2:
        if ($jahr % 400 == 0 || ($jahr % 4 == 0 && $jahr % 100 != 0)) {
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
$bestellungen = $bestellungVerwaltung->findeAlleZuMonat($monat, $jahr);
//  echo count($bestellungen).'<br />'.'<br />';

if (count($bestellungen) > 0) {
    ?>
<div style="position:relative;padding-top:20px;">
    <table style="float: left; margin-right: 10px;position:relative;">
        <tr>
            <th colspan="5"><?php echo $monat . '/' . $jahr; ?></th>
        </tr>
        <tr>
            <th>Name</th>
            <th>Portionen</th>
            <th>Info</th>
            <th>Status</th>
            <th>Aktion</th>
        </tr>
        <?php
        $_SESSION['uebersicht_abrechnung'] = array();
        foreach ($kunden as $kunde) {
            $abrechnungstage = $abrechnungstagVerwaltung->findeAlleZuMonatUndKunde($monat, $jahr, $kunde->getId());
            $gesamtportionen_monat = 0;
            foreach ($abrechnungstage as $abrechnungstag) {
                $gea_portionen = findeGeaendertePortionenZuTagUndKunde($kunde->getId(), $abrechnungstag->getTag2(), $abrechnungstag->getMonat(), $abrechnungstag->getJahr(), $kundeVerwaltung, $standardportionenVerwaltung, $portionenaenderungVerwaltung);
                // $gesamtportionen_monat += $gea_portionen;
                $gesamtportionen_monat += $abrechnungstag->getPortionen();
            }
            ?>
                    <?php if ($kunde->getAktiv() || (!$kunde->getAktiv() && $gesamtportionen_monat > 0)) { ?>
                <tr>
                    <td style="width: 200px;">
            <?php echo $kunde->getName()
            ?>
                   <!--<img src="images/nbg_icon.png" style="height:12px;" />       <img src="../images/biosiegel_small.jpg" style="height:12px;" />
                   <img src="images/kitafino_icon.png" style="height:12px;" />-->
                    </td>
                    <td><?php echo $gesamtportionen_monat ?></td>
                    <td><?php echo $kunde->getLexware() ?></td>
                    <td><?php
                    $status_str = '';
                        if ($kunde->getAktiv() != 1) {
                            echo '<span class="error_text">INAKTIV</span>';
                    $status_str = 'INAKTIV';
                        }
                        ?></td>
                    <td>
                        <a class="action_links" href="index.php?action=erzeuge_abrechnung_xls&jahr=<?php echo $jahr ?>&monat=<?php echo $monat ?>&kunde_id=<?php echo $kunde->getId() ?>">Abrechnung</a>
                    </td>
                </tr>
            <?php 
            $_SESSION['uebersicht_abrechnung'][$kunde->getName()]['portionen'] = $gesamtportionen_monat;
            $_SESSION['uebersicht_abrechnung'][$kunde->getName()]['info'] = $kunde->getLexware();
            $_SESSION['uebersicht_abrechnung'][$kunde->getName()]['status'] = $status_str;
            
                        } 
            ?>
    <?php
    
    
                        } ?>
                
  
    </table>
  <a class="action_links" href="index.php?action=erzeuge_abrechnungs_uebersicht&jahr=<?php echo $jahr ?>&monat=<?php echo $monat ?>" style="position:absolute;top:0;left:0;">Übersicht als XLS</a>
</div>
    <?php
} else {
    ?>
    <p>Für diesen Monat liegen keine Bestellungen vor.</p>
    <?php
}


//echo strftime('%d.%m.%Y, %H:%M',$monat_start);
?>
