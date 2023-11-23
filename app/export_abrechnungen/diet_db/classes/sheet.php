<?php

//Bibliotheken laden
require 'vendor/autoload.php';

//Namensräume angeben
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;


/*
 * Sheet-Klasse
 * Konvertiert übergebenes Array in ein Excel File 
 * und lenkt das Ergebnis an den Ausgabe-Stream
 */
class sheet {
    
public function exportExcel($db,$query) {
    //Neue Spreadsheet Instanz
    $spreadsheet = new Spreadsheet();
    
    //Style für Tabellenkopf (Fett)
    $styleArray = [
    'font' => [
        'bold' => true,
    ]];
    
    $sheet = $spreadsheet->getActiveSheet()->getStyle('1')->applyFromArray($styleArray); //Styles übernehmen
    //Sheet aus Array generieren, beginn bei Zelle A1
    $sheet = $spreadsheet->getActiveSheet()
                    ->fromArray($db->getCleanTable($query),NULL,"A1"); 
    
    //Anhang(Speicher-Dialog) über HTML Header forcieren
    header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
    header('Content-Disposition: attachment;filename="diaet.xlsx"');
    header('Cache-Control: max-age=0');

    //Writer Instanz gnerieren und in den Ausgabestream schreiben/umleiten
    $writer = \PhpOffice\PhpSpreadsheet\IOFactory::createWriter($spreadsheet, 'Xlsx');
    $writer->save('php://output');
    
    die(); //Ansonsten wird weiter in die Datei aus dem output Stream geschrieben
        //und man erh�lt eine ung�ltiges Sheet
    
}
}