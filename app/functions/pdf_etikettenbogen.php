<?php

require 'fpdf/fpdf.php';
$pdf = new FPDF();
//$pdf->AddPage();

$pdf->SetAutoPageBreak(false);
$pdf->SetTextColor(80, 80, 80);
$pdf->SetFont('Arial', '', 12);
$pdf->AddPage();
$count_etiketten = 0;
$y = 12.9;
$x = 9.75;
foreach ($boxen_daten as $box_data) {
    for ($f = 1; $f <= $faktor; $f++) {
        $count_etiketten++;
        //RAHMEN
       /// $pdf->setXY($x, $y);
      ///  $pdf->MultiCell(63.5, 33.9, '', 1, 'C');

        //CODE IMG    
        $pdf->Image('http://www.s-bar.net/verwaltung/functions/barcode.php?text=' . $box_data['code'], $x + 0.25, $y + 9, 63, 20, 'PNG');
        //BEZEICHNUNG
        $pdf->setXY($x, $y + 4.5);
        $pdf->SetFont('Arial', 'B', 12);
        $pdf->MultiCell(63.5, 0, utf8_decode($box_data['name']), 0, 'C');
        //CODE STRING
        $pdf->setXY($x, $y + 31);
        $pdf->SetFont('Arial', '', 9);
        $pdf->MultiCell(63.5, 0, $box_data['code'], 0, 'C');
        /*
          $pdf->setY($y);
          $pdf->setX($x);
          $pdf->Cell('', 33.9, 'Test '.$count_etiketten, '', '', 'C');
          //$pdf->Image('http://www.s-bar.net/verwaltung/functions/barcode.php?barcode=test', $x+10, $y+10, 45, 0, 'PNG');
          $pdf->setY($y+30);
          $pdf->Cell('', '', $box_data['code'], '', '', 'L');
         */
        $x = $x + 63.5;
        //nach jedem dritten Element neue Zeile (X Reset und Y + Höhe)
        if ($count_etiketten > 1 && $count_etiketten % 3 === 0) {
            $y = $y + 33.9;
            $x = 9.75;
        }

        if ($count_etiketten > 0 && $count_etiketten % 24 === 0 && $count_etiketten < $durchgaenge*$faktor) {
            $pdf->AddPage();
            $y = 12.9;
            $x = 9.75;
        }
    }
}

$add_name = $_REQUEST['typ'];
$pdf->Output('kitafino_' . $add_name . $file_stamp . '.pdf', 'D');
exit;
echo '<pre>';
var_dump($boxen_daten);
echo '</pre>';

define('EURO', chr(128));
//$pdf->SetAutoPageBreak(true, 30);

$pdf->setRightMargin(20);
$pdf->SetFont('Arial', '', 12);
$pdf->AddPage();
$pdf->SetTextColor(80, 80, 80);
$pdf->Image('images/kitafino_logo.jpg', 125, 10, 70);

//Logo + Briefkopf
$pdf->SetFontSize(9);
$pdf->setY(35);
$pdf->Cell(0, 0, utf8_decode('kitafino UG (haftungsbeschränkt)'), 0, 0, 'R');
$pdf->setY(39);
$pdf->Cell(0, 0, utf8_decode('Allersberger Str. 185/O'), 0, 0, 'R');
$pdf->setY(43);
$pdf->Cell(0, 0, utf8_decode('90461 Nürnberg'), 0,0, 'R');
$pdf->setY(47 + 2);
$pdf->Cell(0, 0, utf8_decode('Tel.: 0911 / 46 25 99 - 1146'), 0, 0, 'R');
$pdf->setY(51 + 2);
$pdf->Cell(0, 0, utf8_decode('Fax: 0911 / 46 25 99 - 9111'), 0, 0, 'R');
$pdf->setY(55 + 4);
$pdf->Cell(0, 0, utf8_decode('kontakt@kitafino.de'), 0, 0, 'R');
$pdf->setY(59 + 4);
$pdf->Cell(0, 0, utf8_decode('www.kitafino.de'), 0, 0, 'R');

//Datum
$pdf->setY(80);
$pdf->setX(20);
$pdf->SetFont('Arial', 'B', 10);
$pdf->Cell(0, 0, utf8_decode(date('d') . '.' . date('m') . '.' . date('Y')), 0, 0, 'R');

//Betreff
$pdf->setY(90);
$pdf->setX(20);
$pdf->SetFont('Arial', 'B', 12);
$pdf->Cell(0, 0, utf8_decode($betreff), 0,0, 'L');


//Personeninfos
$pdf->SetFont('Arial', '', 9);
$pdf->setY(100);
$pdf->setX(20);
$pdf->Cell(0, 0, utf8_decode('Name, Vorname (Erziehungsberechtigter):'), 0, 0, 'L');
$pdf->SetFont('Arial', 'B', 9);
$pdf->setX(85);
$pdf->Cell(0, 0, utf8_decode($name_erz), 0, 0, 'L');

$pdf->SetFont('Arial', '', 9);
$pdf->setY(105);
$pdf->setX(20);
$pdf->Cell(0, 0, utf8_decode('Name, Vorname (Kind/Esser):'), 0,0, 'L');
$pdf->SetFont('Arial', 'B', 9);
$pdf->setX(85);
$pdf->Cell(0, 0, utf8_decode($name_kind), 0, 0, 'L');

$cell_1_w = 130;
$cell_2_w = 12;
$cell_3_w = 12;
$cell_4_w = 14;
$row_h = 6;
$set_y = 110;

