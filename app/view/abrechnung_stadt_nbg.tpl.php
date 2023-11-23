<?php
$daten_abrechnung = erstelleAbrechnungsDatenNbg($_REQUEST, $kundeVerwaltung, $abrechnungstagVerwaltung, $menunamenVerwaltung, $rechnungsadresseVerwaltung);

if (isset($_REQUEST['m']) && isset($_REQUEST['y'])) {
    $monat = $_REQUEST['m'];
    $jahr = $_REQUEST['y'];
    $heute_test_ts = mktime(12, 0, 0, $_REQUEST['m'], 1, $_REQUEST['y']);
} else {
    $monat = date("m", strtotime("last month", time()));
    $jahr = date("Y", strtotime("last month", time()));
    $heute_test_ts = mktime(12, 0, 0, $monat, 1, $jahr);
}

$monat_abre = date("m", strtotime("last month", time()));
$jahr_abre = date("Y", strtotime("last month", time()));
$vormonat = date("m", strtotime("last month", $heute_test_ts));
$vorjahr = date("Y", strtotime("last month", $heute_test_ts));

$nextmonat = date("m", strtotime("next month", $heute_test_ts));
$nextjahr = date("Y", strtotime("next month", $heute_test_ts));


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

<h5>Abrechnungs PDF</h5>
<form method="post" action="index.php?action=erzeuge_abrechnung_stadt_nbg">


    <select id="m" class="" name="monat">
        <?php
        foreach ($monate_array as $key => $monat_str) {
            $selected = '';
            if ($key == $monat_abre) {
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
            if ($jahr == $jahr_abre) {
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
            if ($key == $monat_abre) {
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
            if ($jahr == $jahr_abre) {
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