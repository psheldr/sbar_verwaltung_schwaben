<?php
/*
 * DB-Klasse
 * getConnection()          *gibt mysqli Instanz zurück
 * getTableHead()           *zeichnet Tabellenkopf
 * getTable($query)         *zeichnet komplette HTML-Tabelle nach $query
 * getTableArrayHead()      *gibt Tabellenkopf als einzeiliges Array(per Element) zurück [0]->[][][]
 * getTableArray($query)    *gibt komplette Tabelle als Array nach $query zurück
 *                          [0]->[][][]
 *                          [1]->[][][]
 *                           ...
 * getCleanTable($query)    *gibt komplette aufgelöste Tabelle nach $query zurück
 *                          true -> Spaltenname
 *                          false-> ""
 *                          *true & false sind Schlüsselwerte und dürfen nicht
 *                           als Strings in Eingabe oder DB vorkommen, da sie
 *                           die Verarbeitung verfälschen würden
 * getSummaryColumn($query) *baut aus den Diät-Spalten ein zusammengefasstes Array
 *                           aus Diät Elementen und gibt diese zurück
 *                          [0]->[][][]
 *                          [1]->[][][]
 */
class Database{

    private $user = "db1055935-deniz";
    private $dbname = "db1055935-dietdb";
    private $pw = "xT8Ep23L!";
    private $host = "wp088.webpack.hosteurope.de";

    // --- Gibt ein mysqli Object zurück,
    // --- f�r DB querys
    public function getConnection() {

        $mysqli = new mysqli($this->host, $this->user, $this->pw, $this->dbname);
        $mysqli->set_charset("utf8");
        if($mysqli->connect_errno){
            printf("Connection failed: %s\n", $mysqli->connect_errno);
            die();
        }
        return $mysqli;
    }

    // --- Ausgabe des Tabellenkopfes
    public function getTableHead(){

        $db = new database();
        $mysqli = $db->getConnection();
        //Query für die Tabellenspalten
        $q = "SELECT `COLUMN_NAME` FROM `INFORMATION_SCHEMA`.`COLUMNS` WHERE `TABLE_SCHEMA`='".$this->dbname."' AND `TABLE_NAME`='diaeten';";

        //HTML Tabellenkopf schreiben
        if ($result = $mysqli->query($q)){
            echo "<tr>";
            while ($row = $result->fetch_assoc()) {
                foreach ($row as $v) {
                    if($v != "Mo"
                          && $v != "Di"
                          && $v != "Mi"
                          && $v != "Do"
                          && $v != "Fr"){
                    echo "<th>" . $v . "</th>";
                    }
                }
            }
            echo "</tr>";
        }
        else{
            //Fehlerausgabe
            echo "Fehler:<br>".mysqli_error($mysqli);
            die();
        }
    }

    // --- Schreibt die komplette HTML Tabelle
    public function getTable($query) {
        $mysqli = $this->getConnection();

        echo "<table id='table'>";
        $this->getTableHead();//Schreibe HTML Tabellenkopf
        $thead = $this->getTableArrayHead();
         //hole alle Werte aus Spalte Zusammenfassung(aufgelöst)
        $summary = $this->getSummaryColumn($query); //Die Spalte 'Zusammenfassung'
                                    //wird separat generiert aus Diät-Spalten
        $summaryDays = $this->getSummaryColumnDays($query);
       //var_dump($summaryDays);
        // --- Baue Tabelle --- //
        if ($result = $mysqli->query($query)) {

            $j=1;
            //Zeilenweise iteration der Mysql-query
            while ($row = $result->fetch_assoc()) {

                echo "<tr onclick='editRow()'>";
                $i=0;
                foreach ($row as $value) {
                    if($thead[0][$i] == NULL){
                        continue;
                    }
                   // var_dump($thead[0][$i]." => ".$value);
                   // echo "<br>";
                   // echo "<br>";

                  if($value == "true"){ //True nach Spaltenname auflösen
                    // --- Wochentage ausblenden
                    if($thead[0][$i] != "Mo"
                          && $thead[0][$i] != "Di"
                          && $thead[0][$i] != "Mi"
                          && $thead[0][$i] != "Do"
                          && $thead[0][$i] != "Fr"){
                      echo "<td>" . $thead[0][$i] . "</td>";
                    }
                  }
                  elseif($value == "false"){ //False auflösen
                    if($thead[0][$i] != "Mo"
                          && $thead[0][$i] != "Di"
                          && $thead[0][$i] != "Mi"
                          && $thead[0][$i] != "Do"
                          && $thead[0][$i] != "Fr"){
                      echo "<td></td>";
                    }
                  }
                  //Ausgabe der zusammengefassten Diäten, 'Zusammenfassung'-Spalte
                  elseif($thead[0][$i] == "Zusammenfassung"){
                      echo "<td>";
                      foreach ($summary[$j] as $value){
                          if($value == ""){
                              echo "";
                          }
                          else{
                              echo $value.", ";
                          }
                      }
                      echo "</td>";
                  }
                  //Ausgabe der zusammengefassten Tage, 'Tag'-Spalte
                  elseif($thead[0][$i] == "Tag"){
                      echo "<td>";
                      foreach ($summaryDays[$j] as $value){
                          if($value == ""){
                              echo "";
                          }
                          else{
                              echo $value.", ";
                          }
                      }
                      echo "</td>";
                  }
                  else{
                    // var_dump($value);
                    // echo "<br>";
                    echo "<td>" . $value . "</td>";
                    }
                  $i++; //Spaltenzähler
                }
                $j++; //Zeilenzähler
                echo "</tr>";
            }

            //Speicher wieder freigeben
            $result->free();
        }
        echo "</table>";

        //Verbindung zur DB schließen
        $mysqli->close();

    }

