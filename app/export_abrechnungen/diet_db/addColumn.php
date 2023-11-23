<?php
/*
 * Hier werden die Formulareingaben
 * für das hinzufügen von Spalten verarbeitet
 */
include 'header.php';
include "classes/database.php";

if(!empty($_POST["modifyColumn"])){
  $columnname = $_POST["modifyColumn"][0];
  $columnmodifyedname = $_POST["modifyColumn"][1];

  //Neue DB-Instanz
  $db = new Database();
  $mysqli = $db->getConnection();

  $query = "alter table `diaeten` change column `".$columnname."` `".$columnmodifyedname."` text;";
  if(!$res = $mysqli->query($query)){
      echo "Query Fehlgeschlagen: ".mysqli_error($mysqli)."; Statement: ".$query."<br>";
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


if(isset($_POST["addColumn"])){ //Formular versendet?
    //Variablen übergeben
    $input = $_POST["addColumn"][0]; //Spaltenname
    $columnType = $_POST["addColumn"][1]; //Datentyp der Spalte
    $afterColumn = $_POST["addColumn"][2]; //Spalte nach der die neue Spalte eingefügt werden soll
    //Variablen Definition
    $query = "";
    $queryUpdate = "";

    /*
     * Spalte darf nicht 'Zusammenfassung' benannt werden, dass ist ein anwendungsinterner Schlüsselwert(DB-Spalte)
     * in dem die Diät-Werte zusammengefasst werden.
     */
    if($input == "Zusammenfassung" || $input == "zusammenfassung"){
        ?>
        <script type="text/javascript">
            function info(){
                //Info ausgeben und auf Hauptseite weiterleiten
                if(window.confirm("'Zusammenfassung' ist ein geschützter Spaltenname und darf nicht verwendet werden")){
                    window.location.href='index.php';
                }
                else{
                    window.location.href='index.php';
                }
            }
            info();
        </script>
        <?php
    }

    //Falls eingabe leer, zurück zur Hauptseite leiten
    if($input == "" || $afterColumn == ""){
        ?>
        <script type="text/javascript">
         window.location.href='index.php';
        </script>
        <?php
    }

    //Neue DB-Instanz
    $db = new Database();
    $mysqli = $db->getConnection();

    //Text-, Diät- oder Zahlenfeld?
    if($columnType == "text"){
        $query = "alter table `diaeten` add column `".$input."` text after `".$afterColumn."`;"; //Spalte als Texttyp anlegen
    }
    elseif($columnType == "diaet"){ //Diät ist ein anwendungsinterner boolischer Datentyp für die Checkbox Logik
        $query = "alter table `diaeten` add column `".$input."` text after `".$afterColumn."`;"; //Spalte als Texttyp anlegen
        $queryUpdate = "UPDATE `diaeten` SET `diaeten`.`".$input."` = 'false';"; //gesamte Spalte mit 'false' Werten ausfüllen
    }
    else {
        $query = "alter table `diaeten` add column `".$input."` int(10) after `".$afterColumn."`;"; //Spalte als Zahlentyp anlegen
    }

    /*
     * Querys ausführen
     */
    if($columnType == "diaet"){
        //Diät Query ausführen und auf Hauptseite weiterleiten
        if($res = $mysqli->query($query) && $res02 = $mysqli->query($queryUpdate)){
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
    else{
        //Alternativquery ausführen und auf Hauptseite weiterleiten
        if($res = $mysqli->query($query)){
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
}
