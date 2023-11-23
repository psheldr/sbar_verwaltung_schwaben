    <?php include 'header.php';?>
    <body>
        <img src="img/logo.jpg"/>
        <?php
            // --- Debug-Optionen --- //
            error_reporting();
            ini_set('display_errors', 1);
            ini_set('display_startup_errors', 1);

            //PHP-Spreadsheet Bibliothek einbinden
            require 'vendor/autoload.php';
            include 'classes/database.php';
            include 'classes/sheet.php';


            //Standard query vorbereiten
            $query = "select * from `diaeten` order by `Tour`,`Einrichtung`";
            //Neue Datenbank Instanz
            $db = new Database();
            //Verbindung zur Datenbank aufbauen
            $mysqli = $db->getConnection();

            //Verbindung prüfen
            if (mysqli_connect_errno()) {
                printf("Verbindung fehlgeschlagen: %s\n", mysqli_connect_error());
                exit();
            }

            //Query ausführen
            $queryAll = $mysqli->query($query);
            $queryHead = $db->getTableArrayHead(); //Tabellenkopf holen
            $tbody = $db->getTableArray($query);

            //Query nach Auswahl erweitern
            if(isset($_POST["ordnen"]) && !(empty($_POST["ordnen"]))){
                $query = "select * from `diaeten`";
                $query .= " order by ";
                foreach((array)$_POST["ordnen"] as $value) {
                    $query .= $value . " ASC,";
                }
                //letztes Komma wegschmeißen
                $query=substr($query,0,-1);
            }
            ?>
                <!-- Filter -->
                <!-- Checkboxen nach Spaltennamen bauen -->
                <form id='filterForm' method="post">
                    <fieldset>
                        <legend>Nach Spalte(n) filtern</legend>
                        <?php
                        for($i=0; $i <= sizeof($queryHead[0])-1; $i++){
                            echo "<label class='".$queryHead[0][$i]."'>";
                            echo "<input type='checkbox' name='filter[]' value='".$queryHead[0][$i]."'>".$queryHead[0][$i];
                            echo "</label>";
                        }
                        ?>
                    <button>Filtern</button>
                    </fieldset>
                </form>


        <?php
        if(isset($_POST["filter"]) && !(empty($_POST["filter"]))){
                $filter = $_POST["filter"];
                $query = "select * from `diaeten`";
                //Standar-Query erweitern
                $query .= " where ";
                //Über gesamte POST-Übergabe(gescheckte Spaltennamen) erweitern
                foreach((array)$_POST["filter"] as $value){
                    $query .= "`".$value."` like 'true' OR";
                }

                $query .= " `sonstiges` not like '' order by `tour`;"; //immer nach Tour ordnen

                //Filter nochmals ausgeben, für die Übersicht
                echo "<fieldset><legend id='info'>Sie haben gefiltert nach</legend>";
                foreach((array)$_POST["filter"] as $value){
                        echo "<span id='filterElements'>".$value."</span>";
                    }
                  echo "</p></fieldset>";

                //Query ausführen
                if(!($res = $mysqli->query($query))){
                    echo "Statement konnte nicht abgesetzt werden.<br><br>Fehler:<br>".mysqli_error($mysqli)."<br><br>Statement:<br>".$query;
                die();
                }
            }
            ?>
            <!-- Exportieren nach Excel Spreadsheet -->
            <form id="export" method="post" action="exports.php">
                <button id="export" name="export" type="submit" value="<?php echo $query ?>">Exportieren</button> <!-- Query mitgeben -->
            </form>
            <!-- Spezifischer, statischer Export (aktuell nicht implementiert)-->
