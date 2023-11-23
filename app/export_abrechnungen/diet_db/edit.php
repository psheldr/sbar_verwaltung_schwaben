<?php
        include 'header.php';
        include "classes/database.php";

        $db = new database();
        $mysqli = $db->getConnection();
        $thead = $db->getTableArrayHead();

        // --- Beginn Form-Elemente
        echo "<form  method='post' action='results.php'>";

        $id = $_GET["id"]; //id merken
        $i = 0; //zähler
        $query = "select * from `diaeten` where `id` = ".$id.";"; //Query vorbereiten
        $thead = $db->getTableArray($query); //komplette Tabelle als Array holen
        $summary = $db->getSummaryColumn($query); //Zusammenfassung der Diät-Werte holen
        $query02 = "select * from `diaeten`"; //Zweite Query für die Vorschläge über die DB-Elemente
        $result02 = $mysqli->query($query02); //Query ausführen
        // --- Eingabefeld für jedes Element --- //
        if ($result = $mysqli->query($query)) {
            for($i=0; $i <= sizeof($thead[0])-1; $i++){
              if($thead[1][$i] == "true"){
                  echo "<input class='editCheck' onclick='disableHidden(".$i.")' id='check".$i."' name='array[]' type='checkbox' value='true' checked>".$thead[0][$i];//bei checked werden beide Formulare gesendet
                  echo "<input id='hidden".$i."' name='array[]' type='hidden' value='false' disabled>";
              }
              elseif($thead[0][$i] == "id"){ //Die Spalte 'Zusamenfassung' überspringen
                                //'Zusammenfassung' sind hier die Diät-Felder
                  echo "<input name='array[]' type='hidden' value='".$thead[1][$i]."'></input>";
              }
              elseif($thead[0][$i] == "Tag"){
                  echo "<input name='array[]' type='hidden' value=''>";
              }
              elseif($thead[0][$i] == "Zusammenfassung"){
                  echo "<input name='array[]' type='hidden' value=''>"; //Ersatzparameter da Zusammenfassung übersprungen wird
                                                            //und in der Verarbeitung die Zeilenanzahl nicht mehr stimmt
              }
              elseif($thead[1][$i] == "false"){
                  echo "<input class='editCheck' onclick='disableHidden(".$i.")' id='check".$i."' name='array[]' type='checkbox' value='true'>".$thead[0][$i];//bei checked werden beide Formulare gesendet
                  echo "<input id='hidden".$i."' name='array[]' type='hidden' value='false'>";
                  if($i == '14'){
                    echo "<br/>";
                  }
              }
              else{
                  echo"<p>".$thead[0][$i]."</p>";
                  echo "<input id='".$thead[0][$i]."' onchange='validate()' class='inputs' placeholder='".$thead[0][$i]."' list='search".$i."' name='array[]' type='text' value='".$thead[1][$i]."'><datalist id='search".$i."'>";
                   // --- Vorschläge über die Gesamtheit der DB Elemente
                  $db->getOptions($thead[0][$i]);
                  echo "</datalist></input><br>";
                }

            }
        }
        else{
            echo "Statement konnte nicht abgesetzt werden.<br><br>Fehler:<br>".mysqli_error($mysqli)."<br><br>Statement:<br>".$query;
            die();
        }
        echo "<br>";
        echo "<input id='submit' name='update' type='submit' value='Zeile aktualisieren'>";
        echo "</form>";

        echo "<form  id='delRowForm' method='post' action='delete.php'>";
        echo "<input type='hidden' name='del' value='".$id."'>";
        echo "</form><br>";
        echo "<button onclick='delRowForm()'>Zeile entfernen</button>";


        // Ende Formular --- //

  ?>
<!-- Zurück auf die Hauptseite -->
<form method="post" action="index.php">
    <br>
    <button name="back" type="submit" value="">Zurück</button>
</form>
<div id='dialog' style='display:none;'>

<?php include 'footer.php';?>
