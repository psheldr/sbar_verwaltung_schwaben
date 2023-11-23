<?php

/*
 * Hier werden die Eingaben für die 
 * 'Spalten löschen'-Funktionalität verarbeitet
 */

include 'classes/database.php';

if(isset($_POST["deleteColumn"])){ //Form versendet?
    $db = new Database();
    $mysqli = $db->getConnection();
    
    $query = "alter table `diaeten` drop `".$_POST["deleteColumn"]."`;"; //Query vorbereiten
    
    if($res = $mysqli->query($query)){ //Query ausführen & Weiterleiten
        ?> 
            <script type="text/javascript">
            window.location.href='index.php';
            </script>
        <?php
    }
    else{
        echo "Statement konnte nicht abgesetzt werden.<br><br>Fehler:<br>". mysqli_error($mysqli); //Fehlerausgabe
        die();
    }
    
}