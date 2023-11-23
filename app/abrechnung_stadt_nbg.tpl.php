<?php
$daten_abrechnung = erstelleAbrechnungsDatenNbg($_REQUEST, $kundeVerwaltung, $abrechnungstagVerwaltung, $menunamenVerwaltung, $rechnungsadresseVerwaltung);



/*
  echo '<pre>';
  var_dump($daten_abrechnung);
  echo '</pre>'; */
//
//$_SESSION['daten_abrechnung_nbg'] = $daten_abrechnung;
/* echo '<pre>';
  var_dump($daten_abrechnung);
  echo '</pre>'; */
?>
<?php
$monate_array = array(
    1 => "Januar",
    2 => "Februar",
    3 => "M&auml;rz",
    4 => "April",
    5 => "Mai",
    6 => "Juni",
    7 => "Juli",
    8 => "August",
    9 => "September",
    10 => "Oktober",
    11 => "November",
    12 => "Dezember");
?>
<form method="post" action="index.php?action=erzeuge_abrechnung_stadt_nbg">


    <select id="m" class="" name="monat">
        <?php
        foreach ($monate_array as $key => $monat_str) {
            $selected = '';
            if ($key == date('m')) {
                $selected = 'selected="selected"';
            }
            ?>
            <option <?php echo $selected ?> value="<?php echo $key ?>"><?php echo $monat_str ?></option>
        <?php } ?>
    </select>     

    <select id="j" class="" name="jahr">
        <?php
        for ($y = 2018; $y <= date('Y'); $y++) {
            $selected = '';
            if ($jahr == $year) {
                $selected = 'selected="selected"';
            }
            ?>
            <option <?php echo $selected ?> value="<?php echo $y ?>"><?php echo $y ?></option>
        <?php } ?>
    </select>   
    <input type="submit" value="PDF erzeugen" />
</form>

<br />
<h5>Statistik XLS</h5>
<form method="post" action="index.php?action=erzeuge_statistik_excel_stadt_nbg">


    <select id="m" class="" name="monat">
        <?php
        foreach ($monate_array as $key => $monat_str) {
            $selected = '';
            if ($key == date('m')) {
                $selected = 'selected="selected"';
            }
            ?>
            <option <?php echo $selected ?> value="<?php echo $key ?>"><?php echo $monat_str ?></option>
        <?php } ?>
    </select>     

    <select id="j" class="" name="jahr">
        <?php
        for ($y = 2018; $y <= date('Y'); $y++) {
            $selected = '';
            if ($jahr == $year) {
                $selected = 'selected="selected"';
            }
            ?>
            <option <?php echo $selected ?> value="<?php echo $y ?>"><?php echo $y ?></option>
        <?php } ?>
    </select>   
    <input type="submit" value="XLS erzeugen" />
</form>
<?php /* foreach ($daten_abrechnung as $kunden_id => $daten) { 
  if (!is_numeric($kunden_id)) {
  continue;
  }?>
  <table>
  <tr>
  <th>
  <?php echo $kunden_id ?>
  </th>
  </tr>
  </table>
  <?php } */ ?>