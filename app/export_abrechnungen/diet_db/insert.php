<?php

/*
 * Hier wird das 'Neue Zeile hinzufügen'-Formular gebaut
 */
        include 'header.php';
        include "classes/database.php";

        $db = new database();
        $mysqli = $db->getConnection();

        // --- Beginn Form-Elemente
        echo "<form method='post' action='results.php'>";

        $query = "select * from `diaeten`;"; //Query vorbereiten
        $mysqli = $db->getConnection();
        $thead = $db->getTableArray($query); //komplettes Query Ergebnis
                                //als formattiertes Array zurückgeben

        // --- Eingabefeld per Element --- //
        if ($result = $mysqli->query($query)) {


            for($i=0; $i <= sizeof($thead[0])-1; $i++){
              if($thead[0][$i] == "Zusammenfassung"){ //Die Spalte 'Zusamenfassung' überspringen
                                //'Zusammenfassung' sind hier die Diät-Felder
                echo "<input name='insert[]' type='hidden' value=''></input>";
              }
              elseif($thead[0][$i] == "id"){ //Die Spalte 'Zusamenfassung' überspringen
                                //'Zusammenfassung' sind hier die Diät-Felder
                echo "<input name='insert[]' type='hidden' value=''></input>";
              }
              elseif($thead[0][$i] == "Tag"){
                echo "<input name='insert[]' type='hidden' value=''></input>";
              }
              /*
               * Wenn Werte 'true' oder 'false'(Anwendungsintern), entsprechend versteckte Form mitsenden,
               * da Checkboxen standardmäßig nur die Werte versenden die auch in häckchen gesetzt wurden.
               * Das erschwert den Aufbau der Query, da für das Mysql-Insert Statement
               * alle auch leere Spalten mit angegeben werden müssen. Und nun nicht ersichtbar ist welche
               * Diäten angecheckt wurden, da auch neue Spalten hinzugefügt werden können und die Anzahl dynamisch ist.
               * Ein weiteres Problem ist, dass bei angecheckten Boxen das versteckte Formular mitgesandt wird, was die Anzahl nochmals verfälscht.
               * Darum kümmert sich die 'disableHidden()'-Funktion.
              */
              elseif($thead[1][$i] == "true" || $thead[1][$i] == "false"){
//                  if($thead[0][$i] == 'Montag'
//                          || $thead[0][$i] == 'Dienstag'
//                          || $thead[0][$i] == 'Mittwoch'
//                          || $thead[0][$i] == 'Donnerstag'
//                          || $thead[0][$i] == 'Freitag'){
//                    echo "<input class='editCheck' onclick='disableHidden(".$i.")' id='check".$i."' name='insert[]' type='checkbox' value='true' checked>".$thead[0][$i]."</input>";//bei checked werden beide Formulare gesendet
//                    echo "<input id='hidden".$i."' name='insert[]' type='hidden' value='false'></input>";
//                  }
//                  else{
                    echo "<input class='editCheck' onclick='disableHidden(".$i.")' id='check".$i."' name='insert[]' type='checkbox' value='true'>".$thead[0][$i]."</input>";//bei checked werden beide Formulare gesendet
                    echo "<input id='hidden".$i."' name='insert[]' type='hidden' value='false'></input>";
                    if($i == '14'){
                      echo "<br/>";
                    }
//                  }
              }
              else{
                  echo"<p>".$thead[0][$i]."</p>";
                  //'validate()' überwacht hier den Input live auf 'true' und 'false'
                  echo "<input style='border-color: red;' class='inputs' onchange='validate()' placeholder='".$thead[0][$i]."' list='search".$i."' name='insert[]' type='text' value=''><datalist id='search".$i."'>";
                  // --- Vorschläge ausgeben
                  $db->getOptions($thead[0][$i]);
                  echo "</datalist></input><br>";
                }
            }
            echo "<br>";
            echo "<input id='submit' type='submit' value='Zeile hinzufügen'>";
            echo "</form>";
            // Ende Formular --- //
        }
        else{
            echo "Statement konnte nicht abgesetzt werden.<br><br>Fehler:<br>".mysqli_error($mysqli)."<br><br>Statement:<br>".$query;
            die();
        }



  ?>
<br>

<!-- Zurück auf die Hauptseite -->
<form method="post" action="index.php">
    <button name="back" type="submit" value="">Zurück</button>
</form>
<div id='dialog' style='display:none;'>
<?php include 'footer.php';?>