    // --- Tabellenkopf als Array zurückgeben
    public function getTableArrayHead() {
        $a1 = array(); //initialisieren
        $mysqli = $this->getConnection();
        //Query für Elemente im Tabellenkopf
        $q = "SELECT `COLUMN_NAME` FROM `INFORMATION_SCHEMA`.`COLUMNS` "
                . "WHERE `TABLE_SCHEMA`='".$this->dbname."'"
                . "AND `TABLE_NAME`='diaeten';";
        $res = $mysqli->query($q);

        while($row = $res->fetch_assoc()){
           $a1[] = $row;
        }
        // --- Query wird anstatt in einem Array in 23 Arrays geliefert,
            // da die Query in Zeilen(23) zurückgegeben wird anstatt in einer Zeile
            // deshalb m�ssen hier die Arrays in ein einzelnes Array umgeordnet werden.
            // Ansonsten wird der Tabellenkopf als 23 zeiliges Element in das Excel File expotiert
        $sortedArray = array();
        for($i=0; $i <= sizeof($a1)-1; $i++){
            //if (array_values($a1[$i])[0] != "Mo" && array_values($a1[$i])[0] != "Di" && array_values($a1[$i])[0] != "Mi" && array_values($a1[$i])[0] != "Do" && array_values($a1[$i])[0] != "Fr") {
                $sortedArray[0][$i] = array_values($a1[$i])[0];
            //}
        }

        return $sortedArray;
    }

    // --- Tabelle als Array zurückgeben, für Excel export --- //
    public function getTableArray($query) {
        $mysqli = $this->getConnection();
        // Tabellenkopf holen
        $thead = $this->getTableArrayHead();
        $tbody = array();

        //Query ausführen + Fehlerabfrage
        if(!$res = $mysqli->query($query)){
            echo $mysqli_error($mysqli)." Error<br>";
            die();
        }

        $i=0; //Spaltenzähler
        $j=0; //Zeilenzähler
        while ($row = $res->fetch_assoc()) {
            $j = 0;
            foreach ($row as $value) {

                //if ($thead[0][$i] != "Mo" && $thead[0][$i] != "Di" && $thead[0][$i] != "Mi" && $thead[0][$i] != "Do" && $thead[0][$i] != "Fr") {
                    $tbody[$i][$j] = $value;
                    $j++;
                //}
            }
            $i++;
        }

        $array = array_merge($thead,$tbody);
        //var_dump($array);
        $mysqli->close();
        return $array;
    }

