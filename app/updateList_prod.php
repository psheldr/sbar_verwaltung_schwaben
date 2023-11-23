<?php

include("connect.php");



$array = $_POST['arrayorder'];
/*echo '<pre>';
var_dump($array);
echo '</pre>';*/

if ($_POST['update'] == "update") {

    $count = 1;
    foreach ($array as $idval) {
            $tour_id = 0;
        if ($count > 1) {
            $finde_tour_sql = "SELECT id FROM kunde WHERE einrichtungskategorie_id = 5 AND lieferreihenfolge < $count ORDER BY lieferreihenfolge DESC LIMIT 1";
            

            $result = mysqli_query($conn,$finde_tour_sql);
            while ($row = mysqli_fetch_row($result)) {
                $tour_id = $row[0];
                //echo $tour_id;
            }
        }
        $query = "UPDATE kunde SET produktionsreihenfolge = " . $count . ", tour_id = " . $tour_id . " WHERE id = " . $idval;
        mysqli_query($conn,$query) or die('Error, insert query failed');
        $count ++;
    }
    echo 'Änderung gespeichert. Evtl. Seite aktualisieren um Änderungen anzuzeigen.';
}
?>