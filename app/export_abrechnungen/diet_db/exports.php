<?php

    //PHP-Spreadsheet Bibliothek einbinden
    require 'vendor/autoload.php';
    include 'classes/database.php';
    include 'classes/sheet.php';
            
    //Nach Excel exportieren, wenn Form versendet //
    if(isset($_POST["export"])){
        $db = new Database();
        $query = $_POST["export"];
        $sheet = new sheet();
        $sheet->exportExcel($db,$query);
        ?> 
        <script type="text/javascript">
        window.location.href='index.php';
        </script>
        <?php
        
    }
    /*
     * Die limitierte Export Funktionalität wird
     * aktuell nicht bereitgestellt
     */
    // --- Limitierter Export --- //
    /*
       if(isset($_POST["exportAlt"])){
        $db = new Database();
        $query = $_POST["exportAlt"];
        $sheet = new sheet();
        $sheet->exportExcel($db,$query);
        ?> 
        <script type="text/javascript">
        window.location.href='index.php';
        </script>
        <?php
    }
     */