    // --- Die aufgelöste Version der Tabelle bauen (true,false) --- //
        // --- für den Export
    public function getCleanTable($query){

        $mysqli = $this->getConnection();
        //Komplette Tabelle holen
        $thead = $this->getTableArrayHead();
        $summary = $this->getSummaryColumn($query);
        $summaryDays = $this->getSummaryColumnDays($query);
        $translatedArray  = array();
        $t = array();
        $trow = array();

        //True,False auflösen und zurückgeben
        if ($result = $mysqli->query($query)) {

            $j=0; //Zeilenzähler
            while ($row = $result->fetch_assoc()) {
                $i=0; //Spaltenzähler
                foreach ($row as $value) {
                  if($value == "true"){
                    if($thead[0][$i] != "Mo"
                          && $thead[0][$i] != "Di"
                          && $thead[0][$i] != "Mi"
                          && $thead[0][$i] != "Do"
                          && $thead[0][$i] != "Fr"){
                      $translatedArray[$j][$i] = $thead[0][$i];
                    }
                  }
                  elseif($value == "false"){
                    if($thead[0][$i] != "Mo"
                          && $thead[0][$i] != "Di"
                          && $thead[0][$i] != "Mi"
                          && $thead[0][$i] != "Do"
                          && $thead[0][$i] != "Fr"){
                      $translatedArray[$j][$i] = "";
                    }
                  }
                  //Zusammengefasste Diäten in die gleichnamige Spalte schreiben
                  elseif($thead[0][$i] == "Zusammenfassung"){
                      foreach ($summary[$j+1] as $value) { //Werte der $summary beginnen bei index 1
                          if($value == ""){
                              $translatedArray[$j][$i] .= "";
                          }
                          else{
                              $translatedArray[$j][$i] .= $value.", ";
                          }
                      }
                  }
                  //Zusammengefasste Wochentage in die gleichnamige Spalte schreiben
                  elseif($thead[0][$i] == "Tag"){
                      foreach ($summaryDays[$j+1] as $value) { //Werte der $summary beginnen bei index 1
                          if($value == ""){
                              $translatedArray[$j][$i] .= "";
                          }
                          else{
                              $translatedArray[$j][$i] .= $value.", ";
                          }
                      }
                  }
                  else{
                    $translatedArray[$j][$i] = $value;
                  }
                  $i++;
                }
                $j++;
            }
        }
        else{
            echo $mysqli_error($mysqli)." Error<br>";
            die();
        }

        //strip Weekdays from array, for Export
        foreach($thead[0] as $v){
          if($v != "Mo"
                && $v != "Di"
                && $v != "Mi"
                && $v != "Do"
                && $v != "Fr"){
                  $t[] = $v;
                }
        }
        $trow[] = $t;


        //var_dump(array_merge($thead,$translatedArray));
        return array_merge($trow,$translatedArray);
    }

    //Gibt die zusammengefassten Tage zurück
    public function getSummaryColumn($query){
        $mysqli = $this->getConnection();
        $tbody = $this->getTableArray($query);

        $summary = array();

        for($i=0;$i<=sizeof($tbody)-1;$i++){
            $j=0;
            for($j=0;$j<=sizeof($tbody[0])-1;$j++){

                //Die Wochentage nicht zusammenfassen
                if( $tbody[0][$j] != 'Mo'
                        && $tbody[0][$j] != 'Di'
                        && $tbody[0][$j] != 'Mi'
                        && $tbody[0][$j] != 'Do'
                        && $tbody[0][$j] != 'Fr')
                {
                    if($tbody[$i][$j] == "true"){
                        $summary[$i][$j] = $tbody[0][$j];
                    }
                    elseif($tbody[$i][$j] == "false"){
                        $summary[$i][$j] = ""; //Hier muss ein Wert zugewiesen werden,
                        //damit bei einem späteren foreach durchlauf diese Spalte
                        //nicht übersprungen wird
                    }
                }
            }
        }

        return $summary;
    }

      //Gibt die zusammengefassten Wochentage zurück
    public function getSummaryColumnDays($query){
        $mysqli = $this->getConnection();
        $tbody = $this->getTableArray($query);

        $summary = array();

        for($i=0;$i<=sizeof($tbody)-1;$i++){
            $j=0;
            for($j=0;$j<=sizeof($tbody[0])-1;$j++){

                //Die Wochentage zusammenfassen
                if($tbody[0][$j] == 'Mo'
                        || $tbody[0][$j] == 'Di'
                        || $tbody[0][$j] == 'Mi'
                        || $tbody[0][$j] == 'Do'
                        || $tbody[0][$j] == 'Fr')
                {
                    if($tbody[$i][$j] == "true"){
                        $summary[$i][$j] = $tbody[0][$j];
                    }
                    elseif($tbody[$i][$j] == "false"){
                        $summary[$i][$j] = ""; //Hier muss ein Wert zugewiesen werden,
                        //damit bei einem späteren foreach durchlauf diese Spalte
                        //nicht übersprungen wird
                    }
                }
            }
        }

        return $summary;
    }

    //Gibt HTML-Options(Vorschläge) zurück. Format: [0][1][2]...
    public function getOptions($columnName){

        $mysqli = $this->getConnection();

        //Query vorbereiten
        $query = "select ".$columnName." from `".$this->dbname."`.`diaeten`;";
        //Query ausführen + Fehlerabfrage
        if(!$res = $mysqli->query($query)){
            echo $mysqli_error($mysqli)." Error<br>";
            die();
        }

        $a = array();
        while($row = $res->fetch_assoc()){
            $a[] = $row[$columnName];
        }

        $a = array_unique($a);
        foreach($a as $value){
            if($value == ""){continue;}
            echo "<option value='".$value."'>";
        }



    }
}