$gesamt_jahr = 0;
$anzahl_gesamt_jahr = 0;
foreach ($orders_array_month as $monat_key => $orders_month) {

    if (count($orders_array_month[$monat_key]) == 0) {
        continue;
    }



    $pdf->setY($set_y);
    $pdf->SetFont('Arial', 'B', 9);
//Table Head Datum
    $pdf->setX(20);
    $pdf->setFillColor(120, 120, 120);
    $pdf->setTextColor(220, 220, 220);
    $pdf->Cell($cell_1_w + $cell_2_w + $cell_3_w + $cell_4_w, $row_h, $monat_key . '/' . $jahr_abr, 1, 0, 'C', 1);
    $set_y += $row_h;


    $pdf->SetTextColor(80, 80, 80);
    $pdf->SetFont('Arial', '', 8);
    $pdf->setY($set_y);
//col1 th
    $pdf->setX(20);
    $pdf->Cell($cell_1_w, $row_h - 1, 'Speise', 0, 0, 'L');
//col2 th
    $pdf->setX(20 + $cell_1_w);
    $pdf->Cell($cell_2_w, $row_h - 1, EURO . ' Einzel', 0, 0, 'C');
//col3 th
    $pdf->setX(20 + $cell_1_w + $cell_2_w);
    $pdf->Cell($cell_3_w, $row_h - 1, 'Anzahl', 0, 0, 'C');
//col4 th
    $pdf->setX(20 + $cell_1_w + $cell_2_w + $cell_3_w);
    $pdf->Cell($cell_4_w, $row_h - 1, EURO . ' Gesamt', 0, 0, 'C');

    $set_y += $row_h - 2;

    $gesamt = 0;
    $anzahl_gesamt = 0;
    foreach ($orders_array_month[$monat_key] as $order_cat) {
        $pdf->setY($set_y);
        $gesamt_in_cat = 0;
        $gesamt_in_cat = $order_cat['anzahl'] * $order_cat['kategorie_preis'];
        $gesamt += $gesamt_in_cat;
        $anzahl_gesamt += $order_cat['anzahl'];
//col1
        $pdf->setX(20);
        $pdf->Cell($cell_1_w, $row_h, utf8_decode($order_cat['kategorie_string']), 1, 0, 'L');
//col2
        $pdf->setX(20 + $cell_1_w);
        $pdf->Cell($cell_2_w, $row_h, preisAusgeben($order_cat['kategorie_preis']), 1,0, 'R');
//col3
        $pdf->setX(20 + $cell_1_w + $cell_2_w);
        $pdf->Cell($cell_3_w, $row_h, $order_cat['anzahl'], 1, 0, 'C');
//col4
        $pdf->setX(20 + $cell_1_w + $cell_2_w + $cell_3_w);
        $pdf->Cell($cell_4_w, $row_h, preisAusgeben($gesamt_in_cat), 1, 0, 'R');
        $set_y += $row_h;
    }

    $pdf->SetFont('Arial', 'B', 8);
    $pdf->setY($set_y);
    $pdf->setX(20);
    $pdf->Cell($cell_1_w + $cell_2_w, $row_h, 'Gesamt ' . $monat_key . '/' . $jahr_abr, 1, 0, 'L');

    $pdf->setX(20 + $cell_1_w + $cell_2_w);
    $pdf->Cell($cell_3_w, $row_h, $anzahl_gesamt, 1, 0, 'C');

    $pdf->setX(20 + $cell_1_w + $cell_2_w + $cell_3_w);
    $pdf->Cell($cell_4_w, $row_h, preisAusgeben($gesamt), 1, 0, 'R');
    $gesamt_jahr += $gesamt;
    $anzahl_gesamt_jahr += $anzahl_gesamt;
    $set_y += 2 * $row_h;

    if ($pdf->GetY() > 240) {

        $pdf->setX(20);
        $pdf->setY(276);
        $pdf->Cell(0, 0, utf8_decode('Fortsetzung auf Seite 2'), 0, 0, 'R');

        $pdf->AddPage();
        $set_y = 30;
        $pdf->Image('images/kitafino_logo_small.jpg', 149, 10, '');
    }
}

if ($monat_abr == 0) {
    $pdf->SetFont('Arial', 'B', 9);
    $pdf->setY($set_y);
    $pdf->setX(20);
    $pdf->Cell($cell_1_w + $cell_2_w, $row_h, 'Gesamt ' . $jahr_abr, 1, 0, 'L');

    $pdf->setX(20 + $cell_1_w + $cell_2_w);
    $pdf->Cell($cell_3_w, $row_h, $anzahl_gesamt_jahr, 1, 0, 'C');

    $pdf->setX(20 + $cell_1_w + $cell_2_w + $cell_3_w);
    $pdf->Cell($cell_4_w, $row_h, EURO . ' ' . preisAusgeben($gesamt_jahr), 1, 0, 'R');

    //$pdf->setY($set_y);
}

$pdf->setY($set_y + 10);
$pdf->setX(20);
$pdf->Cell(0, $row_h, utf8_decode('Dieses Schreiben wurde maschinell erstellt und ist daher ohne Unterschrift gültig.'), 0, 0, 'L');

if ($monat_abr == 0) {
    $file_stamp = '-' . $jahr_abr;
} else {
    $file_stamp = '-' . $monat_abr . '_' . $jahr_abr;
}

$add_name = 'Abrechnung';
$pdf->Output('kitafino_' . $add_name . $file_stamp . '.pdf', 'D');
