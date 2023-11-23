<?php

/*
 * Zeile einfügen & Zeile bearbeiten Formulare
 * werden hier verarbeitet.
 */
    include 'header.php';
    include 'classes/sheet.php';
    include 'classes/database.php';

    // --- Neue Eingaben als Zeile einfügen
    if (isset($_POST["insert"])){
        $db = new Database();
        $mysqli = $db->getConnection();
        $thead = $db->getTableArrayHead(); //hole Tabellenkopf

        // --- Query beginnen
        $query = "insert into `diaeten` (";

      //  var_dump($_POST['insert']);
      //  echo "</br>";
      //  echo "</br>";
      //  var_dump($thead[0]);
        if (sizeof($_POST["insert"]) == sizeof($thead[0])){
            // Ersten Teil der Query bauen
            for($i=0; $i <= sizeof($thead[0])-1; $i++){
                $query .= "`".$thead[0][$i]."`,";
            }

            //Letztes Komma entfernen
            $query = substr($query,0,-1);
            //Ersten Teil abschließen
            $query .= ") values (";

            //Eingabewerte hinzufügen
            for($i=0; $i <= sizeof($thead[0])-1; $i++){
                /*if($thead[0][$i] == "Zusammenfassung"){
                    $query .= "'',";
                }
                else{*/
                    $query .= "'". $_POST["insert"][$i] ."',";
                //}
            }

            //letztes Komma entfernen
            $query = substr($query,0,-1);
            //Query abschließen
            $query .= ")";

            //Query ausführen
            if(!($res = $mysqli->query($query))){
                echo "Statement konnte nicht abgesetzt werden.<br><br>Fehler:<br>".mysqli_error($mysqli)."<br><br>Statement:<br>".$query;
                die();
            }
            else{
                ?>
                <script type="text/javascript">
                window.location.href='index.php';
                </script>
                <?php
        }
    }
    else{
        echo "Spaltenzahl der Tabelle stimmt nicht mit der übergebenen Eingabeanzahl überein, bitte überprüfen Sie die Konsistenz ihrer Datanbank, sowie ihre Eingabe.";
        die();
    }

    }

    // --- Eingaben übernehmen und Zeile aktualisieren
    if(isset($_POST["update"])){

        $id = array_values($_POST["array"])[0]; //id merken für SQL-Update
        // Neue Instanz
        $db = new Database();
        $mysqli = $db->getConnection();
        $thead = $db->getTableArrayHead();

        $id = array_values($_POST["array"])[0]; //id merken f�r SQL-Update


        if(sizeof($thead[0]) == sizeof($_POST["array"]))
        {
            // Query vorbereiten
            $query = "update `diaeten` set "; //unvollst�ndige Query

            // Update-Query zusammensetzen
            for($i = 0;$i <= sizeof($_POST["array"])-1;$i++){
                $query .= "`".array_values($thead[0])[$i]."` = '".array_values($_POST["array"])[$i]."',";
            }
            //letztes Komma wegschmeißen
            $query=substr($query,0,-1);
            // Query abschlie�en
            $query .= " where `id` = ". $id .";";
            if(!($res = $mysqli->query($query))){
                echo "Statement konnte nicht abgesetzt werden.<br><br>Fehler:<br>".mysqli_error($mysqli)."<br><br>Statement:<br>".$query;
                }
                else{
                    ?>
                    <script type="text/javascript">
                    window.location.href='index.php';
                    </script>
                    <?php
                }
            }

    }
    else{
        die("Tabellenkopf und Tabellendaten".
                "haben nicht die selbe Länge, bitte überprüfen Sie die Konsistenz Ihrer Datenbank!");
    }

?>
                </html>