<!--            <form id="export" method="post" action="exports.php">
                <button id="export" name="exportAlt" type="submit" value="<?php echo "select Tour,Einrichtung,Diät,Menge,Zusammenfassung,Tag,Sonstiges from `db1055935-dietdb`.`diaeten`;" ?>">Limitierter Export</button>
            </form> -->
            <?php

            // --- Komplette HTML-Tablle zeichnen --- //

            $db->getTable($query);

        ?>
        <p id="infotext">*Zeile doppelt anklicken zum Bearbeiten</p>
            <!-- Ordner und Filter Form bauen -->
            <fieldset>
                <legend onclick="toggle_visibility()"><button>Tabelle ordnen</button></legend>

                <form method='post' id="hidden">
                    <p id="infotext">Mehrfachauswahl STRG+</p>
                    <select name="ordnen[]" size="10" multiple="multiple">
                    <?php
                    for($i=0; $i <= sizeof($queryHead[0])-1; $i++){
                        if($queryHead[0][$i] == 'Zusammenfassung'){continue;}
                        echo "<option>".$queryHead[0][$i]."</option>";
                    }
                    ?>
                    </select>
                    <button>Ordnen</button>
                </form>
            </fieldset>

            <!-- Einfügen neuer Zeilen -->
            <form method="post" action="insert.php">
                <fieldset>
                    <legend>Neue Zeile einfügen</legend>
                    <button name="insert" type="submit" value="<?php echo $query ?>">Neue Zeile hinzufügen</button>
                </fieldset>
            </form>
            <!-- Neue Spalten hinzufügen -->
            <form method="post" action="addColumn.php">
                <fieldset>
                    <legend>Neue Spalte</legend>
                    <input name="addColumn[]" type="input" placeholder="Spaltenname"></input>
                    <input name="addColumn[]" type="radio" value="text" checked><label>Textfeld</label>
                    <input name="addColumn[]" type="radio" value="int"><label>Zahlenfeld</label>
                    <input name="addColumn[]" type="radio" value="diaet"><label>Diätenfeld</label>
                    <em>Setze diese Spalte nach Spalte:</em>
                    <select name="addColumn[]">
                    <?php
                    for($i=0; $i <= sizeof($queryHead[0])-1; $i++){
                        echo "<option>".$queryHead[0][$i]."</option>";
                    }
                    ?>
                    </select>
                <button name="addColumn[]" type="submit">Neue Spalte hinzufügen</button>
                </fieldset>
            </form>
            <!-- Namen der Diätfelder ändern -->
            <form method="post" action="addColumn.php">
                <fieldset>
                    <legend>Diät-Spaltennamen ändern (Passwort erforderlich)</legend>
                    <em>Wählen Sie die Diätspalte die umbennant werden soll:</em>
                    <select name="modifyColumn[]">
                    <?php
                    for($i=0; $i <= sizeof($tbody[0])-1; $i++){
                      if($tbody[0][$i] == "Mo"){continue;}
                      if($tbody[0][$i] == "Di"){continue;}
                      if($tbody[0][$i] == "Mi"){continue;}
                      if($tbody[0][$i] == "Do"){continue;}
                      if($tbody[0][$i] == "Fr"){continue;}
                       if($tbody[1][$i]  === "true" || $tbody[1][$i]  === "false"){
                        echo "<option>".$tbody[0][$i]."</option>";
                       }
                    }
                    ?>
                    </select>
                    <input id='diaetinputdisabled' type="input" value='' placeholder='Spaltenname' disabled /><spre onclick='verifypasswd()' style='cursor: pointer;'> &#9998;</spre>
                    <input id='diaetinput' type='hidden' value='' name='modifyColumn[]'/>

                <button type="submit">Übernehmen</button>
                </fieldset>
            </form>
            <div id='dialog' style='display:none;'>
            <br>
            <i><spre>Für das Umbenennen des Diät-Feldes ist ein Passwort erforderlich</spre></i><br><br>
            <table>
              <tr>
                <td>
                  Password
                </td>
                <td>
                  <input id='diaetpassword' type='password' maxlength='10'/>
                </td>
              </tr>
              <tr>
                <td>
                  Diät
                </td>
                <td>
                  <input id='diaettext' type='text' value=''/>
                </td>
              </tr>
            </table>
            <input id='diaethiddenhash' type='hidden' value='<?php echo md5("Penny22917"); ?>'/>
            </div>
            <!-- Spalte löschen (aktuell deaktiviert)-->
<!--            <fieldset>
               <legend>Spalte entfernen</legend>
                <form id="delColumn" method='post' action="deleteColumn.php">

                    <select name="deleteColumn">
                      <?php
                        for($i=0; $i <= sizeof($queryHead[0])-1; $i++){
                            if($queryHead[0][$i] == 'Sonstiges' || $queryHead[0][$i] == 'id' || $queryHead[0][$i] == 'Zusammenfassung'){continue;}
                            echo "<option>".$queryHead[0][$i]."</option>";
                        }
                      ?>
                    </select>
                </form>
               <br>
                <button onclick='delForm()'>Spalte entfernen</button>
            </fieldset>-->
    </body>
</html>
<?php include 'footer.php';?>
