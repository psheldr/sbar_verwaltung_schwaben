<?php

/*
 * Hier wird die 'Zeile löschen'-Funktionalität verarbeitet,
 * in der 'Zeile bearbeiten'-Form
 */

include "classes/database.php";

if(isset($_POST["del"])){ //Form versendet?

        $id = $_POST["del"]; //id merken
        $db = new Database();
        $mysqli = $db->getConnection();

        $query = "delete from `diaeten` where `id` = '".$id."';"; //Query vorbereiten
        //Ausführen & weiterleiten
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
    
  ?>