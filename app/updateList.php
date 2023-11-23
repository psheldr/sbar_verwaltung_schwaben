<?php

include("connect.php");



$array = $_POST['arrayorder'];
$kat_id = $_POST['kat'];
$moved_id = $_POST['movedid'];

if ($_POST['update'] == "update") {


    echo '<pre>';
    var_dump('moved ' . $moved_id);
    echo '</pre>';
    if ($kat_id == 5) {
        //finde alle Zu bewegter Tour
        $tour_id_move = $moved_id;
        $kunden_zu_tour = array();
        $finde_alle_zu_tour_sql = "SELECT id FROM kunde WHERE tour_id = $tour_id_move and aktiv=1 and einrichtungskategorie_id != 5 ORDER BY lieferreihenfolge ASC";

        $result2 = mysqli_query($conn,$finde_alle_zu_tour_sql);
        while ($row2 = mysqli_fetch_row($result2)) {
            $kunden_zu_tour[] = $row2[0];
        }

        //tourkunden an neuer position in array einfügen

        /*  echo '<pre>';
          var_dump('start', $array);
          echo '</pre>'; */
        $array_cleaned = array_diff($array, $kunden_zu_tour);
        /* echo '<pre>';
          var_dump('cleaned', $array_cleaned);
          echo '</pre>'; */
        $array_cleaned = array_values($array_cleaned);
        $neue_pos_tour_moved = array_search($tour_id_move, $array_cleaned);
        /*  echo '<pre>';
          var_dump('neue pos', $neue_pos_tour_moved);
          var_dump('kunden zu tour', $kunden_zu_tour);
          var_dump('liste ohne knd zu tour', $array_cleaned);
          echo '</pre>'; */
        array_splice($array_cleaned, $neue_pos_tour_moved + 1, 0, $kunden_zu_tour);
        /* echo '<pre>';
          var_dump('neu: ', $array_cleaned);
          echo '</pre>'; */
        $array = $array_cleaned;
    }
//exit;


    $count = 1;
    foreach ($array as $idval) {
        $tour_id = 0;
        if ($count > 1) {

            if ($kat_id == 5) {
                $finde_tour_sql = "SELECT id,tour_id,einrichtungskategorie_id FROM kunde WHERE id = $idval";
            } else {
                $finde_tour_sql = "SELECT id,tour_id,einrichtungskategorie_id FROM kunde WHERE einrichtungskategorie_id = 5 AND lieferreihenfolge < $count ORDER BY lieferreihenfolge DESC LIMIT 1";
            }



            $result = mysqli_query($conn,$finde_tour_sql);
            while ($row = mysqli_fetch_row($result)) {
                $tour_id = $row[0];
                if ($row[2] != 5 && $kat_id == 5) {
                    $tour_id = $row[1];
                }
                echo '<pre>';
                var_dump($row);
                echo '</pre>';
                //echo $tour_id;
            }
        }
        $query = "UPDATE kunde SET lieferreihenfolge = " . $count . ", tour_id = " . $tour_id . " WHERE id = " . $idval;
        mysqli_query($conn,$query) or die('Error, insert query failed');
        $count ++;
    }
    echo 'Änderung gespeichert. Evtl. Seite aktualisieren um Änderungen anzuzeigen.';
}
?>