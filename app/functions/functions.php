<?php
require_once __DIR__ . '/../Dotenv.php';

function br2nl($string) {
    return preg_replace('#<br\s*?/?>#i', "", $string);
}

function erzeugeFahrerlistenPdf($daten_fahrerliste) {
    $trans_dates = array(
        'Monday' => 'Montag',
        'Tuesday' => 'Dienstag',
        'Wednesday' => 'Mittwoch',
        'Thursday' => 'Donnerstag',
        'Friday' => 'Freitag',
        'Saturday' => 'Samstag',
        'Sunday' => 'Sonntag',
        'Mon' => 'Mo',
        'Tue' => 'Di',
        'Wed' => 'Mi',
        'Thu' => 'Do',
        'Fri' => 'Fr',
        'Sat' => 'Sa',
        'Sun' => 'So'
    );

    require 'fpdf/fpdf.php';
    $pdf = new FPDF();

    $pdf->SetAutoPageBreak(false);
    $pdf->SetTextColor(80, 80, 80);

    foreach ($daten_fahrerliste as $datumkey => $daten_touren) {

        $datum_string = strftime('%d.%m.%Y', strtotime($datumkey));
        $tag_ger = $trans_dates[date('D', strtotime($datumkey))];

        foreach ($daten_touren as $tourname => $daten_zu_tour) {

            // $pdf->AddPage('L');

            $l_height = 10;
            $w_col_0 = 6; //#nr
            $w_col_1 = 75; //Einrichtung
            $w_col_2 = 40; //Adresse
            $w_col_3 = 60; //Fahrerinfo
            $w_col_4 = 65; //menü
            $w_col_5 = 37; //Fahrer Extra
            $w_col_6 = 8; //Anzahl
            /*   $x = 5;
              $y = 5;
              $pdf->setXY($x, $y);
              $pdf->SetFont('Arial', 'B', 14);
              $pdf->MultiCell(160, 8, $tag_ger . ' ' . $datum_string, 0, 'L');

              $pdf->setXY($x + 100, $y);
              $pdf->MultiCell(160, 8, utf8_decode($tourname), 0, 'L'); */

            $y = $y + $l_height;
            $kundenname_shown = '';
            $c = 0;

            $cr = 0;
            /*  echo '<pre>';
              var_dump($bestellinfo);
              echo '</pre>'; */
            $page_nr = 0;
            foreach ($daten_zu_tour as $kundenname => $bestelldaten) {

                $bestellungen_vorhanden = false;
                $bestellungen_vorhanden_speise2 = false;
                $count = 0;
                foreach ($bestelldaten['bestellungen'] as $speise_nr => $bestellinfos) {
                    $count++;
                    $font_weight = '';
                    if ($bestellinfos['anzahl'] > 0) {
                        $bestellungen_vorhanden = true;
                        if ($count == 2) {
                            $bestellungen_vorhanden_speise2 = true;
                        }
                    }
                }

                $sc = 0;

                $bestelldaten_keys = array_keys($bestelldaten['bestellungen']);

                /*  echo '<pre>';
                  var_dump($bestelldaten['bestellungen']);
                  var_dump($bestelldaten_keys);
                  echo '</pre>'; */
                foreach ($bestelldaten['bestellungen'] as $speise_nr_str => $bestellinfo) {
                    $break_p1 = 18;
                    if (count($bestelldaten['bestellungen']) == 2 && $cr == $break_p1 - 1) {
                        $break_p1 = 17;
                    }
                    $kunde_id = $bestellinfo['kunde_id'];

                    if ($bestellinfo['breakpage']) {

                    }
                    $cr++;
                    if ($cr == 1 || ($cr > $break_p1 && $page_nr <= 1)) {

                        $pdf->SetTextColor(0, 0, 0);
                        $pdf->SetFont('Arial', 'B', 12);
                        $page_nr++;

                        if ($page_nr == 2) {
                            $pdf->setXY(250, 5);
                            $pdf->MultiCell(160, 8, 'Seite ' . ($page_nr - 1) . ' von ' . $page_nr, 0, 'L');
                        }

                        $pdf->AddPage('L');

                        if ($cr > 1) {
                            $pdf->setXY(250, 5);
                            $pdf->MultiCell(160, 8, 'Seite ' . $page_nr . ' von ' . '2', 0, 'L');
                        }

                        $x = 5;
                        $y = 5;
                        $pdf->SetTextColor(0, 0, 0);
                        $pdf->SetFont('Arial', 'B', 14);
                        $pdf->setXY($x, $y);
                        $pdf->MultiCell(160, 8, $tag_ger . ' ' . $datum_string, 0, 'L');

                        $pdf->setXY($x + 100, $y);
                        $pdf->MultiCell(160, 8, utf8_decode($tourname), 0, 'L');
                        $y = $y + $l_height;
                    }

                    if ($bestellungen_vorhanden) {
                        $font_weight = 'B';
                        $pdf->SetTextColor(0, 0, 0);
                    } else {
                        $pdf->SetTextColor(120, 120, 120);
                        $font_weight = '';
                    }


                    $sc++;
                    $pdf->SetFillColor(255, 255, 255);
                    if ($bestellinfo['anzahl'] > 0) {

                    }

                    $pdf->SetFont('Arial', '', 10);
                    $pdf->setXY($x, $y);
                    if ($kundenname_shown != $kundenname) { //neuer Kunde
                        $c++;
                        $kundenname_shown = $kundenname;
                        $kundenname_pr = $kundenname;
                        $anschirft_pr = $bestelldaten['anschrift'];
                    }

                    if ($c % 2 == 0) { //highlight jede zweite zeile
                        // $pdf->SetFillColor(222, 222, 222);
                    }
                    if ($bestellungen_vorhanden_speise2) {
                        $pdf->SetFillColor(222, 222, 222);
                    }

                    $fix_height = 1;
                    $borders = 'LTRB';
                    $fix_h = 0;
                    if (count($bestelldaten['bestellungen']) > 1 && $sc == 1) {
                        $fix_height = 2;
                        $borders = 'LTR';
                        $fix_h = 1;
                    }
                    if (count($bestelldaten['bestellungen']) > 1 && $sc == 2) {
                        $fix_height = 1;
                        $borders = 'LBR';
                    }

                    $c_str = $c;
                    if ($sc > 1) {
                        $bestelldaten['anschrift'] = '';
                        $bestelldaten['fahrer_info'] = '';
                        $kundenname_pr = '';
                        $c_str = '';
                    }


                    $pdf->SetFont('Arial', $font_weight, 10);
                    $pdf->Cell($w_col_0, $l_height, $c_str, $borders, 0, 'L', 1);

                    $pdf->SetFont('Arial', $font_weight, 11);
                    $pdf->Cell($w_col_1, $l_height, utf8_decode($kundenname_pr), $borders, 0, 'L', 1);

                    $pdf->SetFont('Arial', '', 8);

                    if ($sc == 1) {
                        $pdf->Rect($w_col_0 + $w_col_1 + $w_col_5, $y, $w_col_2, $l_height * $fix_height, 'DF');

                        $pdf->setXY($w_col_0 + $w_col_1 + $w_col_5, $y);
                        $pdf->MultiCell($w_col_2, $l_height / 3, utf8_decode($bestelldaten['anschrift']), 'LTR', 'L', 1);

                        $pdf->setXY($w_col_0 + $w_col_1 + $w_col_5, $y);
                        $pdf->Rect($w_col_0 + $w_col_1 + $w_col_2 + $w_col_5, $y, $w_col_3, $l_height * $fix_height, 'DF');
                    }
                    $pdf->SetFont('Arial', '', 8);

                    $pdf->setXY($w_col_0 + $w_col_1 + $w_col_2 + $w_col_5, $y);
                    if ($sc == 1) {
                        $pdf->MultiCell($w_col_3, $l_height / (3 ), utf8_decode($bestelldaten['fahrer_info']), 'LTR', 'L', 1);
                    }

                    $pdf->setXY($w_col_0 + $w_col_1 + $w_col_2 + $w_col_3, $y);
                    $menu = $bestellinfo['menu'];
                    $fahrer_extra = $bestellinfo['fahrer_extra'];
                    if ($sc == 1 && $bestellinfo['anzahl'] > 0) {
                        $font_weight = '';
                    }
                    if ($sc == 2 && $bestellinfo['anzahl'] > 0) {
                        // $pdf->SetFillColor(160, 160, 160);
                        // $pdf->SetTextColor(255, 255, 255);
                        $font_weight = '';
                    }
                    if ($bestellinfo['anzahl'] == 0) {
                        $font_weight = '';
                        $menu = '';
                        $fahrer_extra = '';
                    }
                    $pdf->SetFont('Arial', $font_weight, 8);

                    $fix_when_strlen = 1;

                    if (strlen($menu) >= 45) {
                        $fix_when_strlen = 2;
                    }
                    if (strlen($menu) > 60) {
                        $pdf->SetFont('Arial', $font_weight, 8);
                    } else {
                        $pdf->SetFont('Arial', $font_weight, 8);
                    }

                    $pdf->setXY($w_col_0 + $w_col_1 + $w_col_2 + $w_col_3 + $w_col_5, $y);
                    if ($sc == 1) {
                        $pdf->Rect($w_col_0 + $w_col_1 + $w_col_2 + $w_col_3 + $w_col_5, $y, $w_col_4, $l_height * $fix_height, 'DF');
                    }
                    $borders_m = 1;
                    if ($sc == 2) {
                        $borders_m = 'LTR';
                    }

                    $pdf->MultiCell($w_col_4, $l_height / $fix_when_strlen, utf8_decode($menu), $borders_m, 'L', 1);

                    //$pdf->Cell($w_col_4, $l_height, utf8_decode($menu), 1, 0, 'L', 1);
                    //Spalte Fahrer Extra
                    $font_weight = 'B';
                    $pdf->SetFont('Arial', $font_weight, 10);
                    $pdf->setXY($w_col_0 + $w_col_1, $y);
                    if ($sc == 1) {
                        $pdf->setXY($w_col_0 + $w_col_1, $y);
                        $pdf->Rect($w_col_0 + $w_col_1, $y, $w_col_5, $l_height * $fix_height, 'DF');
                    }
                    $borders_m = 1;
                    if ($sc == 2) {
                        $borders_m = 'LTR';
                    }
                    $pdf->MultiCell($w_col_5, $l_height / $fix_when_strlen, utf8_decode($fahrer_extra), $borders_m, 'L', 1);

                    //Ende Spalte Fahrer Extra

                    $pdf->setXY($w_col_0 + $w_col_1 + $w_col_2 + $w_col_3 + $w_col_4 + $w_col_5, $y);

                    $pdf->SetFont('Arial', $font_weight, 10);

                    $pdf->Cell($w_col_6, $l_height, utf8_decode($bestellinfo['anzahl']), 1, 1, 'L', 1);

                    if ($bestellinfo['anzahl'] == 0 && !$bestellungen_vorhanden) {
                        $pdf->SetDrawColor(160, 160, 160);
                        $pdf->SetLineWidth(0.5);
                        $pdf->Line($x + 10, $y + ($l_height / 2), $x + 280, $y + ($l_height / 2));
                        $pdf->SetLineWidth(0.2);
                        $pdf->SetDrawColor(0, 0, 0);
                    }


                    //neue zeile
                    $y = $y + $l_height;
                    $pdf->setXY($x, $y);
                    $pdf->Ln($l_height);
                }
            }
        }
    }

    $pdf->Output('Fahrerliste_' . $datumkey . '.pdf', 'D');
}

function erzeugeFahrerlistenPdf2($daten_fahrerliste) {
    $trans_dates = array(
        'Monday' => 'Montag',
        'Tuesday' => 'Dienstag',
        'Wednesday' => 'Mittwoch',
        'Thursday' => 'Donnerstag',
        'Friday' => 'Freitag',
        'Saturday' => 'Samstag',
        'Sunday' => 'Sonntag',
        'Mon' => 'Mo',
        'Tue' => 'Di',
        'Wed' => 'Mi',
        'Thu' => 'Do',
        'Fri' => 'Fr',
        'Sat' => 'Sa',
        'Sun' => 'So'
    );

    require 'fpdf/fpdf.php';
    $pdf = new FPDF();

    $pdf->SetAutoPageBreak(false);
    $pdf->SetTextColor(80, 80, 80);

    foreach ($daten_fahrerliste as $datumkey => $daten_touren) {

        $datum_string = strftime('%d.%m.%Y', strtotime($datumkey));
        $tag_ger = $trans_dates[date('D', strtotime($datumkey))];

        foreach ($daten_touren as $tourname => $daten_zu_tour) {

            // $pdf->AddPage('L');

            $l_height = 10;
            $w_col_0 = 6; //#nr
            $w_col_1 = 75; //Einrichtung
            $w_col_2 = 40; //Adresse
            $w_col_3 = 60; //Fahrerinfo
            $w_col_4 = 65; //menü
            $w_col_5 = 37; //Fahrer Extra
            $w_col_6 = 8; //Anzahl
            /*   $x = 5;
              $y = 5;
              $pdf->setXY($x, $y);
              $pdf->SetFont('Arial', 'B', 14);
              $pdf->MultiCell(160, 8, $tag_ger . ' ' . $datum_string, 0, 'L');

              $pdf->setXY($x + 100, $y);
              $pdf->MultiCell(160, 8, utf8_decode($tourname), 0, 'L'); */

            $y = $y + $l_height;
            $kundenname_shown = '';
            $c = 0;

            $cr = 0;
            /*  echo '<pre>';
              var_dump($bestellinfo);
              echo '</pre>'; */
            $page_nr = 0;
            foreach ($daten_zu_tour as $kundenname => $bestelldaten) {

                $bestellungen_vorhanden = false;
                $bestellungen_vorhanden_speise2 = false;
                $count = 0;
                foreach ($bestelldaten['bestellungen'] as $speise_nr => $bestellinfos) {
                    $count++;
                    $font_weight = '';
                    if ($bestellinfos['anzahl'] > 0) {
                        $bestellungen_vorhanden = true;
                        if ($count == 2) {
                            $bestellungen_vorhanden_speise2 = true;
                        }
                    }
                }

                $sc = 0;

                $bestelldaten_keys = array_keys($bestelldaten['bestellungen']);

                /*  echo '<pre>';
                  var_dump($bestelldaten['bestellungen']);
                  var_dump($bestelldaten_keys);
                  echo '</pre>'; */
                foreach ($bestelldaten['bestellungen'] as $speise_nr_str => $bestellinfo) {
                    $break_p1 = 18;
                    if (count($bestelldaten['bestellungen']) == 2 && $cr == $break_p1 - 1) {
                        $break_p1 = 17;
                    }
                    $kunde_id = $bestellinfo['kunde_id'];

                    if ($bestellinfo['breakpage']) {

                    }
                    $cr++;
                    if ($cr == 1 || ($cr > $break_p1 && $page_nr <= 1)) {

                        $pdf->SetTextColor(0, 0, 0);
                        $pdf->SetFont('Arial', 'B', 12);
                        $page_nr++;

                        if ($page_nr == 2) {
                            $pdf->setXY(250, 5);
                            $pdf->MultiCell(160, 8, 'Seite ' . ($page_nr - 1) . ' von ' . $page_nr, 0, 'L');
                        }

                        $pdf->AddPage('L');

                        if ($cr > 1) {
                            $pdf->setXY(250, 5);
                            $pdf->MultiCell(160, 8, 'Seite ' . $page_nr . ' von ' . '2', 0, 'L');
                        }

                        $x = 5;
                        $y = 5;
                        $pdf->SetTextColor(0, 0, 0);
                        $pdf->SetFont('Arial', 'B', 14);
                        $pdf->setXY($x, $y);
                        $pdf->MultiCell(160, 8, $tag_ger . ' ' . $datum_string, 0, 'L');

                        $pdf->setXY($x + 100, $y);
                        $pdf->MultiCell(160, 8, utf8_decode($tourname), 0, 'L');
                        $y = $y + $l_height;
                    }

                    if ($bestellungen_vorhanden) {
                        $font_weight = 'B';
                        $pdf->SetTextColor(0, 0, 0);
                    } else {
                        $pdf->SetTextColor(120, 120, 120);
                        $font_weight = '';
                    }


                    $sc++;
                    $pdf->SetFillColor(255, 255, 255);
                    if ($bestellinfo['anzahl'] > 0) {

                    }

                    $pdf->SetFont('Arial', '', 10);
                    $pdf->setXY($x, $y);
                    if ($kundenname_shown != $kundenname) { //neuer Kunde
                        $c++;
                        $kundenname_shown = $kundenname;
                        $kundenname_pr = $kundenname;
                        $anschirft_pr = $bestelldaten['anschrift'];
                    }

                    if ($c % 2 == 0) { //highlight jede zweite zeile
                        // $pdf->SetFillColor(222, 222, 222);
                    }
                    if ($bestellungen_vorhanden_speise2) {
                        $pdf->SetFillColor(222, 222, 222);
                    }

                    $fix_height = 1;
                    $borders = 'LTRB';
                    $fix_h = 0;
                    if (count($bestelldaten['bestellungen']) > 1 && $sc == 1) {
                        $fix_height = 2;
                        $borders = 'LTR';
                        $fix_h = 1;
                    }
                    if (count($bestelldaten['bestellungen']) > 1 && $sc == 2) {
                        $fix_height = 1;
                        $borders = 'LBR';
                    }

                    $c_str = $c;
                    if ($sc > 1) {
                        $bestelldaten['anschrift'] = '';
                        $bestelldaten['fahrer_info'] = '';
                        $kundenname_pr = '';
                        $c_str = '';
                    }


                    $pdf->SetFont('Arial', $font_weight, 10);
                    $pdf->Cell($w_col_0, $l_height, $c_str, $borders, 0, 'L', 1);

                    $pdf->SetFont('Arial', $font_weight, 11);
                    $pdf->Cell($w_col_1, $l_height, utf8_decode($kundenname_pr), $borders, 0, 'L', 1);

                    $pdf->SetFont('Arial', '', 8);

                    if ($sc == 1) {
                        $pdf->Rect($w_col_0 + $w_col_1 + 5, $y, $w_col_2, $l_height * $fix_height, 'DF');

                        $pdf->MultiCell($w_col_2, $l_height / 3, utf8_decode($bestelldaten['anschrift']), 'LTR', 'L', 1);

                        $pdf->setXY($w_col_0 + $w_col_1 + $w_col_2, $y);
                        $pdf->Rect($w_col_0 + $w_col_1 + $w_col_2, $y, $w_col_3, $l_height * $fix_height, 'DF');
                    }
                    $pdf->SetFont('Arial', '', 8);

                    if ($sc == 1) {
                        $pdf->MultiCell($w_col_3, $l_height / (3 ), utf8_decode($bestelldaten['fahrer_info']), 'LTR', 'L', 1);
                    }

                    $pdf->setXY($w_col_0 + $w_col_1 + $w_col_2 + $w_col_3, $y);
                    $menu = $bestellinfo['menu'];
                    $fahrer_extra = $bestellinfo['fahrer_extra'];
                    if ($sc == 1 && $bestellinfo['anzahl'] > 0) {
                        $font_weight = '';
                    }
                    if ($sc == 2 && $bestellinfo['anzahl'] > 0) {
                        // $pdf->SetFillColor(160, 160, 160);
                        // $pdf->SetTextColor(255, 255, 255);
                        $font_weight = '';
                    }
                    if ($bestellinfo['anzahl'] == 0) {
                        $font_weight = '';
                        $menu = '';
                        $fahrer_extra = '';
                    }
                    $pdf->SetFont('Arial', $font_weight, 8);

                    $fix_when_strlen = 1;

                    if (strlen($menu) >= 45) {
                        $fix_when_strlen = 2;
                    }
                    if (strlen($menu) > 60) {
                        $pdf->SetFont('Arial', $font_weight, 8);
                    } else {
                        $pdf->SetFont('Arial', $font_weight, 8);
                    }

                    if ($sc == 1) {
                        $pdf->setXY($w_col_0 + $w_col_1 + $w_col_2 + $w_col_3, $y);
                        $pdf->Rect($w_col_0 + $w_col_1 + $w_col_2 + $w_col_3, $y, $w_col_4, $l_height * $fix_height, 'DF');
                    }
                    $borders_m = 1;
                    if ($sc == 2) {
                        $borders_m = 'LTR';
                    }

                    $pdf->MultiCell($w_col_4, $l_height / $fix_when_strlen, utf8_decode($menu), $borders_m, 'L', 1);

                    //$pdf->Cell($w_col_4, $l_height, utf8_decode($menu), 1, 0, 'L', 1);
                    //Spalte Fahrer Extra
                    $font_weight = 'B';
                    $pdf->SetFont('Arial', $font_weight, 10);
                    $pdf->setXY($w_col_0 + $w_col_1 + $w_col_2 + $w_col_3 + $w_col_4, $y);
                    if ($sc == 1) {
                        $pdf->setXY($w_col_0 + $w_col_1 + $w_col_2 + $w_col_3 + $w_col_4, $y);
                        $pdf->Rect($w_col_0 + $w_col_1 + $w_col_2 + $w_col_3 + $w_col_4, $y, $w_col_5, $l_height * $fix_height, 'DF');
                    }
                    $borders_m = 1;
                    if ($sc == 2) {
                        $borders_m = 'LTR';
                    }
                    $pdf->MultiCell($w_col_5, $l_height / $fix_when_strlen, utf8_decode($fahrer_extra), $borders_m, 'L', 1);

                    //Ende Spalte Fahrer Extra

                    $pdf->setXY($w_col_0 + $w_col_1 + $w_col_2 + $w_col_3 + $w_col_4 + $w_col_5, $y);

                    $pdf->SetFont('Arial', $font_weight, 10);

                    $pdf->Cell($w_col_6, $l_height, utf8_decode($bestellinfo['anzahl']), 1, 1, 'L', 1);

                    if ($bestellinfo['anzahl'] == 0 && !$bestellungen_vorhanden) {
                        $pdf->SetDrawColor(160, 160, 160);
                        $pdf->SetLineWidth(0.5);
                        $pdf->Line($x + 10, $y + ($l_height / 2), $x + 280, $y + ($l_height / 2));
                        $pdf->SetLineWidth(0.2);
                        $pdf->SetDrawColor(0, 0, 0);
                    }


                    //neue zeile
                    $y = $y + $l_height;
                    $pdf->setXY($x, $y);
                    $pdf->Ln($l_height);
                }
            }
        }
    }

    $pdf->Output('Fahrerliste_' . $datumkey . '.pdf', 'D');
}

function ermittleBestellinfosZuTag($kunde, $tag, $monat, $jahr, $kundeVerwaltung, $standardportionenVerwaltung, $portionenaenderungVerwaltung, $menunamenVerwaltung) {
    $return_array = array();

    $tagts = mktime(12, 0, 0, $monat, $tag, $jahr);
    $kunde_id = $kunde->getId();

    //  $standardportionen = $standardportionenVerwaltung->findeAnhandVonKundenId($kunde_id);
    $wochentag_string = strftime('%a', $tagts);
    switch ($wochentag_string) {
        case 'Mo':
            $wochenstartts = $tagts;
            break;
        case 'Di':
            $wochenstartts = $tagts - 86400;
            break;
        case 'Mi':
            $wochenstartts = $tagts - 86400 * 2;
            break;
        case 'Do':
            $wochenstartts = $tagts - 86400 * 3;
            break;
        case 'Fr':
            $wochenstartts = $tagts - 86400 * 4;
            break;
    }
    $starttag = date('d', $wochenstartts);
    $startmonat = date('m', $wochenstartts);
    $startjahr = date('Y', $wochenstartts);

    $portionenaenderungen = $portionenaenderungVerwaltung->findeAlleZuKundenIdUndWochenstartdatum($kunde_id, $starttag, $startmonat, $startjahr);

    foreach ($portionenaenderungen as $portionenaenderung) {
        $speise_nr = $portionenaenderung->getSpeiseNr();
        $standardportionen = $standardportionenVerwaltung->findeAnhandVonKundenIdUndSpeiseNr($kunde_id, $speise_nr);

        $portionen_mo = $portionenaenderung->getPortionenMo();
        $portionen_di = $portionenaenderung->getPortionenDi();
        $portionen_mi = $portionenaenderung->getPortionenMi();
        $portionen_do = $portionenaenderung->getPortionenDo();
        $portionen_fr = $portionenaenderung->getPortionenFr();

        switch ($wochentag_string) {
            case 'Mo':
                $portionen = $portionen_mo;
                break;
            case 'Di':
                $portionen = $portionen_di;
                break;
            case 'Mi':
                $portionen = $portionen_mi;
                break;
            case 'Do':
                $portionen = $portionen_do;
                break;
            case 'Fr':
                $portionen = $portionen_fr;
                break;
        }

        $menuname_obj = $menunamenVerwaltung->findeAnhandVonTagMonatJahrSpeiseNr($tag, $monat, $jahr, $speise_nr);

        $return_array['Speise ' . $speise_nr]['menu'] = $menuname_obj->getBezeichnung();
        $return_array['Speise ' . $speise_nr]['fahrer_extra'] = $menuname_obj->getFahrerExtra();
        $return_array['Speise ' . $speise_nr]['anzahl'] = $portionen;
        $return_array['Speise ' . $speise_nr]['kunde_id'] = $kunde_id;
    }
    ksort($return_array);
    /*
      $portionenaenderung = $portionenaenderungVerwaltung->findeAnhandVonKundenIdUndWochenstarttagts($kunde_id, $wochenstartts);
      if ($portionenaenderung->getId()) {
      $portionen_mo = $portionenaenderung->getPortionenMo();
      $portionen_di = $portionenaenderung->getPortionenDi();
      $portionen_mi = $portionenaenderung->getPortionenMi();
      $portionen_do = $portionenaenderung->getPortionenDo();
      $portionen_fr = $portionenaenderung->getPortionenFr();
      $aenderung = true;
      } else {
      $portionen_mo = $standardportionen->getPortionenMo();
      $portionen_di = $standardportionen->getPortionenDi();
      $portionen_mi = $standardportionen->getPortionenMi();
      $portionen_do = $standardportionen->getPortionenDo();
      $portionen_fr = $standardportionen->getPortionenFr();
      $aenderung = false;
      }
      switch ($wochentag_string) {
      case 'Mo':
      $portionen = $portionen_mo;
      break;
      case 'Di':
      $portionen = $portionen_di;
      break;
      case 'Mi':
      $portionen = $portionen_mi;
      break;
      case 'Do':
      $portionen = $portionen_do;
      break;
      case 'Fr':
      $portionen = $portionen_fr;
      break;
      }

      $return_array['portionen'] = $portionen;
      echo '<pre>';
      var_dump($return_array);
      echo '</pre>';
     */
    return $return_array;
}

function erzeugeExcel($spalten, $daten, $speicherort, $dateiname, $sum_col = 'A', $sum_col2 = 'G') {

    date_default_timezone_set('Europe/Berlin');

    /* require_once('../verwaltung_alt/PHPExcel-1.7.5/PHPExcel.php');
     require_once('../verwaltung_alt/PHPExcel-1.7.5/PHPExcel/IOFactory.php');*/

    require '../PhpSpreadsheet/PhpOffice/autoload.php';

    //$xls = new PHPExcel();
    $xls = new \PhpOffice\PhpSpreadsheet\Spreadsheet();
    $xls->getProperties()->setCreator("S-Br")
        ->setLastModifiedBy("S-Bar")
        ->setTitle($dateiname)
        ->setSubject($dateiname);

    $sheet = $xls->setActiveSheetIndex(0);

    $style = array(
        'alignment' => array(
            'vertical' => PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_TOP
        )
    );
    $xls->getDefaultStyle()->applyFromArray($style);

    $line = 1;
    foreach (range('A', 'H') as $columnID) {
        $sheet->getColumnDimension($columnID)
            ->setAutoSize(true);
    }
    $spalten_letter = range('A', 'Z');
    foreach ($spalten as $index => $spalte) {
        $sheet->setCellValue("$spalten_letter[$index]" . '1', "$spalte");
    }

    foreach ($daten as $datensatz) {

        $line++;
        foreach ($datensatz as $index_daten => $data) {

            $sheet->setCellValue("$spalten_letter[$index_daten]" . "$line", "$data");
        }
    }
    $last_line = $line;
    $line++;
    if ($sum_col != 'none') {
        $sheet->setCellValue("$sum_col$line", "=SUM($sum_col" . "2:$sum_col" . $last_line . ")");
    }
    if ($sum_col2 != 'none') {
        $sheet->setCellValue("$sum_col2$line", "=SUM($sum_col2" . "2:$sum_col2" . $last_line . ")");
    }


    $writer = PhpOffice\PhpSpreadsheet\IOFactory::createWriter($xls, "Xls");

    $filename = $dateiname . ".xls";

    ob_end_clean();

    if (trim($speicherort)) {
        $writer->save($speicherort . '/' . $filename);
        return false;
    } else {
        header('Content-type: application/vnd.ms-excel');
        header('Content-Disposition: attachment; filename="' . utf8_decode($filename) . '"');
        $writer->save('php://output');
        die;
    }
//$writer->save('buchhaltung/'.$filename);
}

function erzeugeExcel_ALT($spalten, $daten, $speicherort, $dateiname, $sum_col = 'A', $sum_col2 = 'G') {

    date_default_timezone_set('Europe/Berlin');

    require_once('../verwaltung_alt/PHPExcel-1.7.5/PHPExcel.php');
    require_once('../verwaltung_alt/PHPExcel-1.7.5/PHPExcel/IOFactory.php');



    $xls = new PHPExcel();
    $xls->getProperties()->setCreator("S-Br")
        ->setLastModifiedBy("S-Bar")
        ->setTitle($dateiname)
        ->setSubject($dateiname);

    $sheet = $xls->setActiveSheetIndex(0);

    $style = array(
        'alignment' => array(
            'vertical' => PHPExcel_Style_Alignment::VERTICAL_TOP
        )
    );
    $xls->getDefaultStyle()->applyFromArray($style);

    $line = 1;
    foreach (range('A', 'H') as $columnID) {
        $sheet->getColumnDimension($columnID)
            ->setAutoSize(true);
    }
    $spalten_letter = range('A', 'Z');
    foreach ($spalten as $index => $spalte) {
        $sheet->setCellValue("$spalten_letter[$index]" . '1', "$spalte");
    }

    foreach ($daten as $datensatz) {

        $line++;
        foreach ($datensatz as $index_daten => $data) {
            $sheet->setCellValue("$spalten_letter[$index_daten]" . "$line", "$data");
        }
    }
    $last_line = $line;
    $line++;
    if ($sum_col != 'none') {
        $sheet->setCellValue("$sum_col$line", "=SUM($sum_col" . "2:$sum_col" . $last_line . ")");
    }
    if ($sum_col2 != 'none') {
        $sheet->setCellValue("$sum_col2$line", "=SUM($sum_col2" . "2:$sum_col2" . $last_line . ")");
    }


    $writer = PHPExcel_IOFactory::createWriter($xls, "Excel5");

    $filename = $dateiname . ".xls";

    ob_end_clean();

    if (trim($speicherort)) {
        $writer->save($speicherort . '/' . $filename);
        return false;
    } else {
        header('Content-type: application/vnd.ms-excel');
        header('Content-Disposition: attachment; filename="' . utf8_decode($filename) . '"');
        $writer->save('php://output');
    }
//$writer->save('buchhaltung/'.$filename);
}

function erzeugeTagesaufstellungPdfEtikettendruck($tag, $monat, $jahr, $starttag, $startmonat, $startjahr, $speise_id, $bestellung, $bestellung_has_speiseVerwaltung, $speiseVerwaltung, $menge_pro_portionVerwaltung, $standardportionenVerwaltung, $portionenaenderungVerwaltung, $kunden, $indifaktorVerwaltung, $bemerkungzutagVerwaltung, $bemerkungzuspeiseVerwaltung, $speise_nr = 1, $kundeVerwaltung = NULL) {
    require 'fpdf/fpdf.php';
    $pdf = new FPDF();
    $pdf->SetAutoPageBreak(false);
    $pdf->SetTextColor(80, 80, 80);
    $pdf->SetFont('Arial', '', 12);
    $pdf->AddPage();

    $count_etiketten = 0;
    $y = 21.5;
    $x = 8.48;

    $bestelltag_ts_check = $ts = mktime(12, 0, 0, $monat, $tag, $jahr);

    $i = 1;
    $bestellung_has_speise_array = $bestellung_has_speiseVerwaltung->findeAnhandVonBestellungId($bestellung->getId());

    $gesamtmenge_tag_speise = 0;
    $gesamtmenge_tag_portionen = 0;
    $c = $i - 2;
    $d = $i - 1;

    $speise = $speiseVerwaltung->findeAnhandVonId($speise_id);

    $speise_bezeichnung_str = substr($speise->getBezeichnung(), 0, 38);
    $last_tour = new Kunde();

    $padding_x = 8.48;
    $padding_y = 21.5;
    $padding_y_etikett = 5;
    $padding_x_etikett = 5;
    $breite_etikett = 97;
    $hoehe_etikett = 42.3;
    $y_einrichtungsname = 15;
    $y_code = 27;
    foreach ($kunden as $kunde) {
        $is_kuechenstrolche = false;

        if ($kunde->getStartdatum() && $bestelltag_ts_check < $kunde->getStartdatum()) {
            continue;
        }
        if ($speise_nr == 2 && $kunde->getAnzahlSpeisen() == 1 && !$kunde->getStaedtischerKunde()) {
            continue;
        }
        if ($speise_nr <= 2 && ($kunde->getBioKunde() || $kunde->getStaedtischerKunde())) {
            continue;
        }
        if ($speise_nr > 2 && (!$kunde->getBioKunde() && !$kunde->getStaedtischerKunde())) {
            continue;
        }

        $speise_id = $speise->getId();
        $indi_faktor = $indifaktorVerwaltung->findeAnhandVonSpeiseIdUndKundeId($speise_id, $kunde->getId());
        $faktor = $indi_faktor->getFaktor();
        $einrichtungskategorie_id = $kunde->getEinrichtungskategorieId();
        $menge_pro_portion = $menge_pro_portionVerwaltung->findeAnhandVonSpeiseIdUndEinrichtungskategorieId($speise_id, $einrichtungskategorie_id);

        $standardportionen = $standardportionenVerwaltung->findeAnhandVonKundenIdUndSpeiseNr($kunde->getId(), $speise_nr);
        $portionenaenderung = $portionenaenderungVerwaltung->findeAnhandVonKundenIdUndWochenstartdatum($kunde->getId(), $starttag, $startmonat, $startjahr, $speise_nr);
        if ($portionenaenderung->getId()) {
            $portionen_mo = $portionenaenderung->getPortionenMo();
            $portionen_di = $portionenaenderung->getPortionenDi();
            $portionen_mi = $portionenaenderung->getPortionenMi();
            $portionen_do = $portionenaenderung->getPortionenDo();
            $portionen_fr = $portionenaenderung->getPortionenFr();
            $aenderung = true;
        } else {
            $portionen_mo = $standardportionen->getPortionenMo();
            $portionen_di = $standardportionen->getPortionenDi();
            $portionen_mi = $standardportionen->getPortionenMi();
            $portionen_do = $standardportionen->getPortionenDo();
            $portionen_fr = $standardportionen->getPortionenFr();
            $aenderung = false;
        }



        $wochentag_string = strftime('%a', mktime(12, 0, 0, $monat, $tag, $jahr));
        switch ($wochentag_string) {
            case 'Mo':
                $portionen = $portionen_mo;
                if ($portionen_mo != $standardportionen->getPortionenMo()) {
                    //$portionen .= ' (' . $standardportionen->getPortionenMo().')';
                }
                break;
            case 'Di':
                $portionen = $portionen_di;
                if ($portionen_di != $standardportionen->getPortionenDi()) {
                    //$portionen .= ' (' . $standardportionen->getPortionenDi().')';
                }
                break;
            case 'Mi':
                $portionen = $portionen_mi;
                if ($portionen_mi != $standardportionen->getPortionenMi()) {
                    //$portionen .= ' (' . $standardportionen->getPortionenMi().')';
                }
                break;
            case 'Do':
                $portionen = $portionen_do;
                if ($portionen_do != $standardportionen->getPortionenDo()) {
                    //$portionen .= ' (' . $standardportionen->getPortionenDo().')';
                }
                break;
            case 'Fr':
                $portionen = $portionen_fr;
                if ($portionen_fr != $standardportionen->getPortionenFr()) {
                    //$portionen .= ' (' . $standardportionen->getPortionenFr().')';
                }
                break;
        }

        $gesamtmenge_tag_portionen += $portionen;
        $gesamtmenge_kunde = $portionen * ($menge_pro_portion->getMenge() * $faktor);
        $einheit = $menge_pro_portion->getEinheit();

        $padding_x = 8.48;
        $padding_y = 21.5;
        $padding_y_etikett = 5;
        $padding_x_etikett = 2;
        $breite_etikett = 97;
        $hoehe_etikett = 42.3;
        $y_einrichtungsname = 13;
        $l_height_einr = 5;
        $l_height_einr = 6;
        $y_code = 28;
        $w_logo = 12;
        $h_logo = 8.1;
        $x_tourname = 38;
        $font_size_code = 8;
        $font_size_einr = 14;
        $font_size_einr = 20;
        $font_size_tour = 22;
        if ($kunde->getEinrichtungskategorieId() == 5) {

        } else {

            if ($portionen > 0) {
                $tour_zu_kunde = $kundeVerwaltung->findeTourZuKundenReihenfolge($kunde->getLieferreihenfolge());
                $tour_zu_kunde = $tour_zu_kunde[0];
                if ($tour_zu_kunde == NULL) {
                    $tour_zu_kunde = new Kunde();
                }




                if (/* $speise_nr > 1 && */ $last_tour->getId() != $tour_zu_kunde->getId()) {

                    for ($te = 1; $te <= 3; $te++) {

                        if ($te > 1 && substr($tour_zu_kunde->getName(), 0, 17) == 'BEGINN PRODUKTION') {
                            continue;
                        }


                        $count_etiketten++;
                        $pdf->Rect($x, $y, $breite_etikett, $hoehe_etikett);

                        //TOURNAME
                        $tourname = $tour_zu_kunde->getName();
                        $show_tour_at_date = false;
                        if (substr($tourname, 0, 5) == 'Tour ') {
                            $tourname = substr($tourname, 5);
                            $show_tour_at_date = true;
                        }
                        $pdf->setXY($x + $padding_x_etikett, $y + $y_einrichtungsname + 5);
                        $pdf->SetFont('Arial', 'B', $font_size_tour);
                        $pdf->MultiCell($breite_etikett - 2 * $padding_x_etikett, 8, utf8_decode($tourname), 0, 'L');

                        //DATUM
                        $pdf->setXY($x + $padding_x_etikett, $y + $padding_y_etikett);
                        $pdf->SetFont('Arial', 'B', 11);
                        $pdf->MultiCell($breite_etikett, 0, utf8_decode(strftime('%a %d.%m.%y', mktime(12, 0, 0, $monat, $tag, $jahr))), 0, 'L');
                        if ($show_tour_at_date) {
                            $pdf->setXY($x + $padding_x_etikett, $y + $padding_y_etikett + 5);
                            $pdf->SetFont('Arial', 'B', 11);
                            $pdf->MultiCell($breite_etikett, 0, 'TOUR', 0, 'L');
                        }

                        //SPEISE BEZEICHNUNG
                        $pdf->setXY($x + $padding_x_etikett + $w_logo + 20, $y + $padding_y_etikett);
                        $pdf->SetFont('Arial', 'B', 11);
                        $pdf->MultiCell($breite_etikett, 0, utf8_decode($speise_bezeichnung_str . ' (S' . $speise_nr . ')'), 0, 'L');

                        $x = $x + $breite_etikett;

                        if ($count_etiketten > 1 && $count_etiketten % 2 === 0) {
                            $y = $y + $hoehe_etikett;
                            $x = $padding_x;
                            $pdf->setXY($x, $y);
                        }
                        if ($count_etiketten > 0 && $count_etiketten % 12 === 0) {
                            $pdf->AddPage();
                            $y = $padding_y;
                            $x = $padding_x;
                            $pdf->setXY($x, $y);
                        }
                    }

                }
                if ($tour_zu_kunde == NULL) {
                    $tour_zu_kunde = new Kunde();
                }
                $last_tour = $tour_zu_kunde;

                $bemerkungen_str = '';
                $bemerkung_zu_tag = $bemerkungzutagVerwaltung->findeAnhandVonKundeIdUndDatumUndSpeiseId($kunde->getId(), $tag, $monat, $jahr, $speise->getId());
                $bemerkung_zu_speise = $bemerkungzuspeiseVerwaltung->findeAnhandVonKundeIdUndSpeiseId($kunde->getId(), $speise->getId());

                if ($bemerkung_zu_speise->getBemerkung() != '') {
                    $bemerkungen_str .= $bemerkung_zu_speise->getBemerkung() . '; ';
                }
                if ($bemerkung_zu_tag->getBemerkung() != '') {
                    $bemerkungen_str .= $bemerkung_zu_tag->getBemerkung() . '; ';
                }
                if ($kunde->getBemerkung() != '') {

                }
                $code = '';
                if ($speise->getKaltVerpackt()) {
                    $code = $kunde->getId() . '-' . $tag . $monat . $jahr . '-' . $speise_nr . '-' . '0' . '-' . $speise->getId();
                }
                if ($speise->getCooled()) {
                    $code = '';
                }






                if ($kunde->getEinrichtungskategorieId() == 6) {

                } else { //BOXEN ZU KUNDE
                    //LOGO IMG
                    $add_y = 5;

                    if (stripos($tour_zu_kunde->getName(),'KÜCHENSTROLCHE') === false  &&
                        stripos($tour_zu_kunde->getName(), 'Küchenstrolche') === false
                        && stripos($kunde->getName(), 'KÜCHENSTROLCHE') === false  && stripos($kunde->getName(), 'Küchenstrolche') === false

                    )  {
                        $pdf->Image('https://www.s-bar.net/verwaltung_dev/images/logo_sw.png', $x + 2, $y + 3, $w_logo, 8.1, 'PNG');

                    } else {
                        $pdf->Image('https://www.s-bar.net/verwaltung_dev/images/kuestro_logo_sw.png', $x + 2, $y + 3, $w_logo, 8.1, 'PNG');

                    }
                    /*   echo '<pre>';
                          var_dump(stripos('KÜCHENSTROLCHE', $tour_zu_kunde->getName()));
                  var_dump(stripos('KÜCHENSTROLCHE', utf8_decode($kunde->getName())));
                  var_dump($tour_zu_kunde->getName());
                  var_dump($kunde->getName());
                  echo '</pre>';   */

                    //EINRICHTUNGSNAME
                    $pdf->setXY($x + $padding_x_etikett, $y + $y_einrichtungsname);
                    $pdf->SetFont('Arial', 'B', $font_size_einr);
                    $pdf->MultiCell($breite_etikett - 2 * $padding_x_etikett, $l_height_einr, utf8_decode($kunde->getName()), 0, 'L');

                    //MENGE
                    $pdf->setXY($x + $padding_x_etikett, $y + $y_einrichtungsname + 9 + $add_y);
                    $pdf->SetFont('Arial', '', 12);
                    $pdf->MultiCell($breite_etikett - $breite_etikett / 2, 6, utf8_decode('XT ' . $gesamtmenge_kunde), 0, 'L');

                    //BEMERKUNG

                    $pdf->setXY($x + $padding_x_etikett + 23, $y + $y_einrichtungsname + 9 + $add_y - 1);
                    $pdf->SetFont('Arial', 'IBU', 12);
                    //  $pdf->MultiCell($breite_etikett, 6, utf8_decode($bemerkungen_str), 0, 'L');
                    //var_dump($_SESSION);
                    $pdf->MultiCell($breite_etikett - 25, 6, utf8_decode($bemerkungen_str), 0, 'L');

                    //DATUM
                    $pdf->setXY($x + $padding_x_etikett + $w_logo, $y + $padding_y_etikett);
                    $pdf->SetFont('Arial', 'B', 11);
                    $pdf->MultiCell($breite_etikett, 0, utf8_decode(strftime('%a %d.%m.%y', mktime(12, 0, 0, $monat, $tag, $jahr))), 0, 'L');
                    //SPEISE BEZEICHNUNG
                    $pdf->setXY($x + $padding_x_etikett + $w_logo, $y + $padding_y_etikett + 5);
                    $pdf->SetFont('Arial', 'B', 11);
                    $pdf->MultiCell($breite_etikett, 0, utf8_decode($speise_bezeichnung_str . ' (S' . $speise_nr . ')'), 0, 'L');
                    //TOURNAME
                    $pdf->setXY($x + $x_tourname, $y + $padding_y_etikett);
                    $pdf->SetFont('Arial', 'B', 12);
                    $pdf->MultiCell($breite_etikett, 0, utf8_decode($tour_zu_kunde->getName()), 0, 'L');

                    if ($code) {
                        //CODE IMG
                        $pdf->Image('https://www.s-bar.net/verwaltung/functions/barcode.php?text=' . $code, $x + $padding_x_etikett, $y + $y_code + 5, $breite_etikett - 2 * $padding_x_etikett, 5, 'PNG');
                        //CODE STRING
                        $pdf->setXY($x + 5, $y + $y_code + 12);
                        $pdf->SetFont('Arial', '', $font_size_code);
                        $pdf->MultiCell($breite_etikett - 2 * $padding_x_etikett, 0, $code, 0, 'C');
                    }
                    /*
                      $sheet->setCellValue("A$i", $kunde->getName());
                      $sheet->setCellValue("B$i", $gesamtmenge_kunde );
                      $sheet->setCellValue("C$i", $bemerkungen_str);
                      $sheet->setCellValue("D$i", $speise->getBezeichnung());
                      $sheet->setCellValue("E$i", strftime('%a %d.%m.%y', mktime(12, 0, 0, $monat, $tag, $jahr)));
                      $sheet->setCellValue("F$i", $code);
                      $sheet->setCellValue("G$i", $tour_zu_kunde->getName());
                     */
                    $count_etiketten++;
                    $pdf->Rect($x, $y, $breite_etikett, $hoehe_etikett);
                    $x = $x + $breite_etikett;
                    if ($count_etiketten > 1 && $count_etiketten % 2 === 0) {
                        $y = $y + $hoehe_etikett;
                        $x = $padding_x;
                        $pdf->setXY($x, $y);
                    }
                    if ($count_etiketten > 0 && $count_etiketten % 12 === 0) {
                        $pdf->AddPage();
                        $y = $padding_y;
                        $x = $padding_x;
                        $pdf->setXY($x, $y);
                    }
                }
                $i++;
            }
        }



        if ($portionen * 1 > 0) {

        }
        $gesamtmenge_kunde = $portionen * ($menge_pro_portion->getMenge() * $faktor);
        $gesamtmenge_tag_speise += $gesamtmenge_kunde;
    }

    if ($einheit == 'g') {
        $gesamtmenge_tag_speise_kg = $gesamtmenge_tag_speise / 1000;
        $gesamtmenge_tag_speise = $gesamtmenge_tag_speise_kg;
    } else {
        $gesamtmenge_tag_speise = $gesamtmenge_tag_speise;
    }
    $count_etiketten++;
    $pdf->Rect($x, $y, $breite_etikett, $hoehe_etikett);

    //LOGO IMG

    if ($is_kuechenstrolche) {
        // $pdf->Image('https://www.s-bar.net/verwaltung_dev/images/kuestro_logo_sw.png', $x + 2, $y + 3, $w_logo, 8.1, 'PNG');

    } else {
        //$pdf->Image('https://www.s-bar.net/verwaltung_dev/images/kuestro_logo_sw.png', $x + 2, $y + 3, $w_logo, 8.1, 'PNG');

    }
    //EINRICHTUNGSNAME
    $pdf->setXY($x + $padding_x_etikett, $y + $y_einrichtungsname);
    $pdf->SetFont('Arial', 'B', $font_size_einr);
    $pdf->MultiCell($breite_etikett - 2 * $padding_x_etikett, 6, utf8_decode('Gesamt'), 0, 'L');

    //MENGE
    $pdf->setXY($x + $padding_x_etikett, $y + $y_einrichtungsname + 9);
    $pdf->SetFont('Arial', '', 12);
    $pdf->MultiCell($breite_etikett - $breite_etikett / 2, 6, utf8_decode('XT ' . $gesamtmenge_tag_speise), 0, 'L');

    //DATUM
    $pdf->setXY($x + $padding_x_etikett + $w_logo, $y + $padding_y_etikett);
    $pdf->SetFont('Arial', 'B', 11);
    $pdf->MultiCell($breite_etikett, 0, utf8_decode(strftime('%a %d.%m.%y', mktime(12, 0, 0, $monat, $tag, $jahr))), 0, 'L');
    //SPEISE BEZEICHNUNG
    $pdf->setXY($x + $padding_x_etikett + $w_logo, $y + $padding_y_etikett + 5);
    $pdf->SetFont('Arial', 'B', 11);
    $pdf->MultiCell($breite_etikett, 0, utf8_decode($speise_bezeichnung_str), 0, 'L');
    $x = $x + $breite_etikett;
    if ($count_etiketten > 1 && $count_etiketten % 2 === 0) {
        $y = $y + $hoehe_etikett;
        $x = $padding_x;
        $pdf->setXY($x, $y);
    }
    if ($count_etiketten > 0 && $count_etiketten % 12 === 0) {
        $pdf->AddPage();
        $y = $padding_y;
        $x = $padding_x;
        $pdf->setXY($x, $y);
    }

    /*
      $sheet->setCellValue("A$i", 'Gesamt');
      $sheet->setCellValue("B$i", $gesamtmenge_tag_speise);
      $sheet->setCellValue("C$i", '');
      $sheet->setCellValue("D$i", $speise->getBezeichnung() . ' ');
      $sheet->setCellValue("E$i", strftime('%a %d.%m.%y', mktime(12, 0, 0, $monat, $tag, $jahr)));

     */


    $heute = strftime('%d_%m_%Y', time());
    $rechner = php_uname('n');
    $speise_bezeichnung = $speise->getBezeichnung();
    $speise_bezeichnung = umlautepas($speise_bezeichnung);
    $speise_bezeichnung = entferneSonderzeichen($speise_bezeichnung);

    $pdf->Output('Eti_' . $speise_bezeichnung . '_Sp' . $speise_nr . '_' . strftime('%d_%m_%y', mktime(12, 0, 0, $monat, $tag, $jahr)) . '.pdf', 'D');
}

function erzeugeInventaretiketten() {
    require 'fpdf/fpdf.php';
    $pdf = new FPDF();

    $pdf->SetAutoPageBreak(false);
    $pdf->SetTextColor(80, 80, 80);
    $pdf->SetFont('Arial', '', 12);
    $pdf->AddPage();
    $count_etiketten = 0;

    $y = 22;
    $x = 10;
    $w_etikett = 45.7;
    $h_etikett = 21.2;
    //BEZEICHNUNG
    $pdf->setXY($x, $y);
    $pdf->SetFont('Arial', 'B', 10);
    $etik_count = 0;
    for ($r = 12001; $r <= 12240; $r++) {
        //$pdf->SetDrawColor(255,0,0);
        //$pdf->Rect($pdf->getX(), $pdf->getY(), $w_etikett, $h_etikett);
        $pdf->SetDrawColor(0, 0, 255);

        $pdf->Cell($w_etikett, $h_etikett / 3, $r, 0, 0, 'C');
        $pdf->Image('https://www.s-bar.net/verwaltung/functions/barcode.php?text=' . $r, $pdf->getX() - $w_etikett + 0.5, $pdf->getY() + $h_etikett / 2, $w_etikett - 1, $h_etikett / 3, 'PNG');

        $pdf->setX($pdf->getX() + 2);
        if ($r % 4 == 0) {
            $pdf->setY($pdf->getY() + $h_etikett);
        }
        if ($r % 48 == 0 && $r < 12240) {
            $pdf->AddPage();
            $pdf->setXY($x, $y);
        }
    }
    // $pdf->MultiCell(90, 7, utf8_decode($tour_zu_kunde->getName() . ' Sammel 1' . ' [Sp ' . $speise_nr . ']'), 0, 'L');
    //CODE IMG
    //$pdf->Image('https://www.s-bar.net/verwaltung/functions/barcode.php?text=' . $code_print, $x + $padding_x_etikett, $y + 25, $breite_etikett - 2 * $padding_x_etikett, 12, 'PNG');


    $pdf->Output('Inventaretiketten_' . strftime('%d_%m_%y', time()) . '.pdf', 'D');
}

function erzeugeEinrichtungslisteZuSpeiseNrPdfEtiketten($kundeVerwaltung, $tag, $monat, $jahr, $starttag, $startmonat, $startjahr, $speise_id, $bestellung, $bestellung_has_speiseVerwaltung, $speiseVerwaltung, $menge_pro_portionVerwaltung, $standardportionenVerwaltung, $portionenaenderungVerwaltung, $kunden, $indifaktorVerwaltung, $bemerkungzutagVerwaltung, $bemerkungzuspeiseVerwaltung, $speise_nr) {
    require 'fpdf/fpdf.php';
    $pdf = new FPDF();

    $pdf->SetAutoPageBreak(false);
    $pdf->SetTextColor(80, 80, 80);
    $pdf->SetFont('Arial', '', 12);
    $pdf->AddPage();

    $count_etiketten = 0;

    $y = 21.5;
    $x = 8.48;

    $ts = mktime(12, 0, 0, $monat, $tag, $jahr);
    $kunden_arr = erzeugeKundenArrayNachLieferreihenfolgeUndTourenNachSpeiseNr($kundeVerwaltung, $speise_nr, $standardportionenVerwaltung, $portionenaenderungVerwaltung, $ts, $starttag, $startmonat, $startjahr);

    $kunden = $kunden_arr['kunden'];

    $i = 1;
    // $x = $i - 1;

    $i = 1;
    $bestellung_has_speise_array = $bestellung_has_speiseVerwaltung->findeAnhandVonBestellungId($bestellung->getId());

    $speisen_cooled_ids = array();
    foreach ($bestellung_has_speise_array as $best_has_speise) {
        if ($best_has_speise->getSpeiseNr() !== $speise_nr) {
            continue;
        }

        $speise_id_check_cooled = $best_has_speise->getSpeiseId();
        $speise_check = $speiseVerwaltung->findeAnhandVonId($speise_id_check_cooled);
        if ($speise_check->getCooled()) {
            $speisen_cooled_ids[$speise_id_check_cooled] = $speise_check->getBezeichnung();
        }
    }

    $gesamtmenge_tag_speise = 0;
    $gesamtmenge_tag_portionen = 0;
    $c = $i - 2;
    $d = $i - 1;

    $speise = $speiseVerwaltung->findeAnhandVonId($speise_id);

    $last_tour = new Kunde();
    $tour_zu_kunde = new Kunde();

    foreach ($kunden as $kunde) {

        $speise_id = $speise->getId();
        $indi_faktor = $indifaktorVerwaltung->findeAnhandVonSpeiseIdUndKundeId($speise_id, $kunde->getId());
        $faktor = $indi_faktor->getFaktor();
        $einrichtungskategorie_id = $kunde->getEinrichtungskategorieId();
        $menge_pro_portion = $menge_pro_portionVerwaltung->findeAnhandVonSpeiseIdUndEinrichtungskategorieId($speise_id, $einrichtungskategorie_id);

        //keine Biotouren in Speise 1 und 2
        if (($speise_nr <= 2 && $einrichtungskategorie_id == 5 && ($kunde->getBioKunde() || $kunde->getStaedtischerKunde())) || $kunde->getAktiv() == 0) {
            continue;
        }


        $portionen = $kunden_arr['portionen'][$kunde->getId()][$speise_nr] * 1;

        $gesamtmenge_tag_portionen += $portionen;
        $gesamtmenge_kunde = $portionen * ($menge_pro_portion->getMenge() * $faktor);

        if (!$last_kunde) {
            $last_kunde = new Kunde();
        }
        if ($last_kunde->getEinrichtungskategorieId() == 5 && $kunde->getEinrichtungskategorieId() == 5) {
            $i . - 1;
        }

        $tour_id = $kunde->getTourId();
        if ($kunde->getEinrichtungskategorieId() == 5) {
            $tour_id = $kunde->getId();
        }
        $tour_zu_kunde = $kundeVerwaltung->findeAnhandVonId($tour_id);

        $portionen_zu_tour = $kunden_arr['portionen']['tour_' . $tour_zu_kunde->getId()][$speise_nr];
        if (/* $kunde->getEinrichtungskategorieId() == 5 || */ ($portionen == 0 && $kunde->getEinrichtungskategorieId() != 5) || ($portionen_zu_tour == 0 && $kunde->getEinrichtungskategorieId() == 5)) {

        } else {
            $code = $kunde->getId() . '-' . sprintf('%02d', $tag) . sprintf('%02d', $monat) . sprintf('%02d', $jahr);
            //  $code = $tour_zu_kunde->getId() . '-' . sprintf('%02d', $tag) . sprintf('%02d', $monat) . sprintf('%02d', $jahr) . '-' . $speise_nr . '-' . 'S' . '-1';

            $datum_str = $wochentag_string . ' ' . $tag . '.' . $monat . '.' . $jahr;

            if ($speise_nr == 2 && $kunde->getAnzahlSpeisen() == 1 && $kunde->getEinrichtungskategorieId() != 5) {
                continue;
            }
            if ($speise_nr <= 2 && ($kunde->getBioKunde() || $kunde->getStaedtischerKunde()) && $kunde->getEinrichtungskategorieId() != 5) {
                continue;
            }
            if ($speise_nr > 2 && !$kunde->getBioKunde() && !$kunde->getStaedtischerKunde() && $kunde->getEinrichtungskategorieId() != 5) {
                continue;
            }

            $padding_x = 8.48;
            $padding_y = 21.5;
            $padding_y_etikett = 5;
            $padding_x_etikett = 5;
            $breite_etikett = 97;
            $hoehe_etikett = 42.3;
            if (/* $speise_nr * 1 > 1 && */ $last_tour->getId() != $tour_zu_kunde->getId() && $kunde->getEinrichtungskategorieId() == 5) {
                $pdf->Rect($x, $y, $breite_etikett, $hoehe_etikett);
                $count_etiketten++;
                $code_print = $tour_zu_kunde->getId() . '-' . sprintf('%02d', $tag) . sprintf('%02d', $monat) . sprintf('%02d', $jahr) . '-' . $speise_nr . '-' . 'S' . '-1';

                //DATUM
                $pdf->setXY($x + $padding_x_etikett, $y + $padding_y_etikett);
                $pdf->SetFont('Arial', 'B', 11);
                $pdf->MultiCell(100, 0, utf8_decode($datum_str), 0, 'L');

                //BEZEICHNUNG
                $pdf->setXY($x + $padding_x_etikett, $y + 10);
                $pdf->SetFont('Arial', 'B', 16);
                $pdf->MultiCell(90, 7, utf8_decode($tour_zu_kunde->getName() . ' Sammel 1' . ' [Sp ' . $speise_nr . ']'), 0, 'L');

                //CODE IMG
                $pdf->Image('https://www.s-bar.net/verwaltung/functions/barcode.php?text=' . $code_print, $x + $padding_x_etikett, $y + 25, $breite_etikett - 2 * $padding_x_etikett, 12, 'PNG');

                //CODE STRING
                /*  $pdf->setXY($x + 5, $y + 64);
                  $pdf->SetFont('Arial', '', 10);
                  $pdf->MultiCell(95, 0, $code_print, 0, 'C'); */
                $pdf->setXY($x + 5, $y + 39);
                $pdf->SetFont('Arial', '', 10);
                $pdf->MultiCell($breite_etikett - 2 * $padding_x_etikett, 0, $code_print, 0, 'C');

                $i++;
                $x = $x + $breite_etikett;

                if ($count_etiketten > 1 && $count_etiketten % 2 === 0) {
                    $y = $y + $hoehe_etikett;
                    $x = $padding_x;
                    $pdf->setXY($x, $y);
                }
                if ($count_etiketten > 0 && $count_etiketten % 12 === 0) {
                    $pdf->AddPage();
                    $y = $padding_y;
                    $x = $padding_x;
                    $pdf->setXY($x, $y);
                }
            }

//nach jedem zweiten Element neue Zeile (X Reset und Y + Höhe)
            /*    if ($count_etiketten > 1 && $count_etiketten % 2 === 0) {
              $y = $y + 70;
              $x = 0;
              } else {
              $x = $x + 105;
              } */
            $add = '';
            for ($r = $speise_nr; $r <= $speise_nr; $r++) {
                $add1 = '';
                if ($kunde->getAnzahlSpeisen() > 1 || $kunde->getStaedtischerKunde()) {
                    $add1 .= ' [Sp ' . $r . ']';
                }
                for ($c = 1; $c <= $kunde->getAnzahlBoxen(); $c++) {
                    $add2 = '';
                    if ($kunde->getAnzahlBoxen() > 1) {
                        $add2 .= ' [Box ' . $c . ']';
                    }

                    if ($kunde->getEinrichtungskategorieId() != 5) {
                        $pdf->Rect($x, $y, $breite_etikett, $hoehe_etikett);

                        $code_print_2 = $code . '-' . $r . '-' . $c;

                        //CODE IMG
                        //    $pdf->Image('https://www.s-bar.net/verwaltung_dev/images/logo_sw.png', $x + 10, $y + 5, 15, 10.125, 'PNG');


                        $count_etiketten++;
                        //EINRICHTUNGSNAME
                        $pdf->setXY($x + $padding_x_etikett, $y + 10);
                        $pdf->SetFont('Arial', 'B', 16);
                        $pdf->MultiCell(90, 7, utf8_decode($kunde->getName() . $add1 . $add2), 0, 'L');

                        //DATUM
                        $pdf->setXY($x + $padding_x_etikett, $y + $padding_y_etikett);
                        $pdf->SetFont('Arial', 'B', 11);
                        $pdf->MultiCell(100, 0, utf8_decode($datum_str), 0, 'L');

                        if ($kunde->getEinrichtungskategorieId() != 5) {
                            //CODE IMG
                            $pdf->Image('https://www.s-bar.net/verwaltung/functions/barcode.php?text=' . $code_print_2, $x + $padding_x_etikett, $y + 25, $breite_etikett - 2 * $padding_x_etikett, 12, 'PNG');

                            //CODE STRING
                            $pdf->setXY($x + 5, $y + 39);
                            $pdf->SetFont('Arial', '', 10);
                            $pdf->MultiCell($breite_etikett - 2 * $padding_x_etikett, 0, $code_print_2, 0, 'C');
                        }

                        if ($kunde->getEinrichtungskategorieId() != 5 && $tour_zu_kunde->getId()) {
                            //TOURNAME
                            $pdf->setXY($x + 30, $y + $padding_y_etikett);
                            $pdf->SetFont('Arial', 'B', 12);
                            $pdf->MultiCell(70, 0, utf8_decode($tour_zu_kunde->getName()), 0, 'L');
                        }


                        $info_string = '';
                        if (preg_match('#vegi#i', $kunde->getBemerkung())) {
                            $info_string .= 'V ';
                        }
                        if (preg_match('#diät#i', $kunde->getBemerkung())) {
                            $info_string .= 'D ';
                        }
                        if (preg_match('#wärme#i', $kunde->getBemerkungKunde())) {
                            $info_string .= 'W ';
                        }
                        if (preg_match('#sauber#i', $kunde->getBemerkungKunde())) {
                            $info_string .= 'S ';
                        }
                        if ($info_string) {

                            //INFO
                            $pdf->setXY($x + 82, $y + $padding_y_etikett);
                            $pdf->SetFont('Arial', 'B', 12);
                            $pdf->MultiCell(20, 0, utf8_decode($info_string), 0, 'L');
                        }
                        $i++;

                        $x = $x + $breite_etikett;

                        if ($count_etiketten > 1 && $count_etiketten % 2 === 0) {
                            $y = $y + $hoehe_etikett;
                            $x = $padding_x;
                            $pdf->setXY($x, $y);
                        }

                        if ($count_etiketten > 0 && $count_etiketten % 12 === 0) {
                            $pdf->AddPage();
                            $y = $padding_y;
                            $x = $padding_x;
                            $pdf->setXY($x, $y);
                        }
                    }
                }
            }











            $last_tour = $tour_zu_kunde;

            /* EINBAUEN!!!!! */
        }
        /* echo '<pre>';
          var_dump($info_string);
          echo '</pre>'; */
        $last_kunde = $kunde;

        $gesamtmenge_kunde = $portionen * ($menge_pro_portion->getMenge() * $faktor);
        $gesamtmenge_tag_speise += $gesamtmenge_kunde;
    }



    $ttt = $i + 1;
    if ($menge_pro_portion->getEinheit() == 'g' || $menge_pro_portion->getEinheit() == 'ml') {
        $gesamtmenge_tag_speise_umger = $gesamtmenge_tag_speise / 1000;
        switch ($menge_pro_portion->getEinheit()) {
            case 'g':
                $einheit = 'kg';
                break;
            case 'ml':
                $einheit = 'L';
                break;
        }
    }






    $heute = strftime('%d_%m_%Y', time());
    $rechner = php_uname('n');
    $speise_bezeichnung = $speise->getBezeichnung();
    $speise_bezeichnung = umlautepas($speise_bezeichnung);
    //var_dump($speise_bezeichnung);

    $pdf->Output('Einrichtungen_' . 'Sp' . $speise_nr . '_' . strftime('%d_%m_%y', $ts) . '.pdf', 'D');
    /*
      if ($_SESSION['is_local_server']) {
      $writer->save('export_einrichtungslisten/Einrichtungen_' . 'Sp' . $speise_nr . '_' . strftime('%d_%m_%y', $ts) . ".xls");
      } else {
      $writer->save('/is/htdocs/wp1055935_1F55KTNHIC/www/sbar/verwaltung/export_einrichtungslisten/Einrichtungen_' . 'Sp' . $speise_nr . '_' . strftime('%d_%m_%y', $ts) . ".xls");
      }

      if ($_SESSION['is_local_server']) {
      header('location:export_einrichtungslisten/Einrichtungen_' . 'Sp' . $speise_nr . '_' . strftime('%d_%m_%y', $ts) . ".xls");
      } else {
      header('location:http://www.s-bar.net/verwaltung/export_einrichtungslisten/Einrichtungen_' . 'Sp' . $speise_nr . '_' . strftime('%d_%m_%y', $ts) . ".xls");
      } */
}

function checkeAufFehler($kundeVerwaltung, $standardportionenVerwaltung, $portionenaenderungVerwaltung) {
    //Zeitraum für Prüfung erzeugen
    $tag_heute = date('d');
    $monat_heute = date('m');
    $jahr_heute = date("Y");

    $wochentag = strftime("%w", mktime(0, 0, 0, $monat_heute, $tag_heute, $jahr_heute)) - 1;
    if ($wochentag == -1) {
        $wochentag = 6;
    }
    $first = date("m/d/Y", mktime(0, 0, 0, $monat_heute, $tag_heute - $wochentag, $jahr_heute));

    $first_ts = strtotime($first);
    $last_ts = strtotime('+1 week', $first_ts);
    $last = date('m', $last_ts) . '/' . date('d', $last_ts) . '/' . date('Y', $last_ts);

    $tage_to_check_range = date_range_starttage($first, $last);

    //Ende Zeitraum für Prüfung
    $errors = array();
    foreach ($tage_to_check_range as $datum_str) {
        $datum_arr = explode('/', $datum_str);
        $d = $datum_arr[0];
        $m = $datum_arr[1];
        $y = $datum_arr[2];

        //städt. mit 0 Portionen
        $kunden_stadt = $kundeVerwaltung->findeAlleAktivenStaedtischen();
        foreach ($kunden_stadt as $kunde_stadt) {
            $port_aenderungen = $portionenaenderungVerwaltung->findeAnhandVonKundenIdUndWochenstartdatumSpeiseNummern($kunde_stadt->getId(), $d, $m, $y, '3,4');

            $portionen_mo = $portionen_di = $portionen_mi = $portionen_do = $portionen_fr = array();
            foreach ($port_aenderungen as $port_aend_woche) { //Speise 3 und Speise 4 durchlaufen
                $snr = $port_aend_woche->getSpeiseNr();
                $portionen_mo[$snr] = $port_aend_woche->getPortionenMo();
                $portionen_di[$snr] = $port_aend_woche->getPortionenDi();
                $portionen_mi[$snr] = $port_aend_woche->getPortionenMi();
                $portionen_do[$snr] = $port_aend_woche->getPortionenDo();
                $portionen_fr[$snr] = $port_aend_woche->getPortionenFr();
            }
            if (count($port_aenderungen)) {
                if ($portionen_mo[3] && $portionen_mo[4]) {
                    $datum_key = 'MO ' . date("d/m/Y", mktime(0, 0, 0, $m, $d, $y));
                    $errors[$kunde_stadt->getName()][$datum_key][] = 'Portionen für S3 + S4 angegeben';
                }
                if ($portionen_mo[3] == 0 && $portionen_mo[4] == 0) {
                    $datum_key = 'MO ' . date("d/m/Y", mktime(0, 0, 0, $m, $d, $y));
                    $errors[$kunde_stadt->getName()][$datum_key][] = 'Keine Portionen angegeben';
                }

                if ($portionen_di[3] && $portionen_di[4]) {
                    $datum_key = 'DI ' . date("d/m/Y", mktime(0, 0, 0, $m, $d + 1, $y));
                    $errors[$kunde_stadt->getName()][$datum_key][] = 'Portionen für S3 + S4 angegeben';
                }
                if ($portionen_di[3] == 0 && $portionen_di[4] == 0) {
                    $datum_key = 'DI ' . date("d/m/Y", mktime(0, 0, 0, $m, $d + 1, $y));
                    $errors[$kunde_stadt->getName()][$datum_key][] = 'Keine Portionen angegeben';
                }


                if ($portionen_mi[3] && $portionen_mi[4]) {
                    $datum_key = 'MI ' . date("d/m/Y", mktime(0, 0, 0, $m, $d + 2, $y));
                    $errors[$kunde_stadt->getName()][$datum_key][] = 'Portionen für S3 + S4 angegeben';
                }
                if ($portionen_mi[3] == 0 && $portionen_mi[4] == 0) {
                    $datum_key = 'MI ' . date("d/m/Y", mktime(0, 0, 0, $m, $d + 2, $y));
                    $errors[$kunde_stadt->getName()][$datum_key][] = 'Keine Portionen angegeben';
                }

                if ($portionen_do[3] && $portionen_do[4]) {
                    $datum_key = 'DO ' . date("d/m/Y", mktime(0, 0, 0, $m, $d + 3, $y));
                    $errors[$kunde_stadt->getName()][$datum_key][] = 'Portionen für S3 + S4 angegeben';
                }
                if ($portionen_do[3] == 0 && $portionen_do[4] == 0) {
                    $datum_key = 'DO ' . date("d/m/Y", mktime(0, 0, 0, $m, $d + 3, $y));
                    $errors[$kunde_stadt->getName()][$datum_key][] = 'Keine Portionen angegeben';
                }

                if ($portionen_fr[3] && $portionen_fr[4]) {
                    $datum_key = 'FR ' . date("d/m/Y", mktime(0, 0, 0, $m, $d + 4, $y));
                    $errors[$kunde_stadt->getName()][$datum_key][] = 'Portionen für S3 + S4 angegeben';
                }
                if ($portionen_fr[3] == 0 && $portionen_fr[4] == 0) {
                    $datum_key = 'FR ' . date("d/m/Y", mktime(0, 0, 0, $m, $d + 4, $y));
                    $errors[$kunde_stadt->getName()][$datum_key][] = 'Keine Portionen angegeben';
                }
            }
        }
    }

    /*  echo '<pre>';
      var_dump($errors);
      echo '</pre>'; */
    ksort($errors);
    return $errors;
    //Unterschied Standard Portionen <-> Eingabe
    //kein externer Menüname bei städt. Kunden
}

function date_range_starttage($first, $last, $step = '+1 week', $output_format = 'd/m/Y') {
    $dates = array();
    $current = strtotime($first);
    $last = strtotime($last);
    while ($current <= $last) {
        /* if ($current > mktime(0, 0, 1, date('m'), date('d') - 1, date('Y'))) { //nurfalls nicht älter als heute

          } */
        $dates[] = date($output_format, $current);
        $current = strtotime($step, $current);
    }
    return $dates;
}

function erstelleAbrechnungsDatenNbg($request, $kundeVerwaltung, $abrechnungstagVerwaltung, $menunamenVerwaltung, $rechnungsadresseVerwaltung) {
    $daten_abrechnung = array();
    $kunden_stadt = $kundeVerwaltung->findeAlleStaedtischen();
    $stadtkunden_ids_array = array();
    foreach ($kunden_stadt as $kunde_stadt) {
        $stadtkunden_ids_array[] = $kunde_stadt->getId();
    }
    if (isset($request['monat'])) {
        $monat = $request['monat'];
    } else {
        $monat = date('m');
    }
    if (isset($request['jahr'])) {
        $jahr = $request['jahr'];
    } else {
        $jahr = date('Y');
    }
    $abrechnungstage = $abrechnungstagVerwaltung->findeAlleFuerAbrechnung($monat, $jahr, $stadtkunden_ids_array);

    $daten_abrechnung['leistungsmonat'] = $monat . '/' . $jahr;
    $monat_gesamt_portionen = 0;
    foreach ($abrechnungstage as $abrechnungstag) {
        $speise_nr = $abrechnungstag->getSpeiseNr();
        if ($speise_nr < 3) {
            continue;
        }
        $menuname = $menunamenVerwaltung->findeAnhandVonTagMonatJahrSpeiseNr($abrechnungstag->getTag2(), $monat, $jahr, $speise_nr);
        $kunde_id = $abrechnungstag->getKundeId();

        $kunde_check = $kundeVerwaltung->findeAnhandVonId($kunde_id);
        if ($kunde_check->getEinrichtungskategorieId() == 5 || $kunde_check->getEinrichtungskategorieId() == 6) {
            continue;
        }

        $tag_key = $jahr . '-' . $monat . '-' . sprintf('%02d', $abrechnungstag->getTag2());
        $daten_abrechnung[$kunde_id]['tagesaufstellung'][$tag_key][$speise_nr]['portionen'] = $abrechnungstag->getPortionen();
        $monat_gesamt_portionen = $abrechnungstag->getPortionen();
        $daten_abrechnung[$kunde_id]['monat_gesamt_portionen'] += $monat_gesamt_portionen;
        $daten_abrechnung[$kunde_id]['tagesaufstellung'][$tag_key][$speise_nr]['speisen_ids'] = array_map('trim', explode(',', $abrechnungstag->getSpeisenIds()));
        //lieferdaten
        $daten_abrechnung[$kunde_id]['tagesaufstellung'][$tag_key][$speise_nr]['lieferschein'] = $abrechnungstag->getLieferschein();
        $daten_abrechnung[$kunde_id]['tagesaufstellung'][$tag_key][$speise_nr]['lieferdaten'] = array();
        $daten_abrechnung[$kunde_id]['tagesaufstellung'][$tag_key][$speise_nr]['menuname'] = $menuname->getBezeichnung();
    }
    foreach ($daten_abrechnung as $kunde_id => $data) {
        if ($kunde_id == 'leistungsmonat') {
            continue;
        }
        $kunde = $kundeVerwaltung->findeAnhandVonId($kunde_id);
        $rechnungs_adresse = $rechnungsadresseVerwaltung->findeAnhandVonKundeId($kunde->getId());
        $re_plz = $rechnungs_adresse->getPlz();
        if ($re_plz == 0) {
            $re_plz = '';
        }
        $daten_abrechnung[$kunde_id]['kunden_daten'] = array(
            'name' => $kunde->getName(),
            'strasse' => $kunde->getStrasse(),
            'plz' => $kunde->getPlz(),
            'ort' => $kunde->getOrt(),
            'einrichtungsart' => $kunde->getEinrichtungsart(),
            'preis' => $kunde->getPreis(),
            're_name' => $rechnungs_adresse->getFirma(),
            're_strasse' => $rechnungs_adresse->getStrasse(),
            're_plz' => $re_plz,
            're_ort' => $rechnungs_adresse->getOrt()
        );
    }
    return $daten_abrechnung;
}

function erzeugeLieferscheine($request, $abrechnungs_daten, $kundeVerwaltung, $speiseVerwaltung, $menge_pro_portionVerwaltung, $indifaktorVerwaltung, $qrcode = false) {

    $kunden_stadt = $kundeVerwaltung->findeAlleAktivenStaedtischen();
    $tag_show = $request['tag'];
    $monat_show = $request['monat'];
    $jahr_show = $request['jahr'];
    $tag_key = $jahr_show . '-' . $monat_show . '-' . $tag_show;
    require 'fpdf/fpdf.php';
    $pdf = new FPDF();
    $pdf->SetAutoPageBreak(false);
    $scheine_gesamt = 0;
    $kunden_stadt_doubled = array();

    foreach ($kunden_stadt as $kunde) {
        if ($kunde->getEinrichtungskategorieId() == 6) {
            continue;
        }
        for ($speisenr = 3; $speisenr <= 4; $speisenr++) {
            $portionen = $abrechnungs_daten[$kunde->getId()]['tagesaufstellung'][$tag_key][$speisenr]['portionen'];
            $speise_ids = $abrechnungs_daten[$kunde->getId()]['tagesaufstellung'][$tag_key][$speisenr]['speisen_ids'];

            if ($speise_ids !== NULL) {
                $speise_ids = array_filter($speise_ids);
            }

            $add_gesamt = false;
            if ($portionen > 0 && count($speise_ids) != 0) {
                $add_gesamt = true;
            }
            if ($add_gesamt) {
                $scheine_gesamt++;
            }
        }
    }


    //DECKBLATT

    $pdf->AddPage();
    $trans_dates = array(
        'Monday' => 'Montag',
        'Tuesday' => 'Dienstag',
        'Wednesday' => 'Mittwoch',
        'Thursday' => 'Donnerstag',
        'Friday' => 'Freitag',
        'Saturday' => 'Samstag',
        'Sunday' => 'Sonntag',
        'Mon' => 'Mo',
        'Tue' => 'Di',
        'Wed' => 'Mi',
        'Thu' => 'Do',
        'Fri' => 'Fr',
        'Sat' => 'Sa',
        'Sun' => 'So',
        'January' => 'Januar',
        'February' => 'Februar',
        'March' => 'März',
        'May' => 'Mai',
        'June' => 'Juni',
        'July' => 'Juli',
        'October' => 'Oktober',
        'December' => 'Dezember'
    );
    $count_schein2 = 1;

    $pdf->SetMargins(24, 50);
    $pdf->setX(24);
    $pdf->setY($pdf->getY() + 20);
    $tag_ger = $trans_dates[date('D', mktime(12, 0, 0, $monat_show, $tag_show, $jahr_show))];
    $pdf->SetFont('Arial', 'B', 14);
    $pdf->Cell($w_spalte1, 6, $tag_ger . ' ' . $tag_show . '.' . $monat_show . '.' . $jahr_show, 0, 0, 'C');

    $pdf->setY($pdf->getY() + 10);
    $w_spalte1 = 97;
    $w_spalte2 = 35;
    $w_spalte3 = 33;
    $pdf->SetFont('Arial', 'B', 10);
    $pdf->Cell($w_spalte1, 6, 'Kunde', 1, 0, 'C');
    $pdf->Cell($w_spalte3, 6, 'Tour', 1, 0, 'C');
    $pdf->Cell($w_spalte2, 6, 'Lieferschein Seite', 1, 0, 'C');

    foreach ($kunden_stadt as $kunde) {

        if ($kunde->getEinrichtungskategorieId() == 6) {
            continue;
        }
        $kunden_daten = $abrechnungs_daten[$kunde->getId()]['kunden_daten'];
        $pdf->SetFont('Arial', '', 12);

        $tour_zu_kunde = $kundeVerwaltung->findeTourZuKundenReihenfolge($kunde->getLieferreihenfolge());
        $tourname = '';
        if (count($tour_zu_kunde)) {
            $tourname = $tour_zu_kunde[0]->getName();
        }

        for ($speisenr = 3; $speisenr <= 4; $speisenr++) {
            $portionen = $abrechnungs_daten[$kunde->getId()]['tagesaufstellung'][$tag_key][$speisenr]['portionen'];
            $lieferschein = $abrechnungs_daten[$kunde->getId()]['tagesaufstellung'][$tag_key][$speisenr]['lieferschein'];
            if ($portionen == 0) {
                continue;
            }
            $speise_ids = $abrechnungs_daten[$kunde->getId()]['tagesaufstellung'][$tag_key][$speisenr]['speisen_ids'];

            $speise_ids = array_filter($speise_ids);

            if (count($speise_ids) == 0) {
                continue;
            }

            $lieferschein_checknummer = $tag_show . $monat_show . substr($jahr_show, -2) . '/' . $count_schein2 . '/' . $scheine_gesamt;
            $count_schein2++;
            $pdf->setY($pdf->getY() + 6);
            $pdf->Multicell($w_spalte1, 6, utf8_decode($kunden_daten['name']), 1, 'L', 0);
            $pdf->setY($pdf->getY() - 6);
            $pdf->setX($w_spalte1 + 24);
            $pdf->Cell($w_spalte3, 6, utf8_decode($tourname), 1, 0, 'C');
            $pdf->Cell($w_spalte2, 6, $lieferschein_checknummer, 1, 0, 'C');
        }




        /* echo '<pre>';
          var_dump($abrechnungs_daten[$kunde->getId()]['tagesaufstellung'][$tag_key]);
          echo '</pre>'; */
    }
    //DECKBLATT ENDE




    $ausfuehrung_fuer_array = array(
        1 => 'S-Bar',
        2 => 'Einrichtung'
    );
    $last_kid = 0;
    $count_schein = 0;
    foreach ($kunden_stadt as $kunde) {

        $kunden_daten = $abrechnungs_daten[$kunde->getId()]['kunden_daten'];
        $last_kid = $kunde->getId();

        if ($kunde->getEinrichtungskategorieId() == 6) {
            continue;
        }
        $tour_zu_kunde = $kundeVerwaltung->findeTourZuKundenReihenfolge($kunde->getLieferreihenfolge());
        $tourname = '';
        if (count($tour_zu_kunde)) {
            $tourname = $tour_zu_kunde[0]->getName();
        }

        for ($speisenr = 3; $speisenr <= 4; $speisenr++) {

            for ($r = 1; $r <= 2; $r++) {
                $ausfuehrung_fuer = $ausfuehrung_fuer_array[$r];
                $portionen = $abrechnungs_daten[$kunde->getId()]['tagesaufstellung'][$tag_key][$speisenr]['portionen'];
                $lieferschein = $abrechnungs_daten[$kunde->getId()]['tagesaufstellung'][$tag_key][$speisenr]['lieferschein'];

                if ($portionen == 0) {
                    continue;
                }

                $speise_ids = $abrechnungs_daten[$kunde->getId()]['tagesaufstellung'][$tag_key][$speisenr]['speisen_ids'];

                $speise_ids = array_filter($speise_ids);

                if (count($speise_ids) == 0) {
                    continue;
                }

                if ($r == 1) {
                    $count_schein++;
                }
                $kunden_daten = $abrechnungs_daten[$kunde->getId()]['kunden_daten'];
                $menuname = $abrechnungs_daten[$kunde->getId()]['tagesaufstellung'][$tag_key][$speisenr]['menuname'];
                $lieferschein_checknummer = $tag_show . $monat_show . substr($jahr_show, -2) . '/' . $count_schein . '/' . $scheine_gesamt;

                $lieferanschrift = '';
                $lieferanschrift .= $kunden_daten['name'];
                $lieferanschrift .= "\n" . $kunden_daten['strasse'];
                $lieferanschrift .= "\n" . $kunden_daten['plz'] . ' ' . $kunden_daten['ort'];
                $lieferdatum = $tag_show . '.' . $monat_show . '.' . $jahr_show;
                //   $pdf->SetTextColor(80, 80, 80);
                $pdf->SetFont('Arial', '', 10);
                $pdf->SetMargins(24, 50);

                //Spaltenbreiten
                $w_spalte1 = 115;
                $w_spalte2 = 25;
                $w_spalte3 = 25;
                //Deckblatt
                $pdf->AddPage();
                $pdf->Image('images/Briefpapier_S-BAR_2017_sw.jpg', 0, 0, 210);
                $pdf->SetFont('Arial', '', 10);
                $pdf->setY(47);

                $pdf->SetFont('Arial', 'B', 10);
                $pdf->setY(55);

                $pdf->MultiCell(80, 5, utf8_decode($lieferanschrift));
                $pdf->setY(80);
                $pdf->setX(143);
                $pdf->Cell(80, 10, utf8_decode('Nürnberg, den ' . $tag_show . '.' . $monat_show . '.' . $jahr_show), 'R');
                $pdf->setY(90);

                $pdf->setX(143);
                $pdf->SetFont('Arial', 'B', 20);
                $pdf->SetTextColor(180);
                $pdf->Cell(80, 10, utf8_decode('Für ' . $ausfuehrung_fuer), 'R');
                $pdf->setY($pdf->getY() + 10);

                $pdf->SetTextColor(0, 0, 0);
                $pdf->SetFont('Arial', 'B', 12);
                $pdf->Cell(40, 10, utf8_decode('Empfänger: ' . $kunden_daten['name']));
                $pdf->setY($pdf->getY() + 5);
                $pdf->Cell(40, 10, utf8_decode('Lieferschein: ' . $lieferschein));
                $pdf->setY($pdf->getY() + 5);
                $pdf->Cell(40, 10, utf8_decode('Lieferdatum: ' . $lieferdatum));
                $pdf->setY($pdf->getY() + 10);

                $pdf->SetFont('Arial', 'B', 10);
                $pdf->Cell(140, 5, 'Speise', 1, 0, 'C');
                $pdf->Cell(25, 5, 'Anzahl', 1, 0, 'C');

                $pdf->SetFont('Arial', '', 10);
                $pdf->setY($pdf->getY() + 5);
                $pdf->Cell(140, 5, utf8_decode($menuname), 1, 0, 'L');
                $pdf->Cell(25, 5, $portionen, 1, 0, 'C');
                $pdf->setY($pdf->getY() + 10);

                $pdf->SetFont('Arial', 'B', 10);
                $pdf->Cell($w_spalte1, 5, 'Komponente', 1, 0, 'C');
                $pdf->Cell($w_spalte3, 5, 'Menge/Port.', 1, 0, 'C');
                $pdf->Cell($w_spalte2, 5, 'Chargen Nr.', 1, 0, 'C');

                $pdf->SetFont('Arial', '', 10);
                foreach ($speise_ids as $speise_id) {
                    $chargennummer = substr($jahr_show, -2) . $monat_show . $tag_show . '-' . $speise_id . '-' . $speisenr;
                    $speise = $speiseVerwaltung->findeAnhandVonId($speise_id);
                    if ($speise->getNonprint()) {
                        continue;
                    }
                    $menge_pro_portion = $menge_pro_portionVerwaltung->findeAnhandVonSpeiseIdUndEinrichtungskategorieId($speise_id, $kunde->getEinrichtungskategorieId());
                    $menge_gesamt = $menge_pro_portion->getMenge() * $portionen;

                    $indi_faktor = $indifaktorVerwaltung->findeAnhandVonSpeiseIdUndKundeId($speise_id, $kunde->getId());
                    $faktor = $indi_faktor->getFaktor();
                    $menge_pro_portion_umger = $menge_pro_portion->getMenge();
                    $einheit = $menge_pro_portion->getEinheit();
                    if ($menge_pro_portion->getEinheit() == 'g' || $menge_pro_portion->getEinheit() == 'ml') {
                        $menge_pro_portion_umger = ($menge_pro_portion->getMenge() * $faktor);
                        $menge_pro_portion_umger = number_format($menge_pro_portion_umger, 0, ',', '.');
                        /*
                          //umrechnen in kg oder L
                          $menge_pro_portion_umger = ($menge_pro_portion->getMenge() * $faktor) / 1000;
                          switch ($menge_pro_portion->getEinheit()) {
                          case 'g':
                          $einheit = 'kg';
                          break;
                          case 'ml':
                          $einheit = 'L';
                          break;
                          }
                         */
                    }

                    if ($menge_pro_portion->getEinheit() == 'Stück' || $menge_pro_portion->getEinheit() == 'Blech') {
                        $menge_pro_portion_umger = number_format($menge_pro_portion_umger, 2);
                    }

                    $speise_bezeichnung = $speise->getBezeichnung();
                    if (substr($speise_bezeichnung, 0, 1) == '*') {
                        $speise_bezeichnung = str_replace('*', '', $speise_bezeichnung);
                    }

                    $pdf->setY($pdf->getY() + 5);
                    $pdf->Multicell($w_spalte1, 5, utf8_decode($speise_bezeichnung), 1, 'L', 0);
                    $pdf->setY($pdf->getY() - 5);
                    $pdf->setX($w_spalte1 + 24);
                    $pdf->Cell($w_spalte3, 5, str_replace('.', ',', /* number_format($menge_pro_portion_umger, 2) */ $menge_pro_portion_umger) . ' ' . utf8_decode($einheit), 1, 0, 'C');
                    $pdf->Cell($w_spalte2, 5, $chargennummer, 1, 0, 'C');
                    /*  $pdf->Cell($w_spalte2, 5, number_format($menge_pro_portion->getMenge(), 2) . ' ' . utf8_decode($menge_pro_portion->getEinheit()), 1, 0, 'C');
                      $pdf->Cell($w_spalte3, 5, $portionen, 1, 0, 'C'); */
                    //$pdf->Cell($w_spalte3, 5, $menge_gesamt . ' ' . utf8_decode($menge_pro_portion->getEinheit()), 1, 0, 'C');
                }

                $pdf->SetFont('Arial', '', 12);
                $pdf->setY($pdf->getY() + 10);
                $w1 = 25;
                $w2 = 25;
                $line_h = 7;
                /*
                  $pdf->Cell($w1, 5, 'geliefert am', 0, 0, 'L');
                  $pdf->Cell($w2, 5, $lieferdatum, 1, 0, 'C');
                  $pdf->Cell(8, 5, 'um', 0, 0, 'L');
                  $pdf->Cell($w2, 5, '     :      ', 1, 0, 'C');
                  $pdf->Cell(8, 5, 'Uhr', 0, 0, 'L'); */

                $pdf->setX(24);

                $pdf->Cell($w1, 5, 'Liefertour: ', 0, 0, 'L');
                $pdf->Cell($w1, 5, utf8_decode($tourname), 0, 0, 'L');
                $pdf->setY($pdf->getY() + 10);

                $pdf->Cell(5, 5, '', 1, 0, 'C');
                $pdf->Cell(50, 5, 'Lieferung rechtzeitig', 0, 0, 'L');

                $pdf->setY($pdf->getY() + 10);

                /*  $pdf->SetFont('Arial', '', 10);
                  $pdf->Cell(25, 5, 'Bemerkungen:', 0, 0, 'L');
                  $pdf->Cell(140, 5, '', 'B', 0, 'L');
                  $pdf->setY($pdf->getY() + 8);
                  $pdf->Cell(165, 5, '', 'B', 0, 'L'); */
                /* $pdf->setY($pdf->getY() + 8);
                  $pdf->Cell(165, 5, '', 'B', 0, 'L'); */

                $pdf->SetFont('Arial', '', 10);
                $pdf->setY($pdf->getY() + 12);

                $pdf->SetFont('Arial', '', 12);
                $pdf->setY(220);
                $pdf->Cell(25, 5, 'Unterschrift: ', 0, 0, 'L');
                $pdf->Cell(80, 5, '', 'B', 0, 'L');

                $pdf->setY($pdf->getY() + 10);
                $pdf->Cell(25, 5, 'Name: ', 0, 0, 'L');
                $pdf->Cell(80, 5, '', 'B', 0, 'L');

                $pdf->SetFont('Arial', '', 8);
                $pdf->setY($pdf->getY() + 5);
                $pdf->Cell(25, 5, '(Unterzeichner in Druckbuchstaben)', 0, 0, 'L');

                $pdf->setY($pdf->getY() + 10);
                $pdf->SetFont('Arial', '', 10);
                $pdf->Cell(25, 5, 'Stempel ', 0, 0, 'L');
                $pdf->Rect(24, $pdf->getY() - 1, 105, 30);

                $pdf->setY(283);
                $pdf->SetFont('Arial', 'B', 12);
                $pdf->Cell(80, 10, utf8_decode($tourname), 0, 0, 'L');
                $pdf->setX(173);
                $pdf->SetFont('Arial', 'B', 14);
                $pdf->Cell(80, 10, utf8_decode($lieferschein_checknummer), 'R');
                $pdf->setY($pdf->getY() + 10);

                //QR Code
                if ($qrcode) {
                    $qr_data = $kunde->getId() . '-' . $tag_show . $monat_show . $jahr_show . '-' . $portionen . '-' . $lieferschein;

                    $pdf->Image("https://www.s-bar.net/verwaltung_dev/qr_generator.php?code=" . $qr_data, 160, 230, 30, 30, "png");
                }

                //$pdf->setX($pdf->getX() + 3);



                /*  echo '<pre>';
                  var_dump($kunden_daten);
                  echo '</pre>'; */
            }
        }
    }
    $dateiname = 'Lieferscheine_NBG_' . $tag_show . '_' . $monat_show . '_' . $jahr_show;
    $pdf->Output($dateiname . '.pdf', 'D');
}

function preisAusgeben($preis) {
    return number_format($preis, 2, ',', '.');
}

function pdfDeckblattNbg($abrechnungs_daten) {

}

function erzeugeAbrechnungStadt($abrechnungs_daten) {
    /* $adresse_stadt_nbg = array(
      'name' => 'Stadt Nürnberg',
      'name2' => 'Amt für Kinder, Jugendliche und Familien',
      'name3' => '- Jugendamt - Abteilung J/B4-3 ',
      'strasse' => 'Dietzstraße 4',
      'plz_ort' => '90443 Nürnberg'
      ); */
    $adresse_stadt_nbg = array(
        'name' => 'Stadt Nürnberg',
        'name2' => 'Jugendamt',
        'name3' => 'Abteilung Finanzen, Mittelfristiger Investitionsplan und Gebäudemanagement',
        'strasse' => 'Postfach 90 01 48',
        'plz_ort' => '90492 Nürnberg'
    );
    $adresse_stadt_nbg_string = '';
    foreach ($adresse_stadt_nbg as $adrzeile) {
        $adresse_stadt_nbg_string .= "\n" . $adrzeile;
    }
    $absender_zeile = 'S-Bar, Allersberger Str. 185/B2, 90461 Nürnberg';

    /* echo '<pre>';
      var_dump($abrechnungs_daten);
      echo '</pre>'; */

    $monat_str = $abrechnungs_daten['leistungsmonat'];
    $monat_str_arr = explode('/', $monat_str);
    $monat = $monat_str_arr[0];
    $jahr = $monat_str_arr[1];

    $mwst_satz = 1.07;
    $mwst_satz_str = 7;
    if ($jahr == 2020 && ($monat >= 7 && $monat <= 12 )) {
        $mwst_satz = 1.05;
        $mwst_satz_str = 5;
    }

    $trans_dates = array(
        'Monday' => 'Montag',
        'Tuesday' => 'Dienstag',
        'Wednesday' => 'Mittwoch',
        'Thursday' => 'Donnerstag',
        'Friday' => 'Freitag',
        'Saturday' => 'Samstag',
        'Sunday' => 'Sonntag',
        'Mon' => 'Mo',
        'Tue' => 'Di',
        'Wed' => 'Mi',
        'Thu' => 'Do',
        'Fri' => 'Fr',
        'Sat' => 'Sa',
        'Sun' => 'So',
        'January' => 'Januar',
        'February' => 'Februar',
        'March' => 'März',
        'May' => 'Mai',
        'June' => 'Juni',
        'July' => 'Juli',
        'October' => 'Oktober',
        'December' => 'Dezember'
    );
    require 'fpdf/fpdf.php';
    $pdf = new FPDF();
    $add_name = str_replace('/', '_', $monat_str);
    define('EURO', chr(128));
    $pdf->SetAutoPageBreak(false);
    $pdf->SetTextColor(80, 80, 80);
    $pdf->SetFont('Arial', '', 12);

    //Spaltenbreiten
    $w_spalte1 = 85;
    $w_spalte2 = 27;
    $w_spalte3 = 15;
    $w_spalte4 = 15;
    $w_spalte5 = 23;
    //Deckblatt
    $pdf->AddPage();
    $pdf->Image('images/Briefpapier_S-BAR_2017_s2.jpg', 0, 0, 210);
    $pdf->SetFont('Arial', '', 10);
    $pdf->SetMargins(28, 50);
    $pdf->setY(47);
    /* $pdf->SetFont('Arial', 'B', 6);
      $pdf->Cell(80, 10, utf8_decode($absender_zeile), 0, 0, 'L'); */
    $pdf->SetFont('Arial', 'B', 10);
    $pdf->setY(50);
    $pdf->setX(24);
    $pdf->MultiCell(80, 5, utf8_decode($adresse_stadt_nbg_string), 0, 'L', 0);
    $pdf->setY(80);
    $pdf->setX(143);
    $pdf->Cell(80, 10, utf8_decode('Nürnberg, den ' . date('d') . '.' . date('m') . '.' . date('Y')), 'R');

    $pdf->setY(85);
    $pdf->SetFont('Arial', 'B', 10);
    $pdf->Cell(40, 10, utf8_decode('Übersicht der Monatsabrechnung für ' . $monat_str));
    $pdf->setY($pdf->getY() + 10);
    $pdf->Cell($w_spalte1, 5, 'Posten', 1, 0, 'C');
    $pdf->Cell($w_spalte2, 5, 'RE-Nr.', 1, 0, 'C');
    $pdf->Cell($w_spalte3, 5, 'Preis', 1, 0, 'C');
    $pdf->Cell($w_spalte4, 5, 'Anzahl', 1, 0, 'C');
    $pdf->Cell($w_spalte5, 5, 'Betrag', 1, 0, 'C');
    $pdf->setY($pdf->getY() + 5);

    $gesamt_betrag_netto = 0;
    $gesamt_portionen = 0;
    foreach ($abrechnungs_daten as $kunden_id => $daten) {

        if (!is_numeric($kunden_id)) {
            continue;
        }
        $kunden_daten = $daten['kunden_daten'];
        $re_nummer = 'N-' . str_replace('/', '', $monat_str) . '-' . $kunden_id;
        /*


          echo '<pre>';
          var_dump($kunden_id,$kunden_daten);
          echo '</pre>';
         */
        /*   echo '<pre>';
          var_dump($kunden_id,$daten);
          echo '</pre>'; */
        $portionen_gesamt_monat = $abrechnungs_daten[$kunden_id]['monat_gesamt_portionen'];

        if ($portionen_gesamt_monat == 0) {
            continue;
        }


        $gesamt_portionen += $portionen_gesamt_monat;
        $preis_pro_essen = $kunden_daten['preis'];
        $gesamt_kosten_monat = $portionen_gesamt_monat * $preis_pro_essen;

        $gesamt_kosten_monat_brutto = $gesamt_kosten_monat * $mwst_satz;

        $gesamt_betrag_netto += $gesamt_kosten_monat;

        $pdf->SetFont('Arial', '', 10);
        $pdf->Multicell($w_spalte1, 5, utf8_decode($kunden_daten['name']) . ' (' . utf8_decode($kunden_daten['einrichtungsart']) . ')', 1, 'L', 0);
        $pdf->setY($pdf->getY() - 5);
        $pdf->setX($w_spalte1 + 28);

        $pdf->Cell($w_spalte2, 5, $re_nummer, 1, 0, 'C');
        $pdf->Cell($w_spalte3, 5, EURO . ' ' . preisAusgeben($kunden_daten['preis']), 1, 0, 'C');
        $pdf->Cell($w_spalte4, 5, $portionen_gesamt_monat, 1, 0, 'C');
        $pdf->Cell($w_spalte5, 5, EURO . ' ' . preisAusgeben($gesamt_kosten_monat_brutto), 1, 0, 'C');
        $pdf->setY($pdf->getY() + 5);
    }
    $pdf->SetFont('Arial', 'B', 10);
    $w_colspan = $w_spalte1 + $w_spalte2 + $w_spalte3 + $w_spalte4;

    /* $pdf->Cell($w_spalte5, 5, EURO . ' ' . preisAusgeben($gesamt_betrag_netto), 1, 0, 'C');
      $pdf->setY($pdf->getY() + 5); */

    $betrag_mwst = ($gesamt_betrag_netto * $mwst_satz) - $gesamt_betrag_netto;
    $gesamt_betrag_brutto = $gesamt_betrag_netto * $mwst_satz;

    /* $pdf->SetFont('Arial', '', 10);
      $pdf->Cell($w_colspan, 5, 'zzgl. 7% MwSt.', 1);
      $pdf->Cell($w_spalte5, 5, EURO . ' ' . preisAusgeben(round($betrag_mwst)), 1, 0, 'C');
      $pdf->setY($pdf->getY() + 5); */


    $pdf->SetFont('Arial', 'B', 10);
    $pdf->Cell($w_colspan - $w_spalte4, 5, 'Brutto Gesamt', 1);

    $pdf->Cell($w_spalte4, 5, $gesamt_portionen, 1, 0, 'C');

    $pdf->Cell($w_spalte5, 5, EURO . ' ' . preisAusgeben($gesamt_betrag_brutto), 1, 0, 'C');
    $pdf->setY($pdf->getY() + 10);

    //Deckblatt Ende
    //
    //
    //Tagesaufstellung
    foreach ($abrechnungs_daten as $kunden_id => $daten) {

        if (!is_numeric($kunden_id)) {
            continue;
        }
        $portionen_gesamt_monat = $abrechnungs_daten[$kunden_id]['monat_gesamt_portionen'];
        $kunden_daten = $daten['kunden_daten'];
        $anschrift = '';
        $anschrift .= $kunden_daten['re_name'];
        $anschrift .= "\n" . $kunden_daten['re_strasse'];
        $anschrift .= "\n" . $kunden_daten['re_plz'] . ' ' . $kunden_daten['re_ort'];
        $anschrift = utf8_decode($anschrift);
        $tagesaufstellung = $daten['tagesaufstellung'];

        if ($portionen_gesamt_monat == 0) {
            continue;
        }

        $pdf->AddPage();
        $pdf->Image('images/Briefpapier_S-BAR_2017_s2.jpg', 0, 0, 210);

        $pdf->SetFont('Arial', '', 10);
        $pdf->SetMargins(24, 50);

        $re_nummer = 'N-' . str_replace('/', '', $monat_str) . '-' . $kunden_id;

        /* $pdf->setY(47);
          $pdf->SetFont('Arial', 'B', 6);
          $pdf->Cell(80, 10, utf8_decode($absender_zeile), 0, 0, 'L'); */

        $pdf->SetFont('Arial', 'B', 10);
        $pdf->setY(50);
        $pdf->setX(24);
        $pdf->MultiCell(80, 5, utf8_decode($adresse_stadt_nbg_string));
        $pdf->setY(80);
        $pdf->setX(143);
        $pdf->Cell(80, 10, utf8_decode('Nürnberg, den ' . date('d') . '.' . date('m') . '.' . date('Y')), 'R');

        $pdf->setY($pdf->getY() + 5);
        $pdf->setX(143);
        $pdf->Cell(80, 10, utf8_decode('RE-Nr.: ' . $re_nummer), 'R');

        $pdf->setY(100);
        $pdf->SetFont('Arial', 'B', 12);
        $pdf->Cell(40, 10, utf8_decode('Monatsabrechnung ' . $monat_str . ' - ' . $kunden_daten['name'] . ' - ' . $kunden_daten['einrichtungsart']));
        $pdf->setY($pdf->getY() + 10);

        $pdf->SetFont('Arial', '', 10);
        $pdf->Cell(80, 5, 'Lieferadresse:');
        $pdf->SetFont('Arial', 'B', 10);
        $pdf->setY($pdf->getY() + 5);
        $pdf->MultiCell(80, 5, $anschrift);
        $pdf->setY($pdf->getY() + 5);

        $pdf->SetFont('Arial', '', 10);

        //Spaltenbreiten
        $w_spalte1 = 40;
        $w_spalte2 = 45;
        //$w_spalte3 = 20;
        $w_spalte4 = 30;
        $w_spalte5 = 25;
        $w_spalte6 = 25;

        $pdf->Cell($w_spalte1, 5, 'Tag', 1, 0, 'C');
        $pdf->Cell($w_spalte2, 5, 'Lieferschein', 1, 0, 'C');
        // $pdf->Cell($w_spalte3, 5, utf8_decode('Menüoption'), 1, 0, 'C');
        $pdf->Cell($w_spalte5, 5, 'Einzelpreis', 1, 0, 'C');
        $pdf->Cell($w_spalte4, 5, 'Anzahl', 1, 0, 'C');
        $pdf->Cell($w_spalte6, 5, 'Betrag', 1, 0, 'C');
        $pdf->setY($pdf->getY() + 5);

        // $pdf->SetFont('Arial', '', 8);
        $gesamt = 0;
        $gesamt_betrag_netto_monat = 0;
        ksort($tagesaufstellung);
        $portionen_monat = 0;
        foreach ($tagesaufstellung as $tag_string => $posten_tag) {
            $datum_arr = explode('-', $tag_string);
            $tag_string_kurz = $trans_dates[date('D', mktime(12, 0, 0, $datum_arr[1], $datum_arr[2], $datum_arr[0]))];
            $datum_string = sprintf('%02d', $datum_arr[2]) . '.' . $datum_arr[1] . '.' . $datum_arr[0] . ' ' . $tag_string_kurz;
            $pdf->setX(24);
            $gesamt_betrag_netto_tag = 0;
            foreach ($posten_tag as $speise_nr => $daten_tag) {
                switch ($speise_nr) {
                    case 3:
                        $menuoption = 1;
                        break;
                    case 4:
                        $menuoption = 2;
                        break;
                }

                $daten_tag['speisen_ids'] = array_filter($daten_tag['speisen_ids']);
                if ($daten_tag['portionen'] == 0) {
                    continue;
                }
                $speisen_ids_arr = $daten_tag['speisen_ids'];
                $lieferschein = $daten_tag['lieferschein'];
                $portionen = $daten_tag['portionen'];
                $einzelpreis = $kunden_daten['preis'];
                $gesamt_betrag_netto_tag = $portionen * $einzelpreis;
                $gesamt_betrag_netto_monat += $gesamt_betrag_netto_tag;

                /*   echo '<pre>';
                  var_dump($kunden_id, $speise_nr,$daten_tag);
                  echo '</pre>'; */
                $pdf->SetFont('Arial', '', 10);
                $pdf->Multicell($w_spalte1, 5, $datum_string, 1, 'L', 0);
                $pdf->setY($pdf->getY() - 5);
                $pdf->setX($w_spalte1 + 24);

                $pdf->Cell($w_spalte2, 5, $lieferschein, 1, 0, 'C');
                // $pdf->Cell($w_spalte3, 5, $menuoption, 1, 0, 'C');
                $pdf->Cell($w_spalte5, 5, EURO . ' ' . preisAusgeben($einzelpreis), 1, 0, 'C');
                $pdf->Cell($w_spalte4, 5, $portionen, 1, 0, 'C');
                $pdf->Cell($w_spalte6, 5, EURO . ' ' . preisAusgeben($gesamt_betrag_netto_tag), 1, 0, 'C');
                $pdf->setY($pdf->getY() + 5);
                $portionen_monat += $portionen;
            }
        }

        $betrag_mwst = ($gesamt_betrag_netto_monat * $mwst_satz) - $gesamt_betrag_netto_monat;

        $gesamt_betrag_brutto = $gesamt_betrag_netto_monat * $mwst_satz;

        $breite_col_span = $w_spalte1 + $w_spalte2 + $w_spalte4 + $w_spalte5;

        $pdf->SetFont('Arial', 'B', 10);
        $pdf->Cell($breite_col_span - $w_spalte4, 5, 'Netto Gesamt', 1);
        $pdf->Cell($w_spalte4, 5, $portionen_monat, 1, 0, 'C');
        $pdf->Cell($w_spalte5, 5, EURO . ' ' . preisAusgeben($gesamt_betrag_netto_monat), 1, 0, 'C');
        $pdf->setY($pdf->getY() + 5);

        $pdf->SetFont('Arial', '', 10);
        $pdf->Cell($breite_col_span, 5, 'zzgl. ' . $mwst_satz_str . '% MwSt.', 1);
        $pdf->Cell($w_spalte5, 5, EURO . ' ' . preisAusgeben($betrag_mwst), 1, 0, 'C');
        $pdf->setY($pdf->getY() + 5);

        $pdf->SetFont('Arial', 'B', 10);
        $pdf->Cell($breite_col_span, 5, 'Brutto Gesamt', 1);

        $pdf->Cell($w_spalte5, 5, EURO . ' ' . preisAusgeben($gesamt_betrag_brutto), 1, 0, 'C');
        $pdf->setY($pdf->getY() + 10);

        /*  echo '<pre>';
          var_dump($daten);
          echo '</pre>'; */
    }
    $dateiname = 'StadtNBG_Abrechnung_' . str_replace('/', '_', $monat_str);
    $pdf->Output('S-Bar_' . $dateiname . '.pdf', 'D');
    //$pdf->Output('kitafino_' . $add_name . $file_stamp . '.pdf', 'D');
    exit;
    echo '<pre>';
    var_dump($boxen_daten);
    echo '</pre>';
}

function erzeugeBesteckExcel($daten) {
    require_once('PHPExcel-1.7.5/PHPExcel.php');
    require_once('PHPExcel-1.7.5/PHPExcel/IOFactory.php');

    $ts = mktime(12, 0, 0, $monat, $tag, $jahr);
    // neue instanz erstellen
    $xls = new PHPExcel();
    // dokumenteigenschaften schreiben
    $xls->getProperties()->setCreator("S-Bar")
        ->setLastModifiedBy("S-Bar")
        ->setTitle("Besteck Codes " . strftime('%d.%m.%y', $ts))
        ->setSubject("Besteck Codes " . strftime('%d.%m.%y', $ts));
    // das erste worksheet anwaehlen
    $sheet = $xls->setActiveSheetIndex(0);

    //STYLES START
    $sheet->getColumnDimension('A')->setAutoSize(false);

    //$sheet->getStyle('A4:E4')->applyFromArray( $style_header );
    //STYLES ENDE
    // den namen vom Worksheet 1 definieren
    $xls->getActiveSheet()->setTitle("Besteck Codes " . strftime('%d.%m.%y', $ts));
    $i = 1;

    for ($c = 1; $c <= 5; $c++) {
        foreach ($daten as $daten_zu_tour) {
            foreach ($daten_zu_tour as $data_zu_tour) {
                $sheet->setCellValue("A$i", $data_zu_tour['code']);
                $sheet->setCellValue("B$i", $data_zu_tour['name']);
                $sheet->setCellValue("C$i", $data_zu_tour['tourname']);

                $i++;
            }
        }
    }


#
    //$sheet->getStyle("A$i:D$i")->getFont()->setSize(15);

    $writer = PHPExcel_IOFactory::createWriter($xls, "Excel5");
    $sheet->getColumnDimension('A')->setWidth(25);
    $sheet->getColumnDimension('B')->setWidth(25);
    $sheet->getColumnDimension('C')->setWidth(25);

    $heute = strftime('%d_%m_%Y', time());

    if ($_SESSION['is_local_server']) {
        $writer->save('export_besteck/Besteck_' . $heute . ".xls");
    } else {
        $writer->save('export_besteck/Besteck_' . $heute . ".xls");
    }

    if ($_SESSION['is_local_server']) {
        header('location:export_besteck/Besteck_' . $heute . ".xls");
    } else {
        header('location:export_besteck/Besteck_' . $heute . ".xls");
    }
}

function checkeKitafinoStarttermin($projekt_id) {
    $db_name = 'kitafino_master';

    try {
        $conn3 = new PDO("mysql:host=".$_ENV['KITAFINO_DB_HOST'].";dbname=$db_name;", $_ENV['KITAFINO_DB_USER'], $_ENV['KITAFINO_DB_PASSWORD'], array(PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES utf8"));

        $conn3->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        if ($conn3->connect_error) {
            die("Connection failed: " . $conn3->connect_error);
        }

        $sql = "SELECT kunden.starttermin from $db_name.kunden WHERE projekt_id = $projekt_id";
        $kunde = array();
        $c = 0;
        foreach ($conn3->query($sql) as $row) {
            $kunde['starttermin'] = $row['starttermin'];
            $c++;
        }
        $conn3 = null;
        return $kunde;
    } catch (PDOException $e) {
        return 'Fehler: Keine korrekte Kitafino-ID';
        //echo 'ERROR: ' . $e->getMessage();
    }
}

function findeGruppenAusKitafino($projekt_id_db) {

    $db_name = 'kitafino_' . $projekt_id_db;

    try {
        $conn3 = new PDO("mysql:host=".$_ENV['KITAFINO_DB_HOST'].";dbname=$db_name;", $_ENV['KITAFINO_DB_USER'], $_ENV['KITAFINO_DB_PASSWORD'], array(PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES utf8"));

        $conn3->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        if ($conn3->connect_error) {
            die("Connection failed: " . $conn3->connect_error);
        }

        $sql = "SELECT gruppe.bezeichnung, gruppe.id from $db_name.gruppe ORDER BY sortierung";
        $gruppen = array();
        $c = 0;

        foreach ($conn3->query($sql) as $row) {
            $gruppen[$c]['bezeichnung'] = $row['bezeichnung'];
            $gruppen[$c]['id'] = $row['id'];
            $c++;
        }

        $conn3 = null;
        return $gruppen;
    } catch (PDOException $e) {
        return 'Fehler: Keine korrekte Kitafino-ID';
        //echo 'ERROR: ' . $e->getMessage();
    }
}

function ermittleZahlenZuKundeWoche2($woche_ts_array, $kunden_array, $pids_sbar_speisewahl = array()) {

    $orders_woche_array = array();

    $db_name = 'kitafino_master';

    $conn3 = new PDO("mysql:host=".$_ENV['KITAFINO_DB_HOST'].";dbname=$db_name;", $_ENV['KITAFINO_DB_USER'], $_ENV['KITAFINO_DB_PASSWORD'], array(PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES utf8"));
    $conn3->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    $row_count = 1;
    $sql = '';
    $bestellungen_kitafino = array();

    $tage_keys = array();
    $rr = 0;
    foreach ($woche_ts_array as $ts) {
        $tag_sel = 1 * date('d', $ts);
        $monat_sel = 1 * date('m', $ts);
        $jahr_sel = 1 * date('Y', $ts);
        $tage_keys[$tag_sel . '-' . $monat_sel . '-' . $jahr_sel] = $rr;
        $rr++;
    }

    $count_array = array();
    $orders_array = array();
    $c = 0;
    $speise_nr = 1;

    //get alle orders zu woche und pids
    $pids_arr = array();
    $kitafino_gruppen_overview = array();
    $c = 0;
    foreach ($kunden_array as $kunde) {
        $index_projekt_id = '' . $kunde->getKundennummer();
        $pids_arr[] = "$index_projekt_id";
        $kitafino_gruppen_overview[$index_projekt_id . '-' . $kunde->getId()] = str_replace(',', '-', $kunde->getKitafinoGruppen());
    }

    $pids_arr_unique = array_unique($pids_arr);

    $pids_str = "'" . implode("','", $pids_arr_unique) . "'";
    $sql = "SELECT projekt_id, user_id,tag,monat,jahr, menu_nummer FROM orders WHERE bestellt = 1 AND projekt_id IN ($pids_str) AND (";
    $in = 0;
    foreach ($woche_ts_array as $ts) {
        $tag_sel = 1 * date('d', $ts);
        $monat_sel = 1 * date('m', $ts);
        $jahr_sel = 1 * date('Y', $ts);
        $sql .= "(tag = $tag_sel AND monat = $monat_sel AND jahr = $jahr_sel) ";
        if ($in < 4) {
            $sql .= "OR ";
        }
        $in++;
    }
    $sql .= ")";

    echo '<pre>';
    var_dump($sql);
    echo '</pre>';
    $orders = array();
    foreach ($conn3->query($sql) as $row) {
        $date_str = $row['jahr'].'-'.$row['monat'].'-'.$row['tag'];
        $orders[$row['projekt_id']][$date_str]['user_ids'][] = $row['user_id'];
        $orders[$row['projekt_id']][$date_str]['menu_nummern'][] = $row['menu_nummer'];
    }

    echo '<pre>';
    var_dump(count($orders));
    echo '</pre>';
    foreach ($orders as $projekt_id => $order_infos) {
        $users = array();
        $gruppen = array();
        $db_name = 'kitafino_' . $index_projekt_id;
        define('DB_NAME2', $db_name);
        $conn_knd = new PDO("mysql:host=".$_ENV['KITAFINO_DB_HOST'].";dbname=$db_name;", $_ENV['KITAFINO_DB_USER'], $_ENV['KITAFINO_DB_PASSWORD'], array(PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES utf8"));
        $conn_knd->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $sql = "SELECT * FROM benutzer WHERE aktiv = 1";
        foreach ($conn_knd->query($sql) as $row) {
            $users[$projekt_id][$row['id']]['gruppen_id'] = $row['standort'];
            $users[$projekt_id][$row['id']]['aktiv'] = $row['aktiv'];
        }
        $sql = "SELECT * FROM gruppe";
        foreach ($conn_knd->query($sql) as $row) {
            $gruppen[$projekt_id][$row['id']] = $row['bezeichnung'];
        }
    }


    /*
     //welche gruppen sind dem sbar kunden zugeordnet? Nur Zahlen aus entsprchenden kitafino Gruppen
         $kitafino_gruppen = str_replace(',', '-', $kunde->getKitafinoGruppen());

         //artikelnr aus menus ermitteln; falls id in speiseauswahl
         $menu_nummer = $row['menu_nummer']; //menunr von orders

             if (array_search($row['pid'], $pids_sbar_speisewahl) !== false) { //falls in speisenwahl => menunr = artikelnr aus menus
                 if ($row['artikel_nummer'] == 1 || $row['artikel_nummer'] == 2) {
                     $menu_nummer = $row['artikel_nummer'];
                 }
             }
             //falls die ktiafino grp id in den zugeordneten Gruppen ist => bestellzahl setzen
             if (array_search($row['gruppe_id'], $gruppen_ids) !== false) {
                 $orders_array[$row['pid'] . '-' . $row['sbar_kid']][$tag_key]['gruppen-' . $gruppen_ids_str][$menu_nummer]++;
             }
             for ($t = 0; $t <= 4; $t++) {
                 if (!isset($orders_array[$row['pid'] . '-' . $row['sbar_kid']][$t]['gruppen-' . $gruppen_ids_str][$menu_nummer])) {
                     $orders_array[$row['pid'] . '-' . $row['sbar_kid']][$t]['gruppen-' . $gruppen_ids_str][$menu_nummer] = 0;
                 }
             }

             */

    echo '<pre>';
    var_dump($users);
    var_dump($gruppen);
    var_dump($orders);
    echo '</pre>';
    exit; /**/
    //get gruppen zu pids
    //get user zu pids

    foreach ($kunden_array as $kunde) {
        $db_name = 'kitafino_' . $kunde->getKundennummer();
        $index_projekt_id = '' . $kunde->getKundennummer();
        $kitafino_gruppen = str_replace(',', '-', $kunde->getKitafinoGruppen());
        $sbar_kid = $kunde->getId();
        $sql = "SELECT kitafino_master.orders.user_id,kitafino_master.orders.tag,kitafino_master.orders.monat,kitafino_master.orders.jahr,$db_name.menu.menu_nummer,$db_name.menu.artikel_nummer, $db_name.benutzer.standort, $db_name.gruppe.bezeichnung, $db_name.gruppe.id as gruppe_id, '$index_projekt_id' as pid, '$sbar_kid' as sbar_kid, '$kitafino_gruppen' as kitafino_gruppen "
            . "FROM kitafino_master.orders "
            . "LEFT JOIN $db_name.benutzer ON $db_name.benutzer.id = kitafino_master.orders.user_id "
            . "JOIN $db_name.gruppe ON $db_name.gruppe.id = $db_name.benutzer.standort "
            . "JOIN $db_name.menu ON kitafino_master.orders.menu_id = $db_name.menu.id ";
        $sql .= "WHERE kitafino_master.orders.projekt_id = '$index_projekt_id' AND kitafino_master.orders.bestellt = 1 AND (";
        $in = 0;
        foreach ($woche_ts_array as $ts) {
            $tag_sel = 1 * date('d', $ts);
            $monat_sel = 1 * date('m', $ts);
            $jahr_sel = 1 * date('Y', $ts);
            $sql .= "(kitafino_master.orders.tag = $tag_sel AND kitafino_master.orders.monat = $monat_sel AND kitafino_master.orders.jahr = $jahr_sel) ";
            if ($in < 4) {
                $sql .= "OR ";
            }
            $in++;
        }
        $sql .= ") ORDER BY kitafino_master.orders.jahr ASC,kitafino_master.orders.monat ASC,kitafino_master.orders.tag ASC, kitafino_master.orders.menu_nummer ASC, sbar_kid ASC";

        $row_count++;

        $_SESSION['query'] = $sql;

        foreach ($conn3->query($sql) as $row) {

            echo '<pre>';
            var_dump($row);
            echo '</pre>';
            exit; /**/
            if ($row['kitafino_gruppen'] == '') {
                continue;
            }
            $gruppen_ids = explode('-', $row['kitafino_gruppen']);
            $gruppen_ids_str = implode('-', $gruppen_ids);
            //$tag_key = $row['tag'].'-'.$row['monat'].'-'.$row['jahr'];
            $tag_key = $tage_keys[$row['tag'] . '-' . $row['monat'] . '-' . $row['jahr']];

            $menu_nummer = $row['menu_nummer'];

            if (array_search($row['pid'], $pids_sbar_speisewahl) !== false) {
                if ($row['artikel_nummer'] == 1 || $row['artikel_nummer'] == 2) {
                    $menu_nummer = $row['artikel_nummer'];
                }
            }


            if (array_search($row['gruppe_id'], $gruppen_ids) !== false) {
                $orders_array[$row['pid'] . '-' . $row['sbar_kid']][$tag_key]['gruppen-' . $gruppen_ids_str][$menu_nummer]++;
            }
            for ($t = 0; $t <= 4; $t++) {
                if (!isset($orders_array[$row['pid'] . '-' . $row['sbar_kid']][$t]['gruppen-' . $gruppen_ids_str][$menu_nummer])) {
                    $orders_array[$row['pid'] . '-' . $row['sbar_kid']][$t]['gruppen-' . $gruppen_ids_str][$menu_nummer] = 0;
                }
            }
        }
    }

    foreach ($kunden_array as $kunde) {

        for ($anz = 1; $anz <= $kunde->getAnzahlSpeisen(); $anz++) {
            for ($t = 0; $t <= 4; $t++) {
                if (!isset($orders_array[$kunde->getKundennummer() . '-' . $kunde->getId()][$t]['gruppen-' . str_replace(',', '-', $kunde->getKitafinoGruppen())][$anz])) {
                    $orders_array[$kunde->getKundennummer() . '-' . $kunde->getId()][$t]['gruppen-' . str_replace(',', '-', $kunde->getKitafinoGruppen())][$anz] = 0;
                }
            }
        }

        if (!isset($orders_array[$kunde->getKundennummer() . '-' . $kunde->getId()])) {
            $orders_array[$kunde->getKundennummer() . '-' . $kunde->getId()] = 0;
        }
    }


    return $orders_array;
}

function ermittleZahlenZuKundeWoche($woche_ts_array, $kunden_array, $pids_sbar_speisewahl = array()) {

    $projekt_id_db = $kunden_array[0]->getKundennummer();
    $db_name = 'kitafino_' . $projekt_id_db;
    $orders_woche_array = array();

    $start2 = microtime(true);
    $conn3 = new PDO("mysql:host=".$_ENV['KITAFINO_DB_HOST'].";dbname=$db_name;", $_ENV['KITAFINO_DB_USER'], $_ENV['KITAFINO_DB_PASSWORD'], array(PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES utf8"));

///$conn3 = new mysqli(DB_SERVER, DB_USER, DB_PASSWORD, DB_NAME);
    $conn3->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $_SESSION['time_db_con'] = microtime(true) - $start2;
    /* if ($conn3->connect_error) {
      die("Connection failed: " . $conn3->connect_error);
      } */

    $row_count = 1;
    $sql = '';
    $bestellungen_kitafino = array();

    $tage_keys = array();
    $rr = 0;
    foreach ($woche_ts_array as $ts) {
        $tag_sel = 1 * date('d', $ts);
        $monat_sel = 1 * date('m', $ts);
        $jahr_sel = 1 * date('Y', $ts);
        $tage_keys[$tag_sel . '-' . $monat_sel . '-' . $jahr_sel] = $rr;
        $rr++;
    }

    $count_array = array();
    $orders_array = array();
    $c = 0;
    $speise_nr = 1;
    foreach ($kunden_array as $kunde) {
        $db_name = 'kitafino_' . $kunde->getKundennummer();
        $index_projekt_id = '' . $kunde->getKundennummer();
        $kitafino_gruppen = str_replace(',', '-', $kunde->getKitafinoGruppen());
        $sbar_kid = $kunde->getId();
        $sql = "SELECT kitafino_master.orders.user_id,kitafino_master.orders.tag,kitafino_master.orders.monat,kitafino_master.orders.jahr,$db_name.menu.menu_nummer,$db_name.menu.artikel_nummer, $db_name.benutzer.standort, $db_name.gruppe.bezeichnung, $db_name.gruppe.id as gruppe_id, '$index_projekt_id' as pid, '$sbar_kid' as sbar_kid, '$kitafino_gruppen' as kitafino_gruppen "
            . "FROM kitafino_master.orders "
            . "LEFT JOIN $db_name.benutzer ON $db_name.benutzer.id = kitafino_master.orders.user_id "
            . "JOIN $db_name.gruppe ON $db_name.gruppe.id = $db_name.benutzer.standort "
            . "JOIN $db_name.menu ON kitafino_master.orders.menu_id = $db_name.menu.id ";
        $sql .= "WHERE kitafino_master.orders.projekt_id = '$index_projekt_id' AND kitafino_master.orders.bestellt = 1 AND (";
        $in = 0;
        foreach ($woche_ts_array as $ts) {
            $tag_sel = 1 * date('d', $ts);
            $monat_sel = 1 * date('m', $ts);
            $jahr_sel = 1 * date('Y', $ts);
            $sql .= "(kitafino_master.orders.tag = $tag_sel AND kitafino_master.orders.monat = $monat_sel AND kitafino_master.orders.jahr = $jahr_sel) ";
            if ($in < 4) {
                $sql .= "OR ";
            }
            $in++;
        }
        $sql .= ") ORDER BY kitafino_master.orders.jahr ASC,kitafino_master.orders.monat ASC,kitafino_master.orders.tag ASC, kitafino_master.orders.menu_nummer ASC, sbar_kid ASC";

        $row_count++;

        $_SESSION['query'] = $sql;

        foreach ($conn3->query($sql) as $row) {

            if ($row['kitafino_gruppen'] == '') {
                continue;
            }
            $gruppen_ids = explode('-', $row['kitafino_gruppen']);
            $gruppen_ids_str = implode('-', $gruppen_ids);
            $tag_key = $tage_keys[$row['tag'] . '-' . $row['monat'] . '-' . $row['jahr']];

            $menu_nummer = $row['menu_nummer'];

            if (array_search($row['pid'], $pids_sbar_speisewahl) !== false) {
                if ($row['artikel_nummer'] == 1 || $row['artikel_nummer'] == 2) {
                    $menu_nummer = $row['artikel_nummer'];
                }
            }

            if (array_search($row['gruppe_id'], $gruppen_ids) !== false) {
                $orders_array[$row['pid'] . '-' . $row['sbar_kid']][$tag_key]['gruppen-' . $gruppen_ids_str][$menu_nummer]++;
            }
            for ($t = 0; $t <= 4; $t++) {
                if (!isset($orders_array[$row['pid'] . '-' . $row['sbar_kid']][$t]['gruppen-' . $gruppen_ids_str][$menu_nummer])) {
                    $orders_array[$row['pid'] . '-' . $row['sbar_kid']][$t]['gruppen-' . $gruppen_ids_str][$menu_nummer] = 0;
                }
            }
        }
    }

    foreach ($kunden_array as $kunde) {
        for ($anz = 1; $anz <= $kunde->getAnzahlSpeisen(); $anz++) {
            for ($t = 0; $t <= 4; $t++) {
                if (!isset($orders_array[$kunde->getKundennummer() . '-' . $kunde->getId()][$t]['gruppen-' . str_replace(',', '-', $kunde->getKitafinoGruppen())][$anz])) {
                    $orders_array[$kunde->getKundennummer() . '-' . $kunde->getId()][$t]['gruppen-' . str_replace(',', '-', $kunde->getKitafinoGruppen())][$anz] = 0;
                }
            }
        }

        if (!isset($orders_array[$kunde->getKundennummer() . '-' . $kunde->getId()])) {
            $orders_array[$kunde->getKundennummer() . '-' . $kunde->getId()] = 0;
        }
    }

    return $orders_array;
}

function ermittleZahlenZuKundeTag($ts, $projekt_id_db, $kitafino_gruppen) {


    $tag_sel = 1 * date('d', $ts);
    $monat_sel = 1 * date('m', $ts);
    $jahr_sel = 1 * date('Y', $ts);
    $db_name = 'kitafino_' . $projekt_id_db;

    try {
        $conn3 = new PDO("mysql:host=".$_ENV['KITAFINO_DB_HOST'].";dbname=$db_name;", $_ENV['KITAFINO_DB_USER'], $_ENV['KITAFINO_DB_PASSWORD'], array(PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES utf8"));
        $conn3->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        if ($conn3->connect_error) {
            die("Connection failed: " . $conn3->connect_error);
        }

        $sql = "SELECT bestellung.benutzer_id,menu.menu_nummer, benutzer.standort, gruppe.bezeichnung, gruppe.id, menu.menu_nummer "
            . "FROM $db_name.bestellung "
            . "LEFT JOIN $db_name.benutzer ON benutzer.id = bestellung.benutzer_id "
            . "JOIN $db_name.gruppe ON gruppe.id = benutzer.standort "
            . "JOIN $db_name.menu ON bestellung.menu_id = menu.id "
            . "WHERE bestellung.tag = $tag_sel AND bestellung.monat = $monat_sel AND bestellung.jahr = $jahr_sel ORDER BY menu.menu_nummer ASC";

        $count_orders = 0;
        $count_array = array();
        $orders_array = array();
        $c = 0;
        $speise_nr = 1;

        foreach ($conn3->query($sql) as $row) {
            if (array_search($row['standort'], $kitafino_gruppen) === false) {
                continue;
            }
            $speise_nr = $row['menu_nummer'];
            $c++;
            $gruppen_id = $row['standort'];
            $gruppen_str = $row['bezeichnung'] . ' (' . $row['id'] . ')';
            $orders_array['Speise ' . $speise_nr][$gruppen_str] = $orders_array['Speise ' . $speise_nr][$gruppen_str] + 1;
        }

        $speise_nr = 1;
        $orders_array['Speise 1']['gesamt'] = 0;
        $orders_array['Speise 2']['gesamt'] = 0;
        foreach ($orders_array as $orders_speise_nr) {
            foreach ($orders_speise_nr as $orders_grp) {
                $orders_array['Speise ' . $speise_nr]['gesamt'] += $orders_grp;
            }
            $speise_nr++;
        }
        return $orders_array;

        $conn3 = NULL;
        // var_dump($order_ids);
    } catch (PDOException $e) {
        // echo 'ERROR: ' . $e->getMessage();
    }
}

function erzeugeKundenliste($kunden) {
    // require_once('../verwaltung_alt/PHPExcel-1.7.5/PHPExcel.php');
    // require_once('../verwaltung_alt/PHPExcel-1.7.5/PHPExcel/IOFactory.php');

    require 'PhpSpreadsheet/PhpOffice/autoload.php';


    $ts = mktime(12, 0, 0, $monat, $tag, $jahr);
    // neue instanz erstellen

    //$xls = new PHPExcel();
    $xls = new \PhpOffice\PhpSpreadsheet\Spreadsheet();
    // dokumenteigenschaften schreiben
    $xls->getProperties()->setCreator("S-Bar")
        ->setLastModifiedBy("S-Bar")
        ->setTitle("Kundenliste " . strftime('%d.%m.%y', $ts))
        ->setSubject("Kundenliste " . strftime('%d.%m.%y', $ts));
    // das erste worksheet anwaehlen
    $sheet = $xls->setActiveSheetIndex(0);

    //STYLES START
    $sheet->getColumnDimension('A')->setAutoSize(false);
    $styleArray = array(
        'borders' => array(
            'allBorders' => array(
                'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_HAIR,
                'color' => array('rgb' => 'cccccc')
            )
        ),
        'alignment' => array(
            'vertical' => PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_TOP,
            'horizontal' => PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_LEFT,
            'indent' => 0,
            'wrapText' => true,
        )
    );
    $styleArrayFahrerInfo = array(
        'font' => array(
            'bold' => true,
            'color' => array('rgb' => 'FF0000')
        )
    );
    $styleArray2 = array(
        'borders' => array(
            'allBorders' => array(
                'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_NONE
            )
        ),
        'alignment' => array(
            'vertical' => PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_TOP,
            'horizontal' => PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_LEFT,
            'indent' => 0.5,
            'wrapText' => true,
        )
    );

    $xls->getDefaultStyle()->getAlignment()->setWrapText(true);

    $sheet->getColumnDimension('A')->setWidth(25);
    $sheet->getColumnDimension('B')->setWidth(20);
    $sheet->getColumnDimension('C')->setWidth(15);
    $sheet->getColumnDimension('D')->setWidth(17);
    $sheet->getColumnDimension('E')->setWidth(5);

    //$sheet->getStyle('A4:E4')->applyFromArray( $style_header );
    //STYLES ENDE
    // den namen vom Worksheet 1 definieren
    $xls->getActiveSheet()->setTitle("Kundenliste " . strftime('%d.%m.%y', $ts));
    $i = 1;
    $pages = 1;
    $aktuelle_tour = '';
    $i++;
    foreach ($kunden as $kunde) {
        if ($kunde->getEinrichtungskategorieId() == 5) {
            $xls->getActiveSheet()->mergeCells("A$i:C$i");
            if ($aktuelle_tour) {
                $sheet->getStyle("A$i:E$i")->getFont()->setSize(15);
                $sheet->setCellValue("A$i", 'Ende ' . $aktuelle_tour);
            }

            $sheet->setBreak("A$i", PhpOffice\PhpSpreadsheet\Worksheet\Worksheet::BREAK_ROW);
            $i++;
            $aktuelle_tour = $kunde->getName();
            $sheet->getStyle("A$i:E$i")->applyFromArray($styleArray2);

            if ($pages > 1) {
                // $sheet->getHeaderFooter()->setOddFooter('&RTEST ' . $kunde->getName());
            }
            $pages++;

            $sheet->getStyle("A$i:E$i")->applyFromArray($styleArray2);
            $sheet->getStyle("A$i:E$i")->getFont()->setSize(15);
            $sheet->setCellValue("A$i", $kunde->getName());
            $sheet->getStyle("B$i")->getFont()->setSize(10);
        } else {
            $sheet->getStyle("A$i:E$i")->applyFromArray($styleArray);

            /* if (($kunde->getEinrichtungskategorieId() == 6 && (substr($kunde->getName(), 0, 4) == 'Tour' || $kunde->getName() == 'ENDE !!!' || $kunde->getName() == 'S-Bar Tour!'))) {
              $sheet->getStyle("A$i:D$i")->applyFromArray($styleArray2);
              $sheet->getStyle("A$i:D$i")->getFont()->setSize(15);
              $sheet->setCellValue("A$i", $kunde->getName());
              $i++;
              $sheet->getStyle("A$i:D$i")->getFont()->setSize(8);
              $sheet->setCellValue("A$i", 'Kunde');
              $sheet->setCellValue("B$i", 'Adresse');
              $sheet->setCellValue("C$i", 'Telefon');
              $sheet->setCellValue("D$i", 'Zeit');
              } else { */
            $sheet->getStyle("A$i:E$i")->getFont()->setSize(10);

            $add_kitafino_nr = '';
            if ($kunde->getKundennummer()) {
                $add_kitafino_nr = chr(13) . '[' . $kunde->getKundennummer() . ']';
            }

            $sheet->setCellValue("A$i", $kunde->getName() . $add_kitafino_nr);
            $add_fahrerinfo = '';

            if ($kunde->getFahrerinfo()) {
                $add_fahrerinfo = /* 'INFO: ' . chr(13) . */ $kunde->getFahrerinfo();
                if ($kunde->getEinrichtungskategorieId() == 6) {
                    $add_fahrerinfo = /* 'TOUR' . */$add_fahrerinfo;
                }
            }
            $adr_info = $kunde->getStrasse() . chr(13) . $kunde->getPlz() . ' ' . $kunde->getOrt();
            if (trim($adr_info)) {
                $adr_info .= chr(13) . chr(13);
            }
            //$adr_info .= $add_fahrerinfo;
            $sheet->setCellValue("B$i", trim($adr_info));
            $sheet->setCellValue("C$i", $kunde->getTelefon() . chr(13) . $kunde->getFax());
            $sheet->getStyle("D$i")->applyFromArray($styleArrayFahrerInfo);
            $sheet->setCellValue("D$i", trim($add_fahrerinfo));
            $uhrzeit_string = '';
            $std = substr($kunde->getEssenszeit(), 0, 2);
            $min = substr($kunde->getEssenszeit(), 2, 2);
            if ($std == 0) {

            }
            if ($min == '') {
                $min = '00';
            }
            $uhrzeit_string = $std . ':' . $min;
            if ($kunde->getEssenszeit() == 0) {
                $uhrzeit_string = '-';
            }
            $sheet->setCellValue("E$i", $uhrzeit_string);
            // }
        }

        $i++;
    }

    $sheet->getStyle("A$i:D$i")->getFont()->setSize(15);
    $sheet->setCellValue("A$i", 'Ende ' . $aktuelle_tour);

    $writer = PhpOffice\PhpSpreadsheet\IOFactory::createWriter($xls, "Xls");

    $heute = strftime('%d_%m_%Y', time());

    if ($_SESSION['is_local_server']) {
        $writer->save('export_einrichtungslisten/Kunden_' . $heute . ".xls");
    } else {
        $writer->save('export_einrichtungslisten/Kunden_' . $heute . ".xls");
    }

    if ($_SESSION['is_local_server']) {
        header('location:export_einrichtungslisten/Kunden_' . $heute . ".xls");
    } else {
        header('location:export_einrichtungslisten/Kunden_' . $heute . ".xls");
    }
}

function pruefeAufStartdatumUndAktiviere($kundeVerwaltung) {
    $kunden = $kundeVerwaltung->findeAlleInaktivenMitStartdatum();
    foreach ($kunden as $kunde) {
        if ($kunde->getStartdatum() != 0 && $kunde->getStartdatum() != NULL && $kunde->getStartdatum() != -3600) {
            $kunde->setAktiv(1);
            $kundeVerwaltung->speichere($kunde);
        }
    }
}

function erzeugeFehlendeAbrechnungstage($kundeVerwaltung, $abrechnungstagVerwaltung) {
    $kunden = $kundeVerwaltung->findeAlleAktiven();
}

function umlautepas($string) {
    $upas = Array("ä" => "ae", "ü" => "ue", "ö" => "oe", "Ä" => "Ae", "Ü" => "Ue", "Ö" => "Oe", "ß" => "ss");
    return strtr($string, $upas);
}

function entferneSonderzeichen($string) {
    $string = str_replace(' ', '_', $string);
    $string = str_replace('/', '_', $string);
    return $string;
}

function register_generate_salt() {
    $pattern = "1234567890abcdefghijklmnopqrstuvwxyz";
    for ($i = 0; $i < 10; $i++) {
        if (isset($key)) {
            $key .= $pattern[rand(0, 35)];
        } else {
            $key = $pattern[rand(0, 35)];
        }
    }
    return $key;
}

function register_generate_pw() {
    $pattern = "1234567890abcdefghijklmnopqrstuvwxyz";
    for ($i = 0; $i < 8; $i++) {
        if (isset($pw)) {
            $pw .= $patternrand[rand(0, 35)];
        } else {
            $pw = $pattern[rand(0, 35)];
        }
    }
    return $pw;
}

function roundTo($number, $to) {
    return round($number / $to, 0) * $to;
}

function erzeugeDatenTagesaufstellung($starttag, $startmonat, $startjahr, $tag, $monat, $jahr, $speise_id, $bestellungVerwaltung, $bestellung_has_speiseVerwaltung, $speiseVerwaltung, $menge_pro_portionVerwaltung, $standardportionenVerwaltung, $portionenaenderungVerwaltung, $indifaktorVerwaltung, $bemerkungzutagVerwaltung, $bemerkungzuspeiseVerwaltung, $speise_nr = 1, $kundeVerwaltung = NULL, $color_speisen = array()) {

    $kunden = $kundeVerwaltung->findeAlleAktivenZuSpeiseNr($speise_nr);

    if (($speise_nr == 3 || $speise_nr == 4) && $_REQUEST['test_prodsort']) {
        //kunden mit produktionsreihenfolge erstellen
        $kunden_touren = $kundeVerwaltung->findeAlleTouren('produktionsreihenfolge');
        $kunden = array();
        foreach ($kunden_touren as $k_tour) {
            $kunden_zu_tour = array();
            $kunden_zu_tour = $kundeVerwaltung->findeAlleAktivenZuSpeiseNrUndTourIdProduktion($speise_nr, $k_tour->getId());
            if (count($kunden_zu_tour)) {
                $kunden[] = $k_tour;
                $kunden = array_merge($kunden, $kunden_zu_tour);
            }
        }
    }

    $daten_deckblatt = array();
    $daten_checkliste = array();
    $daten_aufstellung = array();
    $gesamt_tag = array();

    $wochentag_string = strftime('%a', mktime(12, 0, 0, $monat, $tag, $jahr));
    $speise = $speiseVerwaltung->findeAnhandVonId($speise_id);

    $speise_bezeichnung = str_replace('*', '', $speise->getBezeichnung());
    $info_array = array(
        'speise_nr' => $speise_nr,
        'tag_string' => $wochentag_string,
        'datum' => $tag . '.' . $monat . '.' . $jahr,
        'speise' => $speise_bezeichnung,
        'erstellt' => strftime('%a %d.%m.%Y - %H:%M Uhr', mktime(date('H'), date('i'), date('s'), date('m'), date('d'), date('Y'))),
        'rezept' => $speise->getRezept()
    );
    $tournamen_arr = array();
    $c_knd = 0;
    foreach ($kunden as $kunde) {
        $c_knd++;
        $kunde_name = $kunde->getName();
        $kunden_id = $kunde->getId();
        $einrichtungskategorie_id = $kunde->getEinrichtungskategorieId();
        if ($einrichtungskategorie_id == 6) { //Tour leer überspringen
            continue;
        }
        if ($einrichtungskategorie_id == 5) { //falls Tourentrenner
            $tour_id = $kunde->getId();
            $tournamen_arr[$tour_id] = $kunde->getName();

            if ($kunde->getName() == 'BEGINN PRODUKTION 1' || $kunde->getName() == 'BEGINN PRODUKTION 2') {
                $tour_key = $kunde->getId() . '-' . $kunde->getName();
                $daten_aufstellung[$tour_key]['nach_kunden'] = array();
            }
        } else { //falls kunde
            $tour_id = $kunde->getTourId();
            $tour = $kundeVerwaltung->findeAnhandVonId($tour_id);
            $tourname = $tour->getName();
            if (!$tour->getId()) {
                $tourname = 'keiner Tour zugeordnet';
            }
            $tour_key = $tour_id . '-' . $tourname;
            //$tour_key = "$tour_id" ;
            $kunde_key = $kunden_id . '-' . $kunde->getName();
            $kunde_key = "$kunden_id";
            $indi_faktor = $indifaktorVerwaltung->findeAnhandVonSpeiseIdUndKundeId($speise_id, $kunden_id);
            $faktor = $indi_faktor->getFaktor();
            $menge_pro_portion = $menge_pro_portionVerwaltung->findeAnhandVonSpeiseIdUndEinrichtungskategorieId($speise_id, $einrichtungskategorie_id);

            $standardportionen = $standardportionenVerwaltung->findeAnhandVonKundenIdUndSpeiseNr($kunden_id, $speise_nr);
            $portionenaenderung = $portionenaenderungVerwaltung->findeAnhandVonKundenIdUndWochenstartdatum($kunde->getId(), $starttag, $startmonat, $startjahr, $speise_nr);

            if ($portionenaenderung->getId()) {
                $portionen_mo = $portionenaenderung->getPortionenMo();
                $portionen_di = $portionenaenderung->getPortionenDi();
                $portionen_mi = $portionenaenderung->getPortionenMi();
                $portionen_do = $portionenaenderung->getPortionenDo();
                $portionen_fr = $portionenaenderung->getPortionenFr();
            } else {
                $portionen_mo = $standardportionen->getPortionenMo();
                $portionen_di = $standardportionen->getPortionenDi();
                $portionen_mi = $standardportionen->getPortionenMi();
                $portionen_do = $standardportionen->getPortionenDo();
                $portionen_fr = $standardportionen->getPortionenFr();
            }

            switch ($wochentag_string) {
                case 'Mo':
                    $portionen = $portionen_str = $portionen_mo;
                    if ($portionen_mo != $standardportionen->getPortionenMo()) {
                        $portionen_str .= ' (' . $standardportionen->getPortionenMo() . ')';
                    }
                    break;
                case 'Di':
                    $portionen = $portionen_str = $portionen_di;
                    if ($portionen_di != $standardportionen->getPortionenDi()) {
                        $portionen_str .= ' (' . $standardportionen->getPortionenDi() . ')';
                    }
                    break;
                case 'Mi':
                    $portionen = $portionen_str = $portionen_mi;
                    if ($portionen_mi != $standardportionen->getPortionenMi()) {
                        $portionen_str .= ' (' . $standardportionen->getPortionenMi() . ')';
                    }
                    break;
                case 'Do':
                    $portionen = $portionen_str = $portionen_do;
                    if ($portionen_do != $standardportionen->getPortionenDo()) {
                        $portionen_str .= ' (' . $standardportionen->getPortionenDo() . ')';
                    }
                    break;
                case 'Fr':
                    $portionen = $portionen_str = $portionen_fr;
                    if ($portionen_fr != $standardportionen->getPortionenFr()) {
                        $portionen_str .= ' (' . $standardportionen->getPortionenFr() . ')';
                    }
                    break;
            }

            $menge_pro_port = $menge_pro_portion->getMenge();
            if (!$faktor) {
                $faktor = 1;
            }
            $menge_zu_tag = ($menge_pro_port * $faktor) * $portionen;

            if ($menge_pro_portion->getEinheit() === 'Blech') {
                $menge_zu_tag = ceil($menge_zu_tag * 2) / 2;
                $menge_split = explode('.', $menge_zu_tag);
                $anzahl_ganzer = $menge_split[0];
                $nachkomma = $menge_split[1];
                $anzahl_halber = 0;
                if ($nachkomma) {
                    $anzahl_halber = 1;
                }
                $menge_zu_tag_split = $anzahl_ganzer . ' + ' . $anzahl_halber;
                /*  echo '<pre>';
                  var_dump($tour_key);
                  var_dump($menge_zu_tag);
                  var_dump($nachkomma);
                  var_dump($anzahl_ganzer);
                  var_dump($anzahl_halber);
                  echo '</pre>'; */
            }

            if ($menge_pro_portion->getEinheit() === 'Flasche' || $menge_pro_portion->getEinheit() === 'Beutel') {
                $menge_zu_tag_preround = $menge_zu_tag;
                $komma = fmod($menge_zu_tag, 1);
                if ($menge_zu_tag < 1) {
                    $menge_zu_tag = ceil($menge_zu_tag);
                } else {
                    if ($komma < 0.4 && $komma != 0) {
                        //abrunden
                        $menge_zu_tag = floor($menge_zu_tag);
                    }
                    if ($komma >= 0.4) {
                        //abrunden
                        $menge_zu_tag = ceil($menge_zu_tag);
                    }
                }
                /* echo '<pre>';
                  var_dump($kunde->getName(),$menge_zu_tag_preround, $menge_zu_tag, $komma);
                  echo '</pre>'; */
            }

            $bemerkung_zu_tag = $bemerkungzutagVerwaltung->findeAnhandVonKundeIdUndDatumUndSpeiseId($kunden_id, $tag, $monat, $jahr, $speise_id);
            $bemerkung_zu_speise_obj = $bemerkungzuspeiseVerwaltung->findeAnhandVonKundeIdUndSpeiseId($kunden_id, $speise_id);
            $bemerkung_zu_speise = $bemerkung_zu_speise_obj->getBemerkung();

            $daten_aufstellung[$tour_key]['nach_kunden'][$kunde_key]['name'] = $kunde_name;
            $daten_aufstellung[$tour_key]['nach_kunden'][$kunde_key]['portionen'] = $portionen;
            $daten_aufstellung[$tour_key]['nach_kunden'][$kunde_key]['portionen_string'] = $portionen_str;
            $daten_aufstellung[$tour_key]['nach_kunden'][$kunde_key]['menge_pro_portion'] = $menge_pro_port * $faktor;
            $daten_aufstellung[$tour_key]['nach_kunden'][$kunde_key]['menge'] = $menge_zu_tag;
            $daten_aufstellung[$tour_key]['nach_kunden'][$kunde_key]['menge_einheit'] = $menge_pro_portion->getEinheit();
            $daten_aufstellung[$tour_key]['nach_kunden'][$kunde_key]['diets'] = $kunde->getBemerkung();
            $daten_aufstellung[$tour_key]['nach_kunden'][$kunde_key]['kundeninfo'] = $kunde->getBemerkungKunde();
            $daten_aufstellung[$tour_key]['nach_kunden'][$kunde_key]['notiz_speise'] = $bemerkung_zu_speise;

            $daten_aufstellung[$tour_key]['tour_gesamt']['portionen'] += $portionen;
            if ($menge_pro_portion->getEinheit() === 'Blech') {
                $anzahl_ganzer_halber_bleche_arr = explode(' + ', $daten_aufstellung[$tour_key]['tour_gesamt']['menge']);
                $anzahl_ganzer_bl = $anzahl_ganzer_halber_bleche_arr[0];
                $anzahl_halber_bl = explode(' x ', $anzahl_ganzer_halber_bleche_arr[1]);
                $anzahl_halber_bl = $anzahl_halber_bl[0];

                if ($anzahl_ganzer_bl == '' || $anzahl_ganzer_bl == '0') {
                    $anzahl_ganzer_bl = 0;
                }
                if ($anzahl_ganzer == '' || $anzahl_ganzer == '0') {
                    $anzahl_ganzer = 0;
                }
                if ($anzahl_halber_bl == '' || $anzahl_halber_bl == '0') {
                    $anzahl_halber_bl = 0;
                }
                if ($anzahl_halber == '0' || $anzahl_halber == '') {
                    $anzahl_halber = 0;
                }/*	*/

                $anzahl_ganzer_neu = $anzahl_ganzer_bl + $anzahl_ganzer;

                $anzahl_halber_neu = $anzahl_halber_bl + $anzahl_halber;
                if ($anzahl_halber_neu) {
                    $daten_aufstellung[$tour_key]['tour_gesamt']['menge'] = $anzahl_ganzer_neu . ' + ' . $anzahl_halber_neu . ' x½';
                } else {
                    $daten_aufstellung[$tour_key]['tour_gesamt']['menge'] = $anzahl_ganzer_neu;
                }


                $anzahl_ganzer_halber_bleche_arr = explode(' + ', $gesamt_tag['menge']);
                $anzahl_ganzer_bl = $anzahl_ganzer_halber_bleche_arr[0];
                $anzahl_halber_bl = explode(' x ', $anzahl_ganzer_halber_bleche_arr[1]);
                $anzahl_halber_bl = $anzahl_halber_bl[0];

                if ($anzahl_ganzer_bl == '' || $anzahl_ganzer_bl == '0') {
                    $anzahl_ganzer_bl = 0;
                }
                if ($anzahl_ganzer == '' || $anzahl_ganzer == '0') {
                    $anzahl_ganzer = 0;
                }
                if ($anzahl_halber_bl == '' || $anzahl_halber_bl == '0') {
                    $anzahl_halber_bl = 0;
                }
                if ($anzahl_halber == '0' || $anzahl_halber == '') {
                    $anzahl_halber = 0;
                }/*	*/


                $anzahl_ganzer_neu = $anzahl_ganzer_bl + $anzahl_ganzer;
                $anzahl_halber_neu = $anzahl_halber_bl + $anzahl_halber;
                if ($anzahl_halber_neu) {
                    $gesamt_tag['menge'] = $anzahl_ganzer_neu . ' + ' . $anzahl_halber_neu . ' x½';
                } else {
                    $gesamt_tag['menge'] = $anzahl_ganzer_neu;
                }
            } else {
                $daten_aufstellung[$tour_key]['tour_gesamt']['menge'] += $menge_zu_tag;
                $gesamt_tag['menge'] += $menge_zu_tag;
            }


            $daten_aufstellung[$tour_key]['tour_gesamt']['menge_einheit'] = $menge_pro_portion->getEinheit();
            $gesamt_tag['portionen'] += $portionen;

            if (trim($bemerkung_zu_speise)) {
                $daten_checkliste[$tour_key][] = $kunde->getId();
            }
        }
    }
    // exit;
    $return_array = array(
        'tagesaufstellung' => $daten_aufstellung,
        'gesamt_tag' => $gesamt_tag,
        'info' => $info_array,
        'checkliste' => $daten_checkliste
    );
    return $return_array;
}

function erzeugeDeckblatt($sheet, $daten, $i, $styles_array) {
    $daten_tagesaufstellung = $daten['tagesaufstellung'];
    $daten_info = $daten['info'];
    $daten_gesamt = $daten['gesamt_tag'];
    $sheet->getStyle("A$i:G$i")->applyFromArray($styles_array['header_dark']);
    $sheet->getStyle("E$i")->getFont()->setSize(11);
    $sheet->mergeCells("A$i:B$i");
    $sheet->mergeCells("C$i:D$i");
    $sheet->mergeCells("E$i:G$i");
    $sheet->getStyle("A$i")->getFont()->setSize(20);
    $sheet->setCellValue("A$i", $daten_info['speise'] . ' - Speise ' . $daten_info['speise_nr']);
    $sheet->getStyle("C$i")->getFont()->setSize(12);
    $sheet->setCellValue("C$i", $daten_info['datum']);
    $sheet->setCellValue("E$i", 'Erstellt: ' . strftime('%a %d.%m.%Y - %H:%M Uhr', mktime(date('H'), date('i'), date('s'), date('m'), date('d'), date('Y'))));
    $i++;
    $sheet->getStyle("A$i:G$i")->applyFromArray($styles_array['row_gesamt']);
    $sheet->mergeCells("A$i:C$i");
    $sheet->mergeCells("D$i:E$i");
    $sheet->mergeCells("F$i:G$i");
    $sheet->setCellValue("A$i", 'TOUR');
    $sheet->setCellValue("D$i", 'MENGE');
    $sheet->setCellValue("F$i", 'PORTIONEN');
    $i++;

    foreach ($daten_tagesaufstellung as $tour_key => $daten_tour) {

        if ($daten_tour['tour_gesamt']['portionen'] == 0) {
            continue;
        }

        $tour_key_arr = explode('-', $tour_key);
        $tour_id = trim($tour_key_arr[0]);
        unset($tour_key_arr[0]);
        $tour_name = implode('-', $tour_key_arr);
        if ($i % 2) {
            $sheet->getStyle("A$i:G$i")->applyFromArray($styles_array['row_1']);
        } else {
            $sheet->getStyle("A$i:G$i")->applyFromArray($styles_array['row_2']);
        }

        $sheet->getStyle("A$i")->getAlignment()->applyFromArray(
            array('horizontal' => PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_LEFT,)
        );

        $gesamtmenge_tag_speise_umger = $daten_tour['tour_gesamt']['menge'];
        $einheit = $daten_tour['tour_gesamt']['menge_einheit'];
        if ($daten_tour['tour_gesamt']['menge_einheit'] == 'g' || $daten_tour['tour_gesamt']['menge_einheit'] == 'ml') {
            $gesamtmenge_tag_speise_umger = $daten_tour['tour_gesamt']['menge'] / 1000;
            switch ($daten_tour['tour_gesamt']['menge_einheit']) {
                case 'g':
                    $einheit = 'kg';
                    break;
                case 'ml':
                    $einheit = 'L';
                    break;
            }
        }

        $sheet->mergeCells("A$i:C$i");
        $sheet->mergeCells("D$i:E$i");
        $sheet->mergeCells("F$i:G$i");
        $sheet->setCellValue("A$i", $tour_name);
        $sheet->setCellValue("D$i", $gesamtmenge_tag_speise_umger . ' ' . $einheit);
        $sheet->setCellValue("F$i", $daten_tour['tour_gesamt']['portionen']);
        $i++;
    }

    $gesamtmenge_umger = $daten_gesamt['menge'];
    if ($einheit == 'kg' || $einheit == 'L') {
        $gesamtmenge_umger = $daten_gesamt['menge'] / 1000;
    }


    $sheet->getStyle("A$i:G$i")->applyFromArray($styles_array['row_gesamt']);
    // $sheet->mergeCells("A$i:B$i");
    $sheet->mergeCells("B$i:E$i");
    $sheet->mergeCells("F$i:G$i");
    $sheet->getStyle("A$i:G$i")->getFont()->setSize(18);
    $sheet->setCellValue("A$i", 'GESAMT');
    $sheet->setCellValue("B$i", $gesamtmenge_umger . ' ' . $einheit);
    $sheet->setCellValue("F$i", $daten_gesamt['portionen']);
    $i++;
    $i++;

    if (trim($daten_info['rezept']) != '') {
        $sheet->setCellValue("A$i", "REZEPT/BEMERK.:");
        $sheet->getStyle("A$i")->getFont()->setBold(true);
        $i++;
        $sheet->mergeCells("A$i:E$i");
        $sheet->getStyle("A$i")->getAlignment()->setWrapText(true);
        $sheet->setCellValue("A$i", $daten_info['rezept']);
        $i++;
    }
    return $i;
}

function erzeugeCheckliste($sheet, $daten, $i, $styles_array) {
    $daten_checkliste = $daten['checkliste'];
    $daten_info = $daten['info'];

    $sheet->getStyle("A$i:G$i")->applyFromArray($styles_array['header_orange']);
    $sheet->getStyle("A$i:G$i")->getAlignment()->setWrapText(true);
    $sheet->mergeCells("A$i:G$i");
    $sheet->setCellValue("A$i", 'Checkliste Besonderheiten am ' . $daten_info['datum'] . ' - ' . $daten_info['speise']);
    $i++;

    foreach ($daten_checkliste as $tour_key => $kunde_ids) {

        $tour_key_arr = explode('-', $tour_key);
        $tour_id = trim($tour_key_arr[0]);
        $tour_name = trim($tour_key_arr[1]);

        $sheet->getStyle("A$i:G$i")->applyFromArray($styles_array['tour']);
        $sheet->getStyle("A$i:G$i")->getAlignment()->setWrapText(true);
        $sheet->mergeCells("A$i:G$i");
        $sheet->setCellValue("A$i", $tour_name);
        $i++;
        foreach ($kunde_ids as $kunde_id) {
            $infos = $daten['tagesaufstellung'][$tour_key]['nach_kunden'][$kunde_id];

            $sheet->getStyle("A$i:G$i")->applyFromArray($styles_array['standard']);
            $sheet->getStyle("A$i:G$i")->getAlignment()->setWrapText(true);
            $sheet->mergeCells("A$i:B$i");
            $sheet->mergeCells("D$i:E$i");
            $sheet->mergeCells("F$i:G$i");
            $sheet->setCellValue("A$i", $infos['name']);
            $sheet->setCellValue("C$i", $infos['diets']);
            $sheet->setCellValue("D$i", $infos['kundeninfo']);
            $sheet->setCellValue("F$i", $infos['notiz_speise']);
            /*    echo '<pre>';
              var_dump($tour_key, $kunde_id, $infos);
              echo '</pre>'; */
            $i++;
        }
    }

    $i++;
    $sheet->mergeCells("A$i:G$i");
    $sheet->setCellValue("A$i", 'Die Besonderheiten wurden berücksichtigt:');
    $i++;

    $sheet->mergeCells("A$i:G$i");
    $sheet->getStyle("A$i")->applyFromArray($styles_array['handtext']);
    $sheet->getRowDimension($i)->setRowHeight(30);
    $i++;

    $sheet->mergeCells("A$i:G$i");
    $sheet->setCellValue("A$i", 'Unterschrift');
    $i++;
    $sheet->mergeCells("A$i:G$i");
    $sheet->setCellValue("A$i", 'Bemerkungen:');
    $i++;

    $sheet->getStyle("A$i")->applyFromArray($styles_array['handtext']);
    $sheet->getRowDimension($i)->setRowHeight(30);
    $sheet->mergeCells("A$i:G$i");
    $i++;
    $sheet->getStyle("A$i")->applyFromArray($styles_array['handtext']);
    $sheet->getRowDimension($i)->setRowHeight(30);
    $sheet->mergeCells("A$i:G$i");
    $i++;

    $i++;

    return $i;
}

function erzeugeTagesaufstellungExcelMitDeckblatt($tag, $monat, $jahr, $starttag, $startmonat, $startjahr, $speise_id, $bestellung, $bestellung_has_speiseVerwaltung, $speiseVerwaltung, $menge_pro_portionVerwaltung, $standardportionenVerwaltung, $portionenaenderungVerwaltung, $kunden, $indifaktorVerwaltung, $bemerkungzutagVerwaltung, $bemerkungzuspeiseVerwaltung, $speise_nr = 1, $kundeVerwaltung = NULL, $color_speisen = array()) {
    $daten = erzeugeDatenTagesaufstellung($starttag, $startmonat, $startjahr, $tag, $monat, $jahr, $speise_id, $bestellungVerwaltung, $bestellung_has_speiseVerwaltung, $speiseVerwaltung, $menge_pro_portionVerwaltung, $standardportionenVerwaltung, $portionenaenderungVerwaltung, $indifaktorVerwaltung, $bemerkungzutagVerwaltung, $bemerkungzuspeiseVerwaltung, $speise_nr, $kundeVerwaltung, $color_speisen);
    $daten_tagesaufstellung = $daten['tagesaufstellung'];
    $daten_info = $daten['info'];
    $daten_gesamt = $daten['gesamt_tag'];
    $daten_info['speise'] = str_replace('*', '', $daten_info['speise']);

    /* require_once('../verwaltung_alt/PHPExcel-1.7.5/PHPExcel.php');
     require_once('../verwaltung_alt/PHPExcel-1.7.5/PHPExcel/IOFactory.php');*/

    require '../PhpSpreadsheet/PhpOffice/autoload.php';
    $ts = mktime(12, 0, 0, $monat, $tag, $jahr);

    //$xls = new PHPExcel();
    $xls = new \PhpOffice\PhpSpreadsheet\Spreadsheet();
    $xls->getProperties()->setCreator("S-Bar")
        ->setLastModifiedBy("S-Bar")
        ->setTitle("Tagesaufstellung " . strftime('%d.%m.%y', $ts))
        ->setSubject("Tagesaufstellung " . strftime('%d.%m.%y', $ts));
    $sheet = $xls->setActiveSheetIndex(0);
    $sheet->getPageMargins()->setTop(0.5);
    $sheet->getPageMargins()->setRight(0.5);
    $sheet->getPageMargins()->setLeft(0.5);
    $sheet->getPageMargins()->setBottom(0.5);
    $sheet->getPageSetup()->setOrientation(PhpOffice\PhpSpreadsheet\Worksheet\PageSetup::ORIENTATION_PORTRAIT);
    $sheet->getPageSetup()->setPaperSize(PhpOffice\PhpSpreadsheet\Worksheet\PageSetup::PAPERSIZE_A4);

    $sheet->getColumnDimension('A')->setWidth(32);
    $sheet->getColumnDimension('B')->setWidth(11);
    $sheet->getColumnDimension('C')->setWidth(8);
    $sheet->getColumnDimension('D')->setWidth(7);
    $sheet->getColumnDimension('E')->setWidth(7);
    $sheet->getColumnDimension('F')->setWidth(12);
    $sheet->getColumnDimension('G')->setWidth(12);

    $default_border = array(
        'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
        'color' => array('rgb' => 'BEBEBE')
    );
    $border_color = 'DDDDDD';
    $styles_array = array(
        'standard' => array(
            'borders' => array(
                'allborders' => array(
                    'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
                    'color' => array('rgb' => $border_color)
                )
            )
        ),
        'header_orange' => array(
            'borders' => array(
                'allborders' => array(
                    'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
                    'color' => array('rgb' => $border_color)
                )
            ),
            'fill' => array(
                'fillType' => PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
                'startColor' => array('rgb' => str_replace('#', '', $color_speisen[$speise_nr + 4])),
            ),
            'font' => array(
                'bold' => true,
                'color' => array('rgb' => 'ffffff'),
                'size' => 14
            ),
            'alignment' => array(
                'horizontal' => PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER,
                'vertical' => PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER,
                'indent' => 0.5,
                'wrapText' => true
            )
        ),
        'header_dark' => array(
            'borders' => array(
                'allborders' => array(
                    'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
                    'color' => array('rgb' => $border_color)
                )
            ),
            'fill' => array(
                'fillType' => PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
                'startColor' => array('rgb' => str_replace('#', '', $color_speisen[$speise_nr + 4])),
            ),
            'font' => array(
                'bold' => true,
                'color' => array('rgb' => '000000'),
                'size' => 14
            ),
            'alignment' => array(
                'horizontal' => PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER,
                'vertical' => PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER,
                'indent' => 0.5,
                'wrapText' => true
            )
        ),
        'header_orange2' => array(
            'borders' => array(
                'allborders' => array(
                    'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
                    'color' => array('rgb' => $border_color)
                )
            ),
            'fill' => array(
                'fillType' => PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
                'startColor' => array('rgb' => str_replace('#', '', $color_speisen[$speise_nr])),
            ),
            'font' => array(
                'bold' => true,
                'color' => array('rgb' => '000000'),
                'size' => 14
            ),
            'alignment' => array(
                'horizontal' => PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER,
                'vertical' => PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER,
                'indent' => 0.5,
                'wrapText' => true
            )
        ),
        'row_gesamt' => array(
            'borders' => array(
                'allborders' => array(
                    'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
                    'color' => array('rgb' => $border_color)
                )
            ),
            'fill' => array(
                'fillType' => PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
                'startColor' => array('rgb' => 'C3C2D9'),
            ),
            'font' => array(
                'bold' => true,
                'color' => array('rgb' => '000000'),
                'size' => 12
            ),
            'alignment' => array(
                'horizontal' => PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER,
                'indent' => 0.5,
            )
        ),
        'row_1' => array(
            'borders' => array(
                'allborders' => array(
                    'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
                    'color' => array('rgb' => $border_color)
                )
            ),
            'fill' => array(
                'fillType' => PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
                'startColor' => array('rgb' => 'FFFFFF'),
            ),
            'font' => array(
                'bold' => true,
                'color' => array('rgb' => '000000')
            ),
            'alignment' => array(
                'horizontal' => PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER,
                'vertical' => PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_TOP,
                'indent' => 0.5,
                'wrapText' => true,
            )
        ),
        'row_2' => array(
            'borders' => array(
                'allborders' => array(
                    'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
                    'color' => array('rgb' => $border_color)
                )
            ),
            'fill' => array(
                'fillType' => PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
                'startColor' => array('rgb' => 'E1E0F7'),
            ),
            'font' => array(
                'bold' => true,
                'color' => array('rgb' => '000000')
            ),
            'alignment' => array(
                'horizontal' => PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER,
                'vertical' => PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_TOP,
                'indent' => 0.5,
                'wrapText' => true
            )
        ),
        'tour' => array(
            'borders' => array(
                'allborders' => array(
                    'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
                    'color' => array('rgb' => $border_color)
                )
            ),
            'fill' => array(
                'fillType' => PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
                'startColor' => array('rgb' => 'dddddd'),
            ),
            'font' => array(
                'bold' => true,
                'color' => array('rgb' => '888888'),
                'size' => 16
            ),
            'alignment' => array(
                'horizontal' => PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER,
                'vertical' => PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_TOP,
                'indent' => 0.5,
                'wrapText' => true
            )
        ),
        'bg_grau' => array(
            'fill' => array(
                'fillType' => PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
                'startColor' => array('rgb' => 'dddddd'),
            ),
            'font' => array(
                'color' => array('rgb' => '888888')
            )
        ),
        'bg_gelb' => array(
            'fill' => array(
                'fillType' => PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
                'startColor' => array('rgb' => 'F8F87F'),
            )
        ),
        'bg_rot' => array(
            'fill' => array(
                'fillType' => PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
                'startColor' => array('rgb' => 'FF5050'),
            )
        ),
        'null_portionen' => array(
            'font' => array(
                'color' => array('rgb' => '888888')
            ),
            'alignment' => array(
                'indent' => 1
            )
        ),
        'tourtrenner' => array(
            'font' => array(
                'bold' => true,
                'size' => 13,
                'color' => array('rgb' => '444444')
            ),
            'alignment' => array(
                'wrapText' => true
            ),
            'fill' => array(
                'fillType' => PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
                'startColor' => array('rgb' => 'CAE2FF'),
            )
        ),
        'handtext' => array(
            'borders' => array(
                'top' => array(),
                'left' => array(),
                'bottom' => array(
                    'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_DOTTED,
                    'color' => array('rgb' => '444444')
                ),
                'right' => array(),
            )
        )
    );

    $sheet->getColumnDimension('A')->setWidth(32);
    $sheet->getColumnDimension('B')->setWidth(11);
    $sheet->getColumnDimension('C')->setWidth(8);
    $sheet->getColumnDimension('D')->setWidth(7);
    $sheet->getColumnDimension('E')->setWidth(7);
    $sheet->getColumnDimension('F')->setWidth(12);
    $sheet->getColumnDimension('G')->setWidth(12);

    $datum_tag_string = $daten_info['tag_string'] . ' ' . $daten_info['datum'];
    $erstellt = $daten_info['erstellt'];

    $i = 1;
    /* echo '<pre>';
      var_dump($daten_tagesaufstellung);
      echo '</pre>'; */

    $deckblatt_nr = 1;

    if ($daten_info['speise_nr'] > 1) {
        $sheet->mergeCells("A$i:G$i");
        $sheet->getStyle("A$i:G$i")->applyFromArray($styles_array['row_1']);
        $sheet->setCellValue("A$i", 'Deckblatt ' . $deckblatt_nr);
        $i++;
        $i = erzeugeDeckblatt($sheet, $daten, $i, $styles_array); //Deckblatt 1
        $i++;

        $sheet->mergeCells("A$i:G$i");
        $sheet->getStyle("A$i:G$i")->applyFromArray($styles_array['row_1']);
        $sheet->setCellValue("A$i", 'ENDE Deckblatt ' . $deckblatt_nr);
        $i++;

        $deckblatt_nr++;

        $break_before_line = $i - 1;
        $break_before_line_str = "A" . $break_before_line;
        $sheet->setBreak($break_before_line_str, PhpOffice\PhpSpreadsheet\Worksheet\Worksheet::BREAK_ROW);
    }

    $seitenzahl = 1;
    foreach ($daten_tagesaufstellung as $tour_key => $data) {
        $tour_key_arr = explode('-', $tour_key);
        $tour_id = trim($tour_key_arr[0]);
        $tour_name = trim($tour_key_arr[1]);

        $style = 'header_orange2';

        if ($tour_name === 'BEGINN PRODUKTION 1' || $tour_name === 'BEGINN PRODUKTION 2') {
            $break_before_line = $i - 1;
            $break_before_line_str = "A" . $break_before_line;
            $sheet->setBreak($break_before_line_str, PhpOffice\PhpSpreadsheet\Worksheet\Worksheet::BREAK_ROW);

            $sheet->mergeCells("A$i:G$i");
            $sheet->getStyle("A$i:G$i")->applyFromArray($styles_array['row_1']);
            $sheet->setCellValue("A$i", 'Deckblatt ' . $deckblatt_nr);
            $i++;

            $i = erzeugeDeckblatt($sheet, $daten, $i, $styles_array); //Deckblatt 1
            $i++;

            $i = erzeugeCheckliste($sheet, $daten, $i, $styles_array); //Deckblatt 1
            $i++;

            $sheet->mergeCells("A$i:G$i");
            $sheet->getStyle("A$i:G$i")->applyFromArray($styles_array['row_1']);
            $sheet->setCellValue("A$i", 'ENDE Deckblatt ' . $deckblatt_nr);
            $i++;

            $break_before_line = $i - 1;
            $break_before_line_str = "A" . $break_before_line;
            $sheet->setBreak($break_before_line_str, PhpOffice\PhpSpreadsheet\Worksheet\Worksheet::BREAK_ROW);

            $sheet->mergeCells("A$i:G$i");
            $sheet->getStyle("A$i:G$i")->applyFromArray($styles_array['row_1']);
            $sheet->setCellValue("A$i", 'SEITE ' . $seitenzahl);
            // $seitenzahl++;
            $i++;

            $sheet->getStyle("A$i:G$i")->getFont()->setBold(true);
            $sheet->getStyle("A$i:G$i")->getFont()->setSize(14);
            $sheet->mergeCells("B$i:G$i");
            $sheet->setCellValue("A$i", $daten_info['tag_string'] . ' ' . $daten_info['datum']);
            $sheet->setCellValue("B$i", ' Erstellt: ' . $daten_info['erstellt']);
            $i++;

            $sheet->getStyle("A$i:G$i")->applyFromArray($styles_array['header_orange2']);
            $sheet->mergeCells("A$i:G$i");
            $sheet->setCellValue("A$i", $daten_info['speise']);
            $i++;

            $style = 'tour';
        } elseif ($speise_nr) {
            $break_before_line = $i - 1;
            $break_before_line_str = "A" . $break_before_line;
            $sheet->setBreak($break_before_line_str, PhpOffice\PhpSpreadsheet\Worksheet\Worksheet::BREAK_ROW);

            $sheet->mergeCells("A$i:G$i");
            $sheet->getStyle("A$i:G$i")->applyFromArray($styles_array['row_1']);
            $sheet->setCellValue("A$i", 'SEITE ' . $seitenzahl);
            $i++;
        }


        $sheet->getStyle("A$i:G$i")->applyFromArray($styles_array[$style]);
        $sheet->getStyle("E$i:G$i")->getFont()->setSize(12);
        $sheet->mergeCells("A$i:D$i");
        $sheet->mergeCells("E$i:G$i");
        $sheet->setCellValue("A$i", $daten_info['speise']);
        $sheet->getStyle("E$i")->applyFromArray($styles_array[$style]);
        $sheet->setCellValue("E$i", 'Speise ' . $speise_nr);
        $i++;

        $sheet->getStyle("A$i:G$i")->applyFromArray($styles_array[$style]);
        $sheet->getStyle("E$i:G$i")->getFont()->setSize(12);
        $sheet->mergeCells("A$i:D$i");
        $sheet->mergeCells("E$i:G$i");
        $sheet->setCellValue("A$i", $tour_name . ' ' . $datum_tag_string);
        $sheet->getStyle("E$i")->applyFromArray($styles_array['bg_grau']);
        $sheet->setCellValue("E$i", $erstellt);
        $i++;

        $sheet->setCellValue("A$i", 'Kunde');
        $sheet->setCellValue("B$i", 'Gesamt');
        $sheet->setCellValue("C$i", 'Diät');
        $sheet->setCellValue("D$i", 'Port.');
        $sheet->setCellValue("E$i", 'pro P..');
        $sheet->setCellValue("F$i", 'Kundeninfo');
        $sheet->setCellValue("G$i", 'Notiz Speise');
        $i++;

        //tourtrenner zu tour
        $sheet->getStyle("A$i:G$i")->getFont()->setSize(12);
        $sheet->getStyle("A$i:G$i")->applyFromArray($styles_array['row_1']);
        $sheet->getStyle("A$i:G$i")->applyFromArray($styles_array['tourtrenner']);
        $tour_trenner = $kundeVerwaltung->findeTourtrennerZuTour($tour_id);
        $sheet->mergeCells("A$i:E$i");
        $sheet->setCellValue("A$i", $tour_trenner->getName());
        $sheet->mergeCells("F$i:G$i");
        $sheet->setCellValue("F$i", $tour_trenner->getBemerkungKunde());
        $i++;

        $data_kunden = $data['nach_kunden'];
        foreach ($data_kunden as $kunden_id => $data_kunde) {
            $bemerkung_kunde = $data_kunde['kundeninfo'];
            $bemerkung_kunde = str_replace('+sauber', '', $bemerkung_kunde);
            $bemerkung_kunde = str_replace('+ sauber', '', $bemerkung_kunde);
            $bemerkung_kunde = str_replace('sauber', '', $bemerkung_kunde);
            $bemerkung_kunde = str_replace('Wärmeplatten', 'Wärmeplatte', $bemerkung_kunde);
            $bemerkung_kunde = str_replace('+ Wärmeplatte', 'W', $bemerkung_kunde);
            $bemerkung_kunde = str_replace('+Wärmeplatte', 'W', $bemerkung_kunde);
            $bemerkung_kunde = str_replace('Wärmeplatte', 'W', $bemerkung_kunde);
            $bemerkung_kunde = str_replace('+ Vegi', 'V', $bemerkung_kunde);
            $bemerkung_kunde = str_replace('Vegi', 'V', $bemerkung_kunde);
            $bemerkung_kunde = str_replace('+ Diät', 'D', $bemerkung_kunde);
            $bemerkung_kunde = str_replace('Diät', 'D', $bemerkung_kunde);
            /*    echo '<pre>';
              var_dump($data_kunde);
              echo '</pre>'; */
            if ($i % 2) {
                $sheet->getStyle("A$i:G$i")->applyFromArray($styles_array['row_1']);
            } else {
                $sheet->getStyle("A$i:G$i")->applyFromArray($styles_array['row_2']);
            }
            if ($data_kunde['portionen'] == 0) {
                $sheet->getStyle("A$i:G$i")->applyFromArray($styles_array['null_portionen']);
            }
            if ($bemerkung_kunde && $bemerkung_kunde != 'W') {
                $sheet->getStyle("F$i")->applyFromArray($styles_array['bg_gelb']);
            }

            if ($data_kunde['notiz_speise'] && $data_kunde['portionen']) {
                $sheet->getStyle("A$i:G$i")->applyFromArray($styles_array['bg_rot']);
            }

            $sheet->getStyle("A$i")->getAlignment()->applyFromArray(
                array('horizontal' => PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_LEFT)
            );
            $sheet->getStyle("A$i:B$i")->getFont()->setSize(15);
            $sheet->setCellValue("A$i", $data_kunde['name']);
            $sheet->setCellValue("B$i", $data_kunde['menge']);
            $sheet->getStyle("C$i")->getFont()->setSize(12);
            $sheet->setCellValue("C$i", $data_kunde['diets']);
            $sheet->getStyle("D$i:E$i")->getFont()->setSize(8);
            $sheet->setCellValue("D$i", $data_kunde['portionen']);
            $sheet->setCellValue("E$i", $data_kunde['menge_pro_portion']);
            $sheet->getStyle("F$i:G$i")->getFont()->setSize(12);
            $sheet->setCellValue("F$i", $bemerkung_kunde);
            $sheet->setCellValue("G$i", $data_kunde['notiz_speise']);
            $i++;
        }

        if ($data_kunde['menge_einheit'] == 'Blech') {

            $sheet->getStyle("A$i:G$i")->applyFromArray($styles_array['row_gesamt']);
            $sheet->getStyle("A$i:G$i")->getFont()->setSize(16);

            $sheet->setCellValue("A$i", 'GESAMT');
            $sheet->mergeCells("B$i:G$i");
            $sheet->setCellValue("B$i", $daten_tagesaufstellung[$tour_key]['tour_gesamt']['menge'] . ' Blech');
            $i++;
            /*  echo '<pre>';
              var_dump($tour_key);
              var_dump($daten_tagesaufstellung[$tour_key]['tour_gesamt']['menge']);
              echo '</pre>'; */
        }

        $sheet->mergeCells("A$i:G$i");
        $sheet->getStyle("A$i:G$i")->applyFromArray($styles_array['row_1']);
        $sheet->setCellValue("A$i", 'ENDE SEITE ' . $seitenzahl);
        $seitenzahl++;
        $i++;
    }

    $writer = PhpOffice\PhpSpreadsheet\IOFactory::createWriter($xls, "Xls");
    $heute = strftime('%d_%m_%Y', time());
    $rechner = php_uname('n');
    $speise_bezeichnung = $daten_info['speise'];
    $speise_bezeichnung = umlautepas($speise_bezeichnung);
    $speise_bezeichnung = entferneSonderzeichen($speise_bezeichnung);
    if ($_SESSION['is_local_server']) {
        $writer->save('export_tagesaufstellungen/' . $speise_bezeichnung . '_' . $speise_nr . '_' . strftime('%d_%m_%y', $ts) . ".xls");
    } else {
        $writer->save('/is/htdocs/wp1055935_1F55KTNHIC/www/sbar/verwaltung/export_tagesaufstellungen/' . $speise_bezeichnung . '_' . $speise_nr . '_' . strftime('%d_%m_%y', $ts) . ".xls");
    }
    if ($_SESSION['is_local_server']) {
        header('location:export_tagesaufstellungen/' . $speise_bezeichnung . '_' . $speise_nr . '_' . strftime('%d_%m_%y', $ts) . ".xls");
        exit;
    } else {
        header('location:http://www.s-bar.net/verwaltung/export_tagesaufstellungen/' . $speise_bezeichnung . '_' . $speise_nr . '_' . strftime('%d_%m_%y', $ts) . ".xls");
        exit;
    }

    /* echo '<pre>';
      var_dump($daten_info);
      echo '</pre>';
      exit; */
}



function erzeugeTagesaufstellungExcelMitDeckblattV2($tag, $monat, $jahr, $starttag, $startmonat, $startjahr, $speise_id, $bestellung, $bestellung_has_speiseVerwaltung, $speiseVerwaltung, $menge_pro_portionVerwaltung, $standardportionenVerwaltung, $portionenaenderungVerwaltung, $kunden, $indifaktorVerwaltung, $bemerkungzutagVerwaltung, $bemerkungzuspeiseVerwaltung, $speise_nr = 1, $kundeVerwaltung = NULL, $color_speisen = array()) {
    $daten = erzeugeDatenTagesaufstellung($starttag, $startmonat, $startjahr, $tag, $monat, $jahr, $speise_id, $bestellungVerwaltung, $bestellung_has_speiseVerwaltung, $speiseVerwaltung, $menge_pro_portionVerwaltung, $standardportionenVerwaltung, $portionenaenderungVerwaltung, $indifaktorVerwaltung, $bemerkungzutagVerwaltung, $bemerkungzuspeiseVerwaltung, $speise_nr, $kundeVerwaltung, $color_speisen);
    $daten_tagesaufstellung = $daten['tagesaufstellung'];
    $daten_info = $daten['info'];
    $daten_gesamt = $daten['gesamt_tag'];
    $daten_info['speise'] = str_replace('*', '', $daten_info['speise']);

    /* require_once('../verwaltung_alt/PHPExcel-1.7.5/PHPExcel.php');
     require_once('../verwaltung_alt/PHPExcel-1.7.5/PHPExcel/IOFactory.php');*/

    require '../PhpSpreadsheet/PhpOffice/autoload.php';

    $ts = mktime(12, 0, 0, $monat, $tag, $jahr);

    //$xls = new PHPExcel();
    $xls = new \PhpOffice\PhpSpreadsheet\Spreadsheet();
    $xls->getProperties()->setCreator("S-Bar")
        ->setLastModifiedBy("S-Bar")
        ->setTitle("Tagesaufstellung " . strftime('%d.%m.%y', $ts))
        ->setSubject("Tagesaufstellung " . strftime('%d.%m.%y', $ts));
    $sheet = $xls->setActiveSheetIndex(0);
    $sheet->getPageMargins()->setTop(0.5);
    $sheet->getPageMargins()->setRight(0.5);
    $sheet->getPageMargins()->setLeft(0.5);
    $sheet->getPageMargins()->setBottom(0.5);
    $sheet->getPageSetup()->setOrientation(PhpOffice\PhpSpreadsheet\Worksheet\PageSetup::ORIENTATION_PORTRAIT);
    $sheet->getPageSetup()->setPaperSize(PhpOffice\PhpSpreadsheet\Worksheet\PageSetup::PAPERSIZE_A4);

    //$sheet->getColumnDimension('A')->setWidth(32);
    $sheet->getColumnDimension('A')->setWidth(11);
    $sheet->getColumnDimension('B')->setWidth(32);
    //$sheet->getColumnDimension('B')->setWidth(11);
    $sheet->getColumnDimension('C')->setWidth(10);
    $sheet->getColumnDimension('D')->setWidth(5);
    $sheet->getColumnDimension('E')->setWidth(7);
    $sheet->getColumnDimension('F')->setWidth(12);
    $sheet->getColumnDimension('G')->setWidth(12);

    $default_border = array(
        'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
        'color' => array('rgb' => 'BEBEBE')
    );
    $border_color = 'dddddd';
    $styles_array = array(
        'standard' => array(
            'borders' => array(
                'allborders' => array(
                    'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
                    'color' => array('rgb' => $border_color)
                )
            )
        ),
        'header_orange' => array(
            'borders' => array(
                'allborders' => array(
                    'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
                    'color' => array('rgb' => $border_color)
                )
            ),
            'fill' => array(
                'fillType' => PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
                'color' => array('rgb' => str_replace('#', '', $color_speisen[$speise_nr + 4])),
            ),
            'font' => array(
                'bold' => true,
                'color' => array('rgb' => 'ffffff'),
                'size' => 14
            ),
            'alignment' => array(
                'horizontal' => PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER,
                'vertical' => PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER,
                'indent' => 0.5,
                'wrapText' => true
            )
        ),
        'header_dark' => array(
            'borders' => array(
                'allborders' => array(
                    'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
                    'color' => array('rgb' => $border_color)
                )
            ),
            'fill' => array(
                'fillType' => PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
                'color' => array('rgb' => str_replace('#', '', $color_speisen[$speise_nr + 4])),
            ),
            'font' => array(
                'bold' => true,
                'color' => array('rgb' => '000000'),
                'size' => 14
            ),
            'alignment' => array(
                'horizontal' => PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER,
                'vertical' => PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER,
                'indent' => 0.5,
                'wrapText' => true
            )
        ),
        'header_orange2' => array(
            'borders' => array(
                'allborders' => array(
                    'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
                    'color' => array('rgb' => $border_color)
                )
            ),
            'fill' => array(
                'fillType' => PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
                'color' => array('rgb' => str_replace('#', '', $color_speisen[$speise_nr])),
            ),
            'font' => array(
                'bold' => true,
                'color' => array('rgb' => '000000'),
                'size' => 14
            ),
            'alignment' => array(
                'horizontal' => PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER,
                'vertical' => PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER,
                'indent' => 0.5,
                'wrapText' => true
            )
        ),
        'row_gesamt' => array(
            'borders' => array(
                'allborders' => array(
                    'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
                    'color' => array('rgb' => $border_color)
                )
            ),
            'fill' => array(
                'fillType' => PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
                'color' => array('rgb' => 'C3C2D9'),
            ),
            'font' => array(
                'bold' => true,
                'color' => array('rgb' => '000000'),
                'size' => 12
            ),
            'alignment' => array(
                'horizontal' => PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER,
                'indent' => 0.5,
            )
        ),
        'row_1' => array(
            'borders' => array(
                'allborders' => array(
                    'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
                    'color' => array('rgb' => $border_color)
                )
            ),
            'fill' => array(
                'fillType' => PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
                'color' => array('rgb' => 'FFFFFF'),
            ),
            'font' => array(
                'bold' => true,
                'color' => array('rgb' => '000000')
            ),
            'alignment' => array(
                'horizontal' => PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER,
                'vertical' => PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_TOP,
                'indent' => 0.5,
                'wrapText' => true,
            )
        ),
        'row_2' => array(
            'borders' => array(
                'allborders' => array(
                    'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
                    'color' => array('rgb' => $border_color)
                )
            ),
            'fill' => array(
                'fillType' => PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
                'color' => array('rgb' => 'E1E0F7'),
            ),
            'font' => array(
                'bold' => true,
                'color' => array('rgb' => '000000')
            ),
            'alignment' => array(
                'horizontal' => PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER,
                'vertical' => PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_TOP,
                'indent' => 0.5,
                'wrapText' => true
            )
        ),
        'tour' => array(
            'borders' => array(
                'allborders' => array(
                    'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
                    'color' => array('rgb' => $border_color)
                )
            ),
            'fill' => array(
                'fillType' => PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
                'color' => array('rgb' => 'dddddd'),
            ),
            'font' => array(
                'bold' => true,
                'color' => array('rgb' => '888888'),
                'size' => 16
            ),
            'alignment' => array(
                'horizontal' => PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER,
                'vertical' => PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_TOP,
                'indent' => 0.5,
                'wrapText' => true
            )
        ),
        'bg_grau' => array(
            'fill' => array(
                'fillType' => PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
                'color' => array('rgb' => 'dddddd'),
            ),
            'font' => array(
                'color' => array('rgb' => '888888')
            )
        ),
        'bg_gelb' => array(
            'fill' => array(
                'fillType' => PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
                'color' => array('rgb' => 'F8F87F'),
            )
        ),
        'bg_rot' => array(
            'fill' => array(
                'fillType' => PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
                'color' => array('rgb' => 'FF5050'),
            )
        ),
        'null_portionen' => array(
            'font' => array(
                'color' => array('rgb' => '888888')
            ),
            'alignment' => array(
                'indent' => 1
            )
        ),
        'tourtrenner' => array(
            'font' => array(
                'bold' => true,
                'size' => 13,
                'color' => array('rgb' => '444444')
            ),
            'alignment' => array(
                'wrap' => true
            ),
            'fill' => array(
                'fillType' => PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
                'color' => array('rgb' => 'CAE2FF'),
            )
        ),
        'handtext' => array(
            'borders' => array(
                'top' => array(),
                'left' => array(),
                'bottom' => array(
                    'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_DOTTED,
                    'color' => array('rgb' => '444444')
                ),
                'right' => array(),
            )
        )
    );

    // $sheet->getColumnDimension('A')->setWidth(32);
    $sheet->getColumnDimension('A')->setWidth(8);
    //$sheet->getColumnDimension('B')->setWidth(11);
    $sheet->getColumnDimension('B')->setWidth(32);
    $sheet->getColumnDimension('C')->setWidth(13);
    $sheet->getColumnDimension('D')->setWidth(5);
    $sheet->getColumnDimension('E')->setWidth(7);
    $sheet->getColumnDimension('F')->setWidth(12);
    $sheet->getColumnDimension('G')->setWidth(12);

    $datum_tag_string = $daten_info['tag_string'] . ' ' . $daten_info['datum'];
    $erstellt = $daten_info['erstellt'];

    $i = 1;
    /* echo '<pre>';
      var_dump($daten_tagesaufstellung);
      echo '</pre>'; */

    $deckblatt_nr = 1;

    if ($daten_info['speise_nr'] > 1) {
        $sheet->mergeCells("A$i:G$i");
        $sheet->getStyle("A$i:G$i")->applyFromArray($styles_array['row_1']);
        $sheet->setCellValue("A$i", 'Deckblatt ' . $deckblatt_nr);
        $i++;
        $i = erzeugeDeckblatt($sheet, $daten, $i, $styles_array); //Deckblatt 1
        $i++;

        $sheet->mergeCells("A$i:G$i");
        $sheet->getStyle("A$i:G$i")->applyFromArray($styles_array['row_1']);
        $sheet->setCellValue("A$i", 'ENDE Deckblatt ' . $deckblatt_nr);
        $i++;

        $deckblatt_nr++;

        $break_before_line = $i - 1;
        $break_before_line_str = "A" . $break_before_line;
        $sheet->setBreak($break_before_line_str, PhpOffice\PhpSpreadsheet\Worksheet\Worksheet::BREAK_ROW);
    }

    $seitenzahl = 1;
    foreach ($daten_tagesaufstellung as $tour_key => $data) {
        $tour_key_arr = explode('-', $tour_key);
        $tour_id = trim($tour_key_arr[0]);
        $tour_name = trim($tour_key_arr[1]);

        $style = 'header_orange2';

        if ($tour_name === 'BEGINN PRODUKTION 1' || $tour_name === 'BEGINN PRODUKTION 2') {
            $break_before_line = $i - 1;
            $break_before_line_str = "A" . $break_before_line;
            $sheet->setBreak($break_before_line_str, PhpOffice\PhpSpreadsheet\Worksheet\Worksheet::BREAK_ROW);

            $sheet->mergeCells("A$i:G$i");
            $sheet->getStyle("A$i:G$i")->applyFromArray($styles_array['row_1']);
            $sheet->setCellValue("A$i", 'Deckblatt ' . $deckblatt_nr);
            $i++;

            $i = erzeugeDeckblatt($sheet, $daten, $i, $styles_array); //Deckblatt 1
            $i++;

            $i = erzeugeCheckliste($sheet, $daten, $i, $styles_array); //Deckblatt 1
            $i++;

            $sheet->mergeCells("A$i:G$i");
            $sheet->getStyle("A$i:G$i")->applyFromArray($styles_array['row_1']);
            $sheet->setCellValue("A$i", 'ENDE Deckblatt ' . $deckblatt_nr);
            $i++;

            $break_before_line = $i - 1;
            $break_before_line_str = "A" . $break_before_line;
            $sheet->setBreak($break_before_line_str, PhpOffice\PhpSpreadsheet\Worksheet\Worksheet::BREAK_ROW);

            $sheet->mergeCells("A$i:G$i");
            $sheet->getStyle("A$i:G$i")->applyFromArray($styles_array['row_1']);
            $sheet->setCellValue("A$i", 'SEITE ' . $seitenzahl);
            // $seitenzahl++;
            $i++;

            $sheet->getStyle("A$i:G$i")->getFont()->setBold(true);
            $sheet->getStyle("A$i:G$i")->getFont()->setSize(14);
            $sheet->mergeCells("B$i:G$i");
            $sheet->setCellValue("A$i", $daten_info['tag_string'] . ' ' . $daten_info['datum']);
            $sheet->setCellValue("B$i", ' Erstellt: ' . $daten_info['erstellt']);
            $i++;

            $sheet->getStyle("A$i:G$i")->applyFromArray($styles_array['header_orange2']);
            $sheet->mergeCells("A$i:G$i");
            $sheet->setCellValue("A$i", $daten_info['speise']);
            $i++;

            $style = 'tour';
        } elseif ($speise_nr) {
            $break_before_line = $i - 1;
            $break_before_line_str = "A" . $break_before_line;
            $sheet->setBreak($break_before_line_str, PhpOffice\PhpSpreadsheet\Worksheet\Worksheet::BREAK_ROW);

            $sheet->mergeCells("A$i:G$i");
            $sheet->getStyle("A$i:G$i")->applyFromArray($styles_array['row_1']);
            $sheet->setCellValue("A$i", 'SEITE ' . $seitenzahl);
            $i++;
        }


        //$sheet->getStyle("A$i:G$i")->applyFromArray($styles_array[$style]);
        $sheet->getStyle("B$i:G$i")->applyFromArray($styles_array[$style]);
        $sheet->getStyle("E$i:G$i")->getFont()->setSize(12);
        //$sheet->mergeCells("A$i:D$i");
        $sheet->mergeCells("B$i:D$i");
        $sheet->mergeCells("E$i:G$i");
        //$sheet->setCellValue("A$i", $daten_info['speise']);
        $sheet->setCellValue("B$i", $daten_info['speise']);
        $sheet->getStyle("E$i")->applyFromArray($styles_array[$style]);
        $sheet->setCellValue("E$i", 'Speise ' . $speise_nr);
        $i++;

        //$sheet->getStyle("A$i:G$i")->applyFromArray($styles_array[$style]);
        $sheet->getStyle("B$i:G$i")->applyFromArray($styles_array[$style]);
        $sheet->getStyle("E$i:G$i")->getFont()->setSize(12);
        $sheet->mergeCells("B$i:D$i");
        //$sheet->mergeCells("A$i:D$i");
        $sheet->mergeCells("E$i:G$i");
        $sheet->setCellValue("B$i", $tour_name . ' ' . $datum_tag_string);
        // $sheet->setCellValue("A$i", $tour_name . ' ' . $datum_tag_string);
        $sheet->getStyle("E$i")->applyFromArray($styles_array['bg_grau']);
        $sheet->setCellValue("E$i", $erstellt);
        $i++;

        $sheet->setCellValue("A$i", 'Notiz');
        //  $sheet->setCellValue("A$i", 'Kunde');
        // $sheet->setCellValue("B$i", 'Gesamt');
        $sheet->setCellValue("B$i", 'Kunde');
        $sheet->setCellValue("C$i", 'Gesamt');
        $sheet->setCellValue("D$i", 'Port.');
        $sheet->setCellValue("E$i", 'pro P..');
        $sheet->setCellValue("F$i", 'Kundeninfo');
        $sheet->setCellValue("G$i", 'Notiz Speise');
        $i++;

        //tourtrenner zu tour
        $sheet->getStyle("B$i:G$i")->getFont()->setSize(12);
        $sheet->getStyle("B$i:G$i")->applyFromArray($styles_array['row_1']);
        $sheet->getStyle("B$i:G$i")->applyFromArray($styles_array['tourtrenner']);
        $tour_trenner = $kundeVerwaltung->findeTourtrennerZuTour($tour_id);
        $sheet->mergeCells("B$i:E$i");
        $sheet->setCellValue("B$i", $tour_trenner->getName());
        $sheet->mergeCells("F$i:G$i");
        $sheet->setCellValue("F$i", $tour_trenner->getBemerkungKunde());
        $i++;

        $data_kunden = $data['nach_kunden'];
        foreach ($data_kunden as $kunden_id => $data_kunde) {
            $bemerkung_kunde = $data_kunde['kundeninfo'];
            $bemerkung_kunde = str_replace('+sauber', '', $bemerkung_kunde);
            $bemerkung_kunde = str_replace('+ sauber', '', $bemerkung_kunde);
            $bemerkung_kunde = str_replace('sauber', '', $bemerkung_kunde);
            $bemerkung_kunde = str_replace('Wärmeplatten', 'Wärmeplatte', $bemerkung_kunde);
            $bemerkung_kunde = str_replace('+ Wärmeplatte', 'W', $bemerkung_kunde);
            $bemerkung_kunde = str_replace('+Wärmeplatte', 'W', $bemerkung_kunde);
            $bemerkung_kunde = str_replace('Wärmeplatte', 'W', $bemerkung_kunde);
            $bemerkung_kunde = str_replace('+ Vegi', 'V', $bemerkung_kunde);
            $bemerkung_kunde = str_replace('Vegi', 'V', $bemerkung_kunde);
            $bemerkung_kunde = str_replace('+ Diät', 'D', $bemerkung_kunde);
            $bemerkung_kunde = str_replace('Diät', 'D', $bemerkung_kunde);
            /*    echo '<pre>';
              var_dump($data_kunde);
              echo '</pre>'; */
            if ($i % 2) {
                $sheet->getStyle("A$i:G$i")->applyFromArray($styles_array['row_1']);
            } else {
                $sheet->getStyle("A$i:G$i")->applyFromArray($styles_array['row_2']);
            }
            if ($data_kunde['portionen'] == 0) {
                $sheet->getStyle("B$i:G$i")->applyFromArray($styles_array['null_portionen']);
            }
            if ($bemerkung_kunde && $bemerkung_kunde != 'W') {
                $sheet->getStyle("F$i")->applyFromArray($styles_array['bg_gelb']);
            }

            if ($data_kunde['notiz_speise'] && $data_kunde['portionen']) {
                $sheet->getStyle("B$i:G$i")->applyFromArray($styles_array['bg_rot']);
            }

            $sheet->getStyle("B$i")->getAlignment()->applyFromArray(
                array('horizontal' => PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_LEFT)
            );
            $sheet->getStyle("B$i:B$i")->getFont()->setSize(15);
            $sheet->setCellValue("B$i", $data_kunde['name']);
            $sheet->setCellValue("C$i", $data_kunde['menge']);
            $sheet->getStyle("C$i")->getFont()->setSize(16);
            //$sheet->setCellValue("C$i", $data_kunde['diets']);
            $sheet->getStyle("D$i:E$i")->getFont()->setSize(8);
            $sheet->setCellValue("D$i", $data_kunde['portionen']);
            $sheet->setCellValue("E$i", $data_kunde['menge_pro_portion']);
            $sheet->getStyle("F$i:G$i")->getFont()->setSize(12);
            $sheet->setCellValue("F$i", $bemerkung_kunde);
            $sheet->setCellValue("G$i", $data_kunde['notiz_speise']);
            $i++;
        }

        if ($data_kunde['menge_einheit'] == 'Blech') {

            $sheet->getStyle("B$i:G$i")->applyFromArray($styles_array['row_gesamt']);
            $sheet->getStyle("B$i:G$i")->getFont()->setSize(16);

            $sheet->setCellValue("B$i", 'GESAMT');
            $sheet->mergeCells("C$i:G$i");
            $sheet->setCellValue("C$i", $daten_tagesaufstellung[$tour_key]['tour_gesamt']['menge'] . ' Blech');
            $i++;
            /*  echo '<pre>';
              var_dump($tour_key);
              var_dump($daten_tagesaufstellung[$tour_key]['tour_gesamt']['menge']);
              echo '</pre>'; */
        }

        $sheet->mergeCells("B$i:G$i");
        $sheet->getStyle("B$i:G$i")->applyFromArray($styles_array['row_1']);
        $sheet->setCellValue("B$i", 'ENDE SEITE ' . $seitenzahl);
        $seitenzahl++;
        $i++;
    }

    $writer = PhpOffice\PhpSpreadsheet\IOFactory::createWriter($xls, "Xls");
    $heute = strftime('%d_%m_%Y', time());
    $rechner = php_uname('n');
    $speise_bezeichnung = $daten_info['speise'];
    $speise_bezeichnung = umlautepas($speise_bezeichnung);
    $speise_bezeichnung = entferneSonderzeichen($speise_bezeichnung);
    if ($_SESSION['is_local_server']) {
        $writer->save('export_tagesaufstellungen/' . $speise_bezeichnung . '_' . $speise_nr . '_' . strftime('%d_%m_%y', $ts) . ".xls");
    } else {
        $writer->save('/is/htdocs/wp1055935_1F55KTNHIC/www/sbar/verwaltung/export_tagesaufstellungen/' . $speise_bezeichnung . '_' . $speise_nr . '_' . strftime('%d_%m_%y', $ts) . ".xls");
    }
    if ($_SESSION['is_local_server']) {
        header('location:export_tagesaufstellungen/' . $speise_bezeichnung . '_' . $speise_nr . '_' . strftime('%d_%m_%y', $ts) . ".xls");
        exit;
    } else {
        header('location:http://www.s-bar.net/verwaltung/export_tagesaufstellungen/' . $speise_bezeichnung . '_' . $speise_nr . '_' . strftime('%d_%m_%y', $ts) . ".xls");
        exit;
    }

    /* echo '<pre>';
      var_dump($daten_info);
      echo '</pre>';
      exit; */
}





function erzeugeTagesaufstellungExcelMitDeckblatt2($tag, $monat, $jahr, $starttag, $startmonat, $startjahr, $speise_id, $bestellung, $bestellung_has_speiseVerwaltung, $speiseVerwaltung, $menge_pro_portionVerwaltung, $standardportionenVerwaltung, $portionenaenderungVerwaltung, $kunden, $indifaktorVerwaltung, $bemerkungzutagVerwaltung, $bemerkungzuspeiseVerwaltung, $speise_nr = 1, $kundeVerwaltung = NULL, $color_speisen = array()) {


    require_once('../verwaltung_alt/PHPExcel-1.7.5/PHPExcel.php');
    require_once('../verwaltung_alt/PHPExcel-1.7.5/PHPExcel/IOFactory.php');

    $ts = mktime(12, 0, 0, $monat, $tag, $jahr);
    // neue instanz erstellen
    $xls = new PHPExcel();
    // dokumenteigenschaften schreiben
    $xls->getProperties()->setCreator("S-Bar")
        ->setLastModifiedBy("S-Bar")
        ->setTitle("Tagesaufstellung " . strftime('%d.%m.%y', $ts))
        ->setSubject("Tagesaufstellung " . strftime('%d.%m.%y', $ts));
    // das erste worksheet anwaehlen
    $sheet = $xls->setActiveSheetIndex(0);

    //STYLES START
    $sheet->getStyle('A1')->getFont()->setBold(true);
    $sheet->getStyle('A2:D2')->getFont()->setBold(true);

    $sheet->getColumnDimension('A')->setAutoSize(false);

    $default_border = array(
        'style' => PHPExcel_Style_Border::BORDER_THIN,
        'color' => array('rgb' => 'BEBEBE')
    );
    $style_header_1 = array(
        'borders' => array(
            'bottom' => $default_border,
            'left' => $default_border,
            'top' => $default_border,
            'right' => $default_border,
        ),
        'fill' => array(
            'type' => PHPExcel_Style_Fill::FILL_SOLID,
            'color' => array('rgb' => '' . str_replace('#', '', $color_speisen[$speise_nr])),
        ),
        'font' => array(
            'bold' => true,
            'color' => array('rgb' => 'ffffff'),
        ),
        'alignment' => array(
            'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER,
            'indent' => 0.5,
        )
    );
    $style_header = array(
        'borders' => array(
            'bottom' => $default_border,
            'left' => $default_border,
            'top' => $default_border,
            'right' => $default_border,
        ),
        'fill' => array(
            'type' => PHPExcel_Style_Fill::FILL_SOLID,
            'color' => array('rgb' => '' . str_replace('#', '', $color_speisen[$speise_nr + 4])),
        ),
        'font' => array(
            'bold' => true,
            'color' => array('rgb' => 'ffffff'),
        ),
        'alignment' => array(
            'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER,
            'indent' => 0.5,
        )
    );
    $style_row = array(
        'borders' => array(
            'bottom' => $default_border,
            'left' => $default_border,
            'top' => $default_border,
            'right' => $default_border,
        ),
        'fill' => array(
            'type' => PHPExcel_Style_Fill::FILL_SOLID,
            'color' => array('rgb' => 'E1E0F7'),
        ),
        'font' => array(
            'bold' => true,
            'color' => array('rgb' => '000000')
        ),
        'alignment' => array(
            'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER,
            'vertical' => PHPExcel_Style_Alignment::VERTICAL_TOP,
            'indent' => 0.5,
            'wrapText' => true,
        )
    );
    $style_row_2 = array(
        'borders' => array(
            'bottom' => $default_border,
            'left' => $default_border,
            'top' => $default_border,
            'right' => $default_border,
        ),
        'fill' => array(
            'type' => PHPExcel_Style_Fill::FILL_SOLID,
            'color' => array('rgb' => 'FFFFFF'),
        ),
        'font' => array(
            'bold' => true,
            'color' => array('rgb' => '000000')
        ),
        'alignment' => array(
            'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER,
            'vertical' => PHPExcel_Style_Alignment::VERTICAL_TOP,
            'indent' => 0.5,
            'wrap' => true,
        )
    );
    $style_row_durchgestr = array(
        'borders' => array(
            'bottom' => $default_border,
            'left' => $default_border,
            'top' => $default_border,
            'right' => $default_border,
        ),
        'fill' => array(
            'type' => PHPExcel_Style_Fill::FILL_SOLID,
            'color' => array('rgb' => 'E1E0F7'),
        ),
        'font' => array(
            'bold' => true,
            'strikethrough' => true,
            'color' => array('rgb' => '888888'),
        ),
        'alignment' => array(
            'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER,
            'vertical' => PHPExcel_Style_Alignment::VERTICAL_TOP,
            'indent' => 0.5,
        )
    );
    $style_row_durchgestr_2 = array(
        'borders' => array(
            'bottom' => $default_border,
            'left' => $default_border,
            'top' => $default_border,
            'right' => $default_border,
        ),
        'fill' => array(
            'type' => PHPExcel_Style_Fill::FILL_SOLID,
            'color' => array('rgb' => 'FFFFFF'),
        ),
        'font' => array(
            'bold' => true,
            'strikethrough' => true,
            'color' => array('rgb' => '888888'),
        ),
        'alignment' => array(
            'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER,
            'vertical' => PHPExcel_Style_Alignment::VERTICAL_TOP,
            'indent' => 0.5,
        )
    );
    $style_row_gesamt = array(
        'borders' => array(
            'bottom' => $default_border,
            'left' => $default_border,
            'top' => $default_border,
            'right' => $default_border,
        ),
        'fill' => array(
            'type' => PHPExcel_Style_Fill::FILL_SOLID,
            'color' => array('rgb' => 'C3C2D9'),
        ),
        'font' => array(
            'bold' => true,
            'color' => array('rgb' => '000000')
        ),
        'alignment' => array(
            'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER,
            'indent' => 0.5,
        )
    );
    $style_col1 = array(
        'borders' => array(
            'bottom' => $default_border,
            'left' => $default_border,
            'top' => $default_border,
            'right' => $default_border,
        ),
        'fill' => array(
            'type' => PHPExcel_Style_Fill::FILL_SOLID,
            'color' => array('rgb' => 'E1E0F7'),
        ),
        'font' => array(
            'bold' => true,
            'color' => array('rgb' => '000000')
        ),
        'alignment' => array(
            'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_LEFT,
            'vertical' => PHPExcel_Style_Alignment::VERTICAL_TOP,
            'indent' => 0.5,
            'wrapText' => true,
        )
    );
    $style_col1_2 = array(
        'borders' => array(
            'bottom' => $default_border,
            'left' => $default_border,
            'top' => $default_border,
            'right' => $default_border,
        ),
        'fill' => array(
            'type' => PHPExcel_Style_Fill::FILL_SOLID,
            'color' => array('rgb' => 'FFFFFF'),
        ),
        'font' => array(
            'bold' => true,
            'color' => array('rgb' => '000000')
        ),
        'alignment' => array(
            'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_LEFT,
            'vertical' => PHPExcel_Style_Alignment::VERTICAL_TOP,
            'indent' => 0.5,
        )
    );

    $style_head_summary = array(
        'borders' => array(
            'bottom' => $default_border,
            'left' => $default_border,
            'top' => array(
                'style' => PHPExcel_Style_Border::BORDER_THICK,
                'color' => array('rgb' => 'ffffff')
            ),
            'right' => $default_border,
        ),
        'fill' => array(
            'type' => PHPExcel_Style_Fill::FILL_SOLID,
            'color' => array('rgb' => 'EC5006'),
        ),
        'font' => array(
            'bold' => true,
            'color' => array('rgb' => 'ffffff'),
            'size' => 18
        ),
        'alignment' => array(
            'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER,
            'vertical' => PHPExcel_Style_Alignment::VERTICAL_TOP,
            'indent' => 0.5,
        )
    );
    $style_tour = array(
        'borders' => array(
            'bottom' => $default_border,
            'left' => $default_border,
            'top' => array(
                'style' => PHPExcel_Style_Border::BORDER_MEDIUM,
                'color' => array('rgb' => 'ffffff')
            ),
            'right' => $default_border,
        ),
        'fill' => array(
            'type' => PHPExcel_Style_Fill::FILL_SOLID,
            'color' => array('rgb' => 'dddddd'),
        ),
        'font' => array(
            'bold' => true,
            'color' => array('rgb' => '888888'),
            'size' => 16
        ),
        'alignment' => array(
            'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER,
            'vertical' => PHPExcel_Style_Alignment::VERTICAL_TOP,
            'indent' => 0.5,
        )
    );
    $format_bold = array(
        'font' => array(
            'bold' => true
        )
    );
    $style_handtext = array(
        'borders' => array(
            'top' => array(),
            'left' => array(),
            'bottom' => array(
                'style' => PHPExcel_Style_Border::BORDER_DOTTED,
                'color' => array('rgb' => '444444')
            ),
            'right' => array(),
        )
    );
    $sheet->getPageMargins()->setTop(0.5);
    $sheet->getPageMargins()->setRight(0.5);
    $sheet->getPageMargins()->setLeft(0.5);
    $sheet->getPageMargins()->setBottom(0.5);
    $sheet->getPageSetup()->setOrientation(PHPExcel_Worksheet_PageSetup::ORIENTATION_PORTRAIT);
    $sheet->getPageSetup()->setPaperSize(PHPExcel_Worksheet_PageSetup::PAPERSIZE_A4);
    /* $sheet->getPageSetup()->setFitToPage(true);
      $sheet->getPageSetup()->setFitToWidth(1);
      $sheet->getPageSetup()->setFitToHeight(0); */

    $sheet->getStyle('A3:G3')->applyFromArray($style_header_1);
    $sheet->getStyle('A4:G4')->applyFromArray($style_header);
    //STYLES ENDE
    $i = 5;
    $x = $i - 1;

    // den namen vom Worksheet 1 definieren
    $xls->getActiveSheet()->setTitle("Tagesaufstellung " . strftime('%d.%m.%y', $tag));
    $i = 5;
    $bestellung_has_speise_array = $bestellung_has_speiseVerwaltung->findeAnhandVonBestellungId($bestellung->getId());

    $gesamtmenge_tag_speise = 0;
    $gesamtmenge_tag_portionen = 0;
    $c = $i - 2;
    $d = $i - 1;
    $sheet->getStyle("A$d:F$d")->getFont()->setSize(15);
    $sheet->getStyle("A$c:E$c")->getFont()->setSize(15);

    $speise = $speiseVerwaltung->findeAnhandVonId($speise_id);
    $sheet->setCellValue("A$c", $speise->getBezeichnung());
    // den wert test in das Feld A1 schreiben
    $sheet->setCellValue("A$d", "Kunde", $format_bold);
    $sheet->setCellValue("D$d", "Port.");
    $sheet->setCellValue("E$d", "pro P.");
    $sheet->setCellValue("B$d", "Gesamt");
    $sheet->setCellValue("C$d", "Diäten");
    $sheet->getStyle("F$d")->getFont()->setSize(12);
    $sheet->setCellValue("F$d", "Kundeninfo");
    $sheet->setCellValue("G$d", "Notiz Speise");
    // $sheet->setCellValue("E$d","Menge/P Standard");
    //$sheet->setCellValue("F$d","Faktor");
    $zeilen_mit_summe = array();

    $zeile_tour_beginn = array();
    $portionen_zu_tour = array();
    $menge_diese_tour = 0;
    $portionen_diese_tour = 0;

    $zusammenfassung_nach_touren_array = array();

    $last_tour = new Kunde();
    $count_k = 1;
    $anzahl_kunden = count($kunden);
    /* echo '<pre>';
      var_dump($kunden);
      echo '</pre>'; */
    $uebersicht_array = array();
    foreach ($kunden as $kunde) {

        $einrichtungskategorie_id = $kunde->getEinrichtungskategorieId();

        $tour_id = $kunde->getTourId();
        if ($einrichtungskategorie_id == 5) {
            $tour_id = $kunde->getId();
        }
        $tour_zu_kunde = $kundeVerwaltung->findeAnhandVonId($tour_id);

        if (!$tour_zu_kunde->getId()) {
            $tour_zu_kunde = new Kunde();
        }


        $count_k++;
        $last_tour = $tour_zu_kunde;
        //}


        if ($i % 2) {
            $sheet->getStyle("A$i")->applyFromArray($style_col1);
            $sheet->getStyle("B$i")->applyFromArray($style_row);
            $sheet->getStyle("C$i")->applyFromArray($style_row);
            $sheet->getStyle("D$i")->applyFromArray($style_row);
            $sheet->getStyle("E$i")->applyFromArray($style_row);
            $sheet->getStyle("F$i")->applyFromArray($style_row);
            $sheet->getStyle("G$i")->applyFromArray($style_row);
            $gelb = 'E1E061';
            $blau = '78D1E1';
            $orange = 'EB8065';
        } else {
            $sheet->getStyle("A$i")->applyFromArray($style_col1_2);
            $sheet->getStyle("B$i")->applyFromArray($style_row_2);
            $sheet->getStyle("C$i")->applyFromArray($style_row_2);
            $sheet->getStyle("D$i")->applyFromArray($style_row_2);
            $sheet->getStyle("E$i")->applyFromArray($style_row_2);
            $sheet->getStyle("F$i")->applyFromArray($style_row_2);
            $sheet->getStyle("G$i")->applyFromArray($style_row_2);
            $gelb = 'FFFF83';
            $blau = '96EFFF';
            $orange = 'FFB297';
        }

        $sheet->getStyle("A$i:F$i")->getFont()->setSize(15);
        $sheet->getStyle("E$i")->getFont()->setSize(8);
        $sheet->getStyle("D$i")->getFont()->setSize(8);
        $sheet->getStyle("C$i")->getFont()->setSize(12);
        $sheet->getStyle("F$i")->getFont()->setSize(12);
        $sheet->getStyle("G$i")->getFont()->setSize(12);
        // $sheet->getStyle("E$i")->applyFromArray( $style_row );
        //$sheet->getStyle("F$i")->applyFromArray( $style_row );
        $speise_id = $speise->getId();
        $indi_faktor = $indifaktorVerwaltung->findeAnhandVonSpeiseIdUndKundeId($speise_id, $kunde->getId());
        $faktor = $indi_faktor->getFaktor();
        $menge_pro_portion = $menge_pro_portionVerwaltung->findeAnhandVonSpeiseIdUndEinrichtungskategorieId($speise_id, $einrichtungskategorie_id);

        $standardportionen = $standardportionenVerwaltung->findeAnhandVonKundenIdUndSpeiseNr($kunde->getId(), $speise_nr);
        $portionenaenderung = $portionenaenderungVerwaltung->findeAnhandVonKundenIdUndWochenstartdatum($kunde->getId(), $starttag, $startmonat, $startjahr, $speise_nr);

        if ($portionenaenderung->getId()) {
            $portionen_mo = $portionenaenderung->getPortionenMo();
            $portionen_di = $portionenaenderung->getPortionenDi();
            $portionen_mi = $portionenaenderung->getPortionenMi();
            $portionen_do = $portionenaenderung->getPortionenDo();
            $portionen_fr = $portionenaenderung->getPortionenFr();
            $aenderung = true;
        } else {
            $portionen_mo = $standardportionen->getPortionenMo();
            $portionen_di = $standardportionen->getPortionenDi();
            $portionen_mi = $standardportionen->getPortionenMi();
            $portionen_do = $standardportionen->getPortionenDo();
            $portionen_fr = $standardportionen->getPortionenFr();
            $aenderung = false;
        }

        $wochentag_string = strftime('%a', mktime(12, 0, 0, $monat, $tag, $jahr));
        switch ($wochentag_string) {
            case 'Mo':
                $portionen = $portionen_str = $portionen_mo;
                if ($portionen_mo != $standardportionen->getPortionenMo()) {
                    //$portionen .= ' (' . $standardportionen->getPortionenMo() . ')';
                    $portionen_str .= ' (' . $standardportionen->getPortionenMo() . ')';
                }
                break;
            case 'Di':
                $portionen = $portionen_str = $portionen_di;
                if ($portionen_di != $standardportionen->getPortionenDi()) {
                    // $portionen .= ' (' . $standardportionen->getPortionenDi() . ')';
                    $portionen_str .= ' (' . $standardportionen->getPortionenDi() . ')';
                }
                break;
            case 'Mi':
                $portionen = $portionen_str = $portionen_mi;
                if ($portionen_mi != $standardportionen->getPortionenMi()) {
                    // $portionen .= ' (' . $standardportionen->getPortionenMi() . ')';
                    $portionen_str .= ' (' . $standardportionen->getPortionenMi() . ')';
                }
                break;
            case 'Do':
                $portionen = $portionen_str = $portionen_do;
                if ($portionen_do != $standardportionen->getPortionenDo()) {
                    //  $portionen .= ' (' . $standardportionen->getPortionenDo() . ')';
                    $portionen_str .= ' (' . $standardportionen->getPortionenDo() . ')';
                }
                break;
            case 'Fr':
                $portionen = $portionen_str = $portionen_fr;
                if ($portionen_fr != $standardportionen->getPortionenFr()) {
                    //   $portionen .= ' (' . $standardportionen->getPortionenFr() . ')';
                    $portionen_str .= ' (' . $standardportionen->getPortionenFr() . ')';
                }
                break;
        }

        $gesamtmenge_tag_portionen += $portionen;
        $gesamtmenge_kunde = $portionen * ($menge_pro_portion->getMenge() * $faktor);

        if ($portionen == 0) {
            if ($i % 2) {
                $sheet->getStyle("A$i")->applyFromArray($style_row_durchgestr);
                $sheet->getStyle("B$i")->applyFromArray($style_row_durchgestr);
                $sheet->getStyle("C$i")->applyFromArray($style_row_durchgestr);
                $sheet->getStyle("D$i")->applyFromArray($style_row_durchgestr);
                $sheet->getStyle("E$i")->applyFromArray($style_row_durchgestr);
                $sheet->getStyle("F$i")->applyFromArray($style_row_durchgestr);
                $sheet->getStyle("G$i")->applyFromArray($style_row_durchgestr);
            } else {
                $sheet->getStyle("A$i")->applyFromArray($style_row_durchgestr_2);
                $sheet->getStyle("B$i")->applyFromArray($style_row_durchgestr_2);
                $sheet->getStyle("C$i")->applyFromArray($style_row_durchgestr_2);
                $sheet->getStyle("D$i")->applyFromArray($style_row_durchgestr_2);
                $sheet->getStyle("E$i")->applyFromArray($style_row_durchgestr_2);
                $sheet->getStyle("F$i")->applyFromArray($style_row_durchgestr_2);
                $sheet->getStyle("G$i")->applyFromArray($style_row_durchgestr_2);
            }
        }
        if ($kunde->getEinrichtungskategorieId() == 5) {
            $zusammenfassung_nach_touren_array[$kunde->getName()] = array();
            $tour_name = $kunde->getName();

            $tourbeginn_zeile = $i;
            $menge_diese_tour = 0;
            $portionen_diese_tour = 0;
            $portionen = 0;

            $break_before_line = $i - 1;
            $break_before_line_str = "A" . $break_before_line;
            if ($kunde->getId() == 642 || $kunde->getId() == 646) { //falls PRODUKTION 1 oder 2
                $sheet->setBreak($break_before_line_str, PHPExcel_Worksheet::BREAK_ROW);
                $sheet->getStyle("A$i:E$i")->applyFromArray($style_tour);
            } else {
                $sheet->getStyle("A$i:G$i")->applyFromArray($style_header_1);
            }

            if ($kunde->getId() == 646) { //falls PRODUKTION 2
                $zeile_start_produktion_2 = $break_before_line;
            }
            $sheet->setCellValue("A$i", $kunde->getName() . ' ' . strftime('%a %d.%m.%Y', mktime(12, 0, 0, $monat, $tag, $jahr)));
            $sheet->mergeCells("A$i:D$i");
            $sheet->mergeCells("E$i:G$i");
            $sheet->setCellValue("E$i", 'Erstellt: ' . strftime('%a %d.%m.%Y - %H:%M Uhr', mktime(date('H'), date('i'), date('s'), date('m'), date('d'), date('Y'))));

            $sheet->getStyle("E$i")->applyFromArray($style_tour);
            $sheet->getStyle("E$i")->getFont()->setSize(11);
        } else {
            $tour_obj = $kundeVerwaltung->findeAnhandVonId($kunde->getTourId());
            $tour_name = $tour_obj->getName();
            $add_str = '';
            if ($kunde->getAnzahlSpeisen() > 1) {
                $add_str = '(Speise 1)';
                if ($speise_nr > 1) {
                    $add_str = '(Speise ' . $speise_nr . ')';
                }
            }
            $kitafino_str = '';
            if ($kunde->getKundennummer()) {
                $kitafino_str = '{K} ';
            }
            $sheet->getStyle("A$i")->getAlignment()->setWrapText(true);
            $sheet->setCellValue("A$i", $kitafino_str . $kunde->getName() . ' ' . $add_str);
            $sheet->setCellValue("D$i", $portionen_str);
            $bemerkungen_str = '';
            $sheet->setCellValue("E$i", $menge_pro_portion->getMenge() * $faktor . ' ' . $menge_pro_portion->getEinheit());

            $gerade_bleche = '';
            if ($menge_pro_portion->getEinheit() == 'Blech') {
                //BLECHE aufrunden
                $zahl = number_format(round($gesamtmenge_kunde, 2), 2);
                $array = explode(".", $zahl);
                $nachkommastellen = $array[1];

                $zahl = roundTo($zahl, .50);
                $gerade_bleche = /* ' ('. */$zahl/* .') ' */;
                $gesamtmenge_kunde = $gerade_bleche;
            }

            $sheet->setCellValue("B$i", $gesamtmenge_kunde . ' ' . $menge_pro_portion->getEinheit());

            $bemerkung_zu_tag = $bemerkungzutagVerwaltung->findeAnhandVonKundeIdUndDatumUndSpeiseId($kunde->getId(), $tag, $monat, $jahr, $speise->getId());
            $bemerkung_zu_speise = $bemerkungzuspeiseVerwaltung->findeAnhandVonKundeIdUndSpeiseId($kunde->getId(), $speise->getId());

            $zusammenfassung_nach_touren_array[$tour_name][$kunde->getName()]['portionen'] = $portionen;

            if ($bemerkung_zu_speise === NULL) {
                $bemerkung_zu_speise = new BemerkungZuSpeise();
            }
            if ($bemerkung_zu_speise->getBemerkung() != '' && $portionen > 0) {
                $zusammenfassung_nach_touren_array[$tour_name][$kunde->getName()]['notiz_speise'] = $bemerkung_zu_speise->getBemerkung();
                $bemerkungen_str .= $bemerkung_zu_speise->getBemerkung() . '; ';
            }
            if ($bemerkung_zu_tag->getBemerkung() != '') {
                $zusammenfassung_nach_touren_array[$tour_name][$kunde->getName()]['notiz_speise_tag'] = $bemerkung_zu_tag->getBemerkung();
                $bemerkungen_str .= $bemerkung_zu_tag->getBemerkung() . '; ';
            }
            if ($kunde->getBemerkung() != '') {
                $zusammenfassung_nach_touren_array[$tour_name][$kunde->getName()]['bemerkung_speisen_kunde'] = $kunde->getBemerkung();
                //$zusammenfassung_nach_touren_array[$tour_name][$kunde->getName()]['notiz_kunde'] = $kunde->getBemerkung();
                $bemerkungen_str .= $kunde->getBemerkung() . '; ';
            }
            if ($kunde->getBemerkungKunde() != '') {
                $zusammenfassung_nach_touren_array[$tour_name][$kunde->getName()]['bemerkung_kunde'] = $kunde->getBemerkungKunde();
            }

            $sheet->setCellValue("C$i", $kunde->getBemerkung());

            $bemerkung_kunde = $kunde->getBemerkungKunde();
            $bemerkung_kunde = str_replace('+sauber', '', $bemerkung_kunde);
            $bemerkung_kunde = str_replace('+ sauber', '', $bemerkung_kunde);
            $bemerkung_kunde = str_replace('sauber', '', $bemerkung_kunde);

            $bemerkung_kunde = str_replace('Wärmeplatten', 'Wärmeplatte', $bemerkung_kunde);
            $bemerkung_kunde = str_replace('+ Wärmeplatte', 'W', $bemerkung_kunde);
            $bemerkung_kunde = str_replace('+Wärmeplatte', 'W', $bemerkung_kunde);
            $bemerkung_kunde = str_replace('Wärmeplatte', 'W', $bemerkung_kunde);
            $bemerkung_kunde = str_replace('+ Vegi', 'V', $bemerkung_kunde);
            $bemerkung_kunde = str_replace('Vegi', 'V', $bemerkung_kunde);
            $bemerkung_kunde = str_replace('+ Diät', 'D', $bemerkung_kunde);
            $bemerkung_kunde = str_replace('Diät', 'D', $bemerkung_kunde);

            $sheet->setCellValue("F$i", $bemerkung_kunde);

            $speisen_notiz = $bemerkung_zu_speise->getBemerkung();
            if ($bemerkung_zu_tag->getBemerkung()) {
                $speisen_notiz .= ' | ' . $bemerkung_zu_tag->getBemerkung();
            }

            $sheet->setCellValue("G$i", $speisen_notiz);

            if ($kunde->getBemerkung()) {
                $sheet->getStyle("C$i")->getFill()->applyFromArray(
                    array(
                        'type' => PHPExcel_Style_Fill::FILL_SOLID,
                        'startcolor' => array('rgb' => $gelb),
                        'endcolor' => array('rgb' => $gelb)
                    )
                );
            }

            $uebersicht_array[$tour_name]['gesamt_portionen'] += $portionen;
            if ($portionen) {
                $uebersicht_array[$tour_name]['kunden_einzeln'][$kunde->getName()]['portionen'] += $portionen;
            }
        }
        if (!isset($zeile_tour_beginn[$tourbeginn_zeile]) && $tourbeginn_zeile > 0) {
            $zeile_tour_beginn[$tourbeginn_zeile] = 0;
        }
        if (!isset($portionen_zu_tour[$tourbeginn_zeile]) && $tourbeginn_zeile > 0) {
            $portionen_zu_tour[$tourbeginn_zeile] = 0;
        }

        if ($tourbeginn_zeile) {
            $zeile_tour_beginn[$tourbeginn_zeile] += $gesamtmenge_kunde;
            $portionen_zu_tour[$tourbeginn_zeile] += $portionen;
        }

        $sheet->getStyle("C$i")->getAlignment()->setWrapText(true);
        $sheet->getStyle("F$i")->getAlignment()->setWrapText(true);
        $sheet->getStyle("G$i")->getAlignment()->setWrapText(true);
        //  $sheet->setCellValue("E$i",$menge_pro_portion->getMenge() . ' ' . $menge_pro_portion->getEinheit() . ' /P');
        //  $sheet->setCellValue("F$i",$faktor);

        $i++;
        $gesamtmenge_kunde = $portionen * ($menge_pro_portion->getMenge() * $faktor);

        $uebersicht_array[$tour_name]['gesamt_menge'] += $gesamtmenge_kunde;
        if ($uebersicht_array[$tour_name]['gesamt_menge'] == 0) {
            unset($uebersicht_array[$tour_name]);
        }
        $gesamtmenge_tag_speise += $gesamtmenge_kunde;
        $last_tour = $tour_zu_kunde;
    }
    $sheet->getStyle("E4")->getFont()->setSize(12);
    $sheet->getStyle("D4")->getFont()->setSize(12);

    $ttt = $i + 1;
    if ($menge_pro_portion->getEinheit() == 'g' || $menge_pro_portion->getEinheit() == 'ml') {
        $gesamtmenge_tag_speise_umger = $gesamtmenge_tag_speise / 1000;
        switch ($menge_pro_portion->getEinheit()) {
            case 'g':
                $einheit = 'kg';
                break;
            case 'ml':
                $einheit = 'L';
                break;
        }
    }

    $i++;

    $sheet->setCellValue("A2", strftime('%a %d.%m.%Y', mktime(12, 0, 0, $monat, $tag, $jahr)));
    $sheet->getStyle("A2")->getFont()->setSize(14);

    $sheet->mergeCells("B2:G2");
    $sheet->setCellValue("B2", 'Erstellt: ' . strftime('%a %d.%m.%Y - %H:%M Uhr', mktime(date('H'), date('i'), date('s'), date('m'), date('d'), date('Y'))));
    $sheet->getStyle("B2:G2")->getFont()->setSize(12);
    $sheet->getStyle('B2')->getAlignment()->applyFromArray(
        array('horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER,)
    );

    $sheet->getColumnDimension('A')->setWidth(32);
    $sheet->getColumnDimension('B')->setWidth(11);
    $sheet->getColumnDimension('C')->setWidth(8);
    $sheet->getColumnDimension('D')->setWidth(7);
    $sheet->getColumnDimension('E')->setWidth(7);
    $sheet->getColumnDimension('F')->setWidth(12);
    $sheet->getColumnDimension('G')->setWidth(12);

    //Zusammenfasung
    $i++;
    $i++;
    $i++;

    //DECKBLATT
    $anzahl_zeilen_deckblatt = count($uebersicht_array) + 7;
    $i = 1;
    $sheet->insertNewRowBefore(1, $anzahl_zeilen_deckblatt);
    $sheet->getStyle("A$i:G$i")->applyFromArray($style_header);
    $sheet->getStyle("A$i:G$i")->getFont()->setSize(14);
    $sheet->getStyle("D$i")->getFont()->setSize(12);
    $sheet->mergeCells("A$i:C$i");
    $sheet->mergeCells("D$i:G$i");
    $sheet->setCellValue("A$i", $speise->getBezeichnung() . ' - ' . $tag . '.' . $monat . '.' . $jahr);
    $sheet->setCellValue("D$i", 'Erstellt: ' . strftime('%a %d.%m.%Y - %H:%M Uhr', mktime(date('H'), date('i'), date('s'), date('m'), date('d'), date('Y'))));
    $i++;
    $sheet->getStyle("A$i:G$i")->applyFromArray($style_row_gesamt);
    $sheet->mergeCells("A$i:C$i");
    $sheet->mergeCells("D$i:E$i");
    $sheet->mergeCells("F$i:G$i");
    $sheet->setCellValue("A$i", 'TOUR');
    $sheet->setCellValue("D$i", 'MENGE');
    $sheet->setCellValue("F$i", 'PORTIONEN');
    $i++;
    $gesamtmenge_alle_touren = 0;
    $gesamtportionen_tag = 0;
    foreach ($uebersicht_array as $tour_bez => $tour_sumup) {
        $sheet->mergeCells("D$i:E$i");
        $sheet->mergeCells("F$i:G$i");

        if ($i % 2) {
            $sheet->getStyle("A$i")->applyFromArray($style_col1);
            $sheet->getStyle("B$i")->applyFromArray($style_row);
            $sheet->getStyle("C$i")->applyFromArray($style_row);
            $sheet->getStyle("D$i")->applyFromArray($style_row);
            $sheet->getStyle("E$i")->applyFromArray($style_row);
            $sheet->getStyle("F$i")->applyFromArray($style_row);
            $sheet->getStyle("G$i")->applyFromArray($style_row);
            /* $gelb = 'E1E061';
              $blau = '78D1E1';
              $orange = 'EB8065'; */
        } else {
            $sheet->getStyle("A$i")->applyFromArray($style_col1_2);
            $sheet->getStyle("B$i")->applyFromArray($style_row_2);
            $sheet->getStyle("C$i")->applyFromArray($style_row_2);
            $sheet->getStyle("D$i")->applyFromArray($style_row_2);
            $sheet->getStyle("E$i")->applyFromArray($style_row_2);
            $sheet->getStyle("F$i")->applyFromArray($style_row_2);
            $sheet->getStyle("G$i")->applyFromArray($style_row_2);
            /* $gelb = 'FFFF83';
              $blau = '96EFFF';
              $orange = 'FFB297';* */
        }
        $gesamtmenge_tag_speise_umger = $tour_sumup['gesamt_menge'];
        $einheit = $menge_pro_portion->getEinheit();
        if ($menge_pro_portion->getEinheit() == 'g' || $menge_pro_portion->getEinheit() == 'ml') {
            $gesamtmenge_tag_speise_umger = $tour_sumup['gesamt_menge'] / 1000;
            switch ($menge_pro_portion->getEinheit()) {
                case 'g':
                    $einheit = 'kg';
                    break;
                case 'ml':
                    $einheit = 'L';
                    break;
            }
        }
        $sheet->getStyle("A$i:G$i")->applyFromArray($default_border);
        $sheet->setCellValue("A$i", $tour_bez);
        $sheet->setCellValue("D$i", $gesamtmenge_tag_speise_umger . ' ' . $einheit);
        $sheet->setCellValue("F$i", $tour_sumup['gesamt_portionen']);
        $gesamtmenge_alle_touren += $tour_sumup['gesamt_menge'];
        $gesamtmenge_alle_touren_umg += $gesamtmenge_tag_speise_umger;
        $gesamtportionen_tag += $tour_sumup['gesamt_portionen'];
        $i++;
    }
    $sheet->mergeCells("D$i:E$i");
    $sheet->mergeCells("F$i:G$i");
    $sheet->getStyle("A$i:G$i")->applyFromArray($style_row_gesamt);
    $sheet->getStyle("A$i:G$i")->getFont()->setBold(true);
    $sheet->setCellValue("A$i", 'GESAMT');
    $sheet->setCellValue("D$i", $gesamtmenge_alle_touren_umg . ' ' . $einheit);
    $sheet->setCellValue("F$i", $gesamtportionen_tag);
    $i++;
    $i++;

    if ($speise->getRezept() != '') {
        $sheet->setCellValue("A$i", "REZEPT/BEMERK.:");
        $sheet->getStyle("A$i")->getFont()->setBold(true);
        $i++;
        $sheet->mergeCells("A$i:E$i");
        $sheet->getStyle("A$i")->getAlignment()->setWrapText(true);
        $sheet->setCellValue("A$i", $speise->getRezept());
        $i++;
    }



    //CHECKLISTE
    $anzahl_zeilen_checkliste = 12;
    $tour_gezaehlt = array();
    foreach ($zusammenfassung_nach_touren_array as $tourname_key => $tourinfos_array) {

        foreach ($tourinfos_array as $kunde_key => $kunde_tour) {
            if ($kunde_tour['notiz_speise']) {

                if (!$tour_gezaehlt[$tourname_key]) {
                    $anzahl_zeilen_checkliste++;
                    $tour_gezaehlt[$tourname_key] = 1;
                }
                $anzahl_zeilen_checkliste++;
            }
            /*   echo '<pre>';
              var_dump($kunde_key,$kunde_tour);
              echo '</pre>'; */
        }
    }
    $i = $anzahl_zeilen_deckblatt;
    $i++;
    $sheet->insertNewRowBefore($anzahl_zeilen_deckblatt, $anzahl_zeilen_checkliste);

    $sheet->setCellValue("A$i", 'Checkliste Besonderheiten am ' .
        strftime('%d.%m.%Y', mktime(12, 0, 0, $monat, $tag, $jahr)) . ' - ' . $speise->getBezeichnung());
    $sheet->getStyle("A$i")->applyFromArray($style_head_summary);
    $sheet->getStyle("A$i")->getFont()->setSize(14);
    $sheet->mergeCells("A$i:G$i");
    $i++;
    foreach ($zusammenfassung_nach_touren_array as $tourname_key => $tourinfos_array) {
        $show_tour = false;
        foreach ($tourinfos_array as $kunde_in_tour) {
            /* echo '<pre>';
              var_dump($tourname_key ,$kunde_in_tour);
              echo '</pre>'; */
            if (substr($kunde_in_tour['portionen'], 0, 1) > 0 && ($kunde_in_tour['notiz_speise'] != NULL || $kunde_tour['notiz_speise_tag'] != NULL || $kunde_tour['bemerkung_speisen_kunde '] != NULL)) {
                $show_tour = true;
            }
        }

        if (!$show_tour) {
            $anzahl_kunden--;
            continue;
        } else {
            if (count($tourinfos_array) > 0) {
                $sheet->setCellValue("A$i", $tourname_key);
                $sheet->mergeCells("A$i:G$i");
                $sheet->getStyle("A$i")->applyFromArray($style_tour);
                $i++;
            }
        }

        foreach ($tourinfos_array as $kunde_key => $kunde_tour) {
            if (substr($kunde_tour['portionen'], 0, 1) == '0' || $kunde_tour['notiz_speise'] == NULL) {
                $anzahl_kunden--;
                continue;
            }
            if ($i % 2) {
                $sheet->getStyle("A$i")->applyFromArray($style_col1);
                $sheet->getStyle("B$i")->applyFromArray($style_row);
                $sheet->getStyle("C$i")->applyFromArray($style_row);
                $sheet->getStyle("D$i")->applyFromArray($style_row);
                $sheet->getStyle("E$i")->applyFromArray($style_row);
                $sheet->getStyle("F$i")->applyFromArray($style_row);
                $sheet->getStyle("G$i")->applyFromArray($style_row);
            } else {
                $sheet->getStyle("A$i")->applyFromArray($style_col1_2);
                $sheet->getStyle("B$i")->applyFromArray($style_row_2);
                $sheet->getStyle("C$i")->applyFromArray($style_row_2);
                $sheet->getStyle("D$i")->applyFromArray($style_row_2);
                $sheet->getStyle("E$i")->applyFromArray($style_row_2);
                $sheet->getStyle("F$i")->applyFromArray($style_row_2);
                $sheet->getStyle("G$i")->applyFromArray($style_row_2);
            }

            $sheet->setCellValue("A$i", $kunde_key . ' ' . substr($kunde_tour['portionen'], 0, 1));
            if (isset($kunde_tour['bemerkung_speisen_kunde'])) {
                $sheet->setCellValue("E$i", $kunde_tour['bemerkung_speisen_kunde']);
            }
            if (isset($kunde_tour['bemerkung_kunde'])) {
                $sheet->setCellValue("F$i", $kunde_tour['bemerkung_kunde']);
            }
            if (isset($kunde_tour['notiz_speise']) || isset($kunde_tour['notiz_speise_tag'])) {
                $notiz_string = $kunde_tour['notiz_speise'];
                if ($kunde_tour['notiz_speise_tag']) {
                    $notiz_string .= ' | ' . $kunde_tour['notiz_speise_tag'];
                }
                $sheet->setCellValue("G$i", $notiz_string);
            }
            $sheet->getStyle("E$i")->getAlignment()->setWrapText(true);
            $sheet->getStyle("F$i")->getAlignment()->setWrapText(true);
            $sheet->getStyle("G$i")->getAlignment()->setWrapText(true);
            $i++;
        }
    }



//var_dump($zeile_start_produktion_2,$zeile_start_produktion_2+$anzahl_zeilen_checkliste+$anzahl_zeilen_deckblatt);

    $i++;
    $i++;

    $style_bold = array(
        'font' => array(
            'bold' => true,
            'size' => 13
        ));

    $sheet->setCellValue("A$i", 'Die Besonderheiten wurden berücksichtigt:');
    $sheet->getStyle("A$i")->applyFromArray($style_bold);
    $sheet->mergeCells("A$i:F$i");
    $i++;
    $sheet->getStyle("A$i")->applyFromArray($style_handtext);
    $sheet->mergeCells("A$i:B$i");
    $sheet->getRowDimension($i)->setRowHeight(30);
    $i++;
    $sheet->setCellValue("A$i", 'Unterschrift');
    $sheet->mergeCells("A$i:F$i");
    $i++;
    $sheet->getRowDimension($i)->setRowHeight(10);
    $i++;
    $sheet->setCellValue("A$i", 'Bemerkungen:');
    $sheet->getStyle("A$i")->applyFromArray($style_bold);
    $sheet->mergeCells("B$i:F$i");
    $sheet->getStyle("A$i:F$i")->applyFromArray($style_handtext);
    $i++;
    $sheet->getStyle("A$i")->applyFromArray($style_handtext);
    $sheet->mergeCells("A$i:F$i");
    $sheet->getRowDimension($i)->setRowHeight(30);

    $bl = $i;
    $break_line = "A" . $bl;
    $sheet->setBreak($break_line, PHPExcel_Worksheet::BREAK_ROW);

    if ($speise_nr <= 2) {

        //DECKBLATT 2
        /* echo '<pre>';
          var_dump($zeile_start_produktion_2, $zeile_start_produktion_2+$anzahl_zeilen_deckblatt+$anzahl_zeilen_checkliste+1, $anzahl_zeilen_deckblatt+$anzahl_zeilen_checkliste);
          echo '</pre>'; */

        $anzahl_zeilen_deckblatt = count($uebersicht_array) + 7;
        $i = $zeile_start_produktion_2 + $anzahl_zeilen_deckblatt + $anzahl_zeilen_checkliste + 1;
        $sheet->insertNewRowBefore($i, $anzahl_zeilen_deckblatt + $anzahl_zeilen_checkliste);
        $sheet->getRowDimension($i)->setRowHeight(15);
        $sheet->getStyle("A$i:G$i")->applyFromArray($style_header);
        $sheet->getStyle("A$i")->getFont()->setSize(14);
        $sheet->getStyle("D$i")->getFont()->setSize(12);
        $sheet->mergeCells("A$i:C$i");
        $sheet->mergeCells("D$i:G$i");
        $sheet->setCellValue("A$i", $speise->getBezeichnung() . ' - ' . $tag . '.' . $monat . '.' . $jahr);
        $sheet->setCellValue("D$i", 'Erstellt: ' . strftime('%a %d.%m.%Y - %H:%M Uhr', mktime(date('H'), date('i'), date('s'), date('m'), date('d'), date('Y'))));
        $i++;
        $sheet->getRowDimension($i)->setRowHeight(-1);
        $sheet->getStyle("A$i:G$i")->applyFromArray($style_row_gesamt);
        $sheet->getStyle("A$i:G$i")->getFont()->setSize(11);
        $sheet->mergeCells("A$i:C$i");
        $sheet->mergeCells("D$i:E$i");
        $sheet->mergeCells("F$i:G$i");
        $sheet->setCellValue("A$i", 'TOUR');
        $sheet->setCellValue("D$i", 'MENGE');
        $sheet->setCellValue("F$i", 'PORTIONEN');
        $i++;
        $gesamtmenge_alle_touren = 0;
        $gesamtportionen_tag = 0;
        foreach ($uebersicht_array as $tour_bez => $tour_sumup) {
            $sheet->getRowDimension($i)->setRowHeight(-1);
            $sheet->mergeCells("D$i:E$i");
            $sheet->mergeCells("F$i:G$i");
            $sheet->getStyle("A$i:G$i")->getFont()->setSize(11);

            if ($i % 2) {
                $sheet->getStyle("A$i")->applyFromArray($style_col1);
                $sheet->getStyle("B$i")->applyFromArray($style_row);
                $sheet->getStyle("C$i")->applyFromArray($style_row);
                $sheet->getStyle("D$i")->applyFromArray($style_row);
                $sheet->getStyle("E$i")->applyFromArray($style_row);
                $sheet->getStyle("F$i")->applyFromArray($style_row);
                $sheet->getStyle("G$i")->applyFromArray($style_row);
            } else {
                $sheet->getStyle("A$i")->applyFromArray($style_col1_2);
                $sheet->getStyle("B$i")->applyFromArray($style_row_2);
                $sheet->getStyle("C$i")->applyFromArray($style_row_2);
                $sheet->getStyle("D$i")->applyFromArray($style_row_2);
                $sheet->getStyle("E$i")->applyFromArray($style_row_2);
                $sheet->getStyle("F$i")->applyFromArray($style_row_2);
                $sheet->getStyle("G$i")->applyFromArray($style_row_2);
            }
            $gesamtmenge_tag_speise_umger = $tour_sumup['gesamt_menge'];
            $einheit = $menge_pro_portion->getEinheit();
            if ($menge_pro_portion->getEinheit() == 'g' || $menge_pro_portion->getEinheit() == 'ml') {
                $gesamtmenge_tag_speise_umger = $tour_sumup['gesamt_menge'] / 1000;
                switch ($menge_pro_portion->getEinheit()) {
                    case 'g':
                        $einheit = 'kg';
                        break;
                    case 'ml':
                        $einheit = 'L';
                        break;
                }
            }
            $sheet->getStyle("A$i:G$i")->applyFromArray($default_border);
            $sheet->setCellValue("A$i", $tour_bez);
            $sheet->setCellValue("D$i", $gesamtmenge_tag_speise_umger . ' ' . $einheit);
            $sheet->setCellValue("F$i", $tour_sumup['gesamt_portionen']);
            // $gesamtmenge_alle_touren += $tour_sumup['gesamt_menge'];
            //$gesamtmenge_alle_touren_umg += $gesamtmenge_tag_speise_umger;
            $gesamtportionen_tag += $tour_sumup['gesamt_portionen'];
            $i++;
        }
        $sheet->mergeCells("D$i:E$i");
        $sheet->mergeCells("F$i:G$i");
        $sheet->getStyle("A$i:G$i")->getFont()->setBold(true);
        $sheet->getStyle("A$i:G$i")->getFont()->setSize(11);
        $sheet->getStyle("A$i:G$i")->applyFromArray($style_row_gesamt);
        $sheet->getRowDimension($i)->setRowHeight(-1);
        $sheet->setCellValue("A$i", 'GESAMT');
        $sheet->setCellValue("D$i", $gesamtmenge_alle_touren_umg . ' ' . $einheit);
        $sheet->setCellValue("F$i", $gesamtportionen_tag);
        $i++;
        $sheet->getStyle("A$i:G$i")->applyFromArray($style_row_2);
        $i++;

        if ($speise->getRezept() != '') {
            $sheet->setCellValue("A$i", "REZEPT/BEMERK.:");
            $sheet->getStyle("A$i")->getFont()->setBold(true);
            $i++;
            $sheet->mergeCells("A$i:E$i");
            $sheet->getStyle("A$i")->getAlignment()->setWrapText(true);
            $sheet->setCellValue("A$i", $speise->getRezept());
            $i++;
        }

        //CHECKLISTE
        $anzahl_zeilen_checkliste = 12;
        $tour_gezaehlt = array();
        foreach ($zusammenfassung_nach_touren_array as $tourname_key => $tourinfos_array) {

            foreach ($tourinfos_array as $kunde_key => $kunde_tour) {
                if ($kunde_tour['notiz_speise']) {

                    if (!$tour_gezaehlt[$tourname_key]) {
                        $anzahl_zeilen_checkliste++;
                        $tour_gezaehlt[$tourname_key] = 1;
                    }
                    $anzahl_zeilen_checkliste++;
                }
            }
        }
        $i++;
        //$sheet->insertNewRowBefore($i, $anzahl_zeilen_checkliste);

        $sheet->getRowDimension($i)->setRowHeight(20);
        $sheet->setCellValue("A$i", 'Checkliste Besonderheiten am ' .
            strftime('%d.%m.%Y', mktime(12, 0, 0, $monat, $tag, $jahr)) . ' - ' . $speise->getBezeichnung());
        $sheet->getStyle("A$i")->applyFromArray($style_head_summary);
        $sheet->getStyle("A$i")->getFont()->setSize(14);
        $sheet->mergeCells("A$i:G$i");
        $i++;
        foreach ($zusammenfassung_nach_touren_array as $tourname_key => $tourinfos_array) {
            $show_tour = false;
            foreach ($tourinfos_array as $kunde_in_tour) {

                if (substr($kunde_in_tour['portionen'], 0, 1) > 0 && ($kunde_in_tour['notiz_speise'] != NULL || $kunde_tour['notiz_speise_tag'] != NULL || $kunde_tour['bemerkung_speisen_kunde '] != NULL)) {
                    $show_tour = true;
                }
            }

            if (!$show_tour) {
                $anzahl_kunden--;
                continue;
            } else {
                if (count($tourinfos_array) > 0) {
                    $sheet->getRowDimension($i)->setRowHeight(20);
                    $sheet->setCellValue("A$i", $tourname_key);
                    $sheet->mergeCells("A$i:G$i");
                    $sheet->getStyle("A$i")->applyFromArray($style_tour);
                    $i++;
                }
            }

            foreach ($tourinfos_array as $kunde_key => $kunde_tour) {
                if (substr($kunde_tour['portionen'], 0, 1) == '0' || $kunde_tour['notiz_speise'] == NULL) {
                    $anzahl_kunden--;
                    continue;
                }
                if ($i % 2) {
                    $sheet->getStyle("A$i")->applyFromArray($style_col1);
                    $sheet->getStyle("B$i")->applyFromArray($style_row);
                    $sheet->getStyle("C$i")->applyFromArray($style_row);
                    $sheet->getStyle("D$i")->applyFromArray($style_row);
                    $sheet->getStyle("E$i")->applyFromArray($style_row);
                    $sheet->getStyle("F$i")->applyFromArray($style_row);
                    $sheet->getStyle("G$i")->applyFromArray($style_row);
                } else {
                    $sheet->getStyle("A$i")->applyFromArray($style_col1_2);
                    $sheet->getStyle("B$i")->applyFromArray($style_row_2);
                    $sheet->getStyle("C$i")->applyFromArray($style_row_2);
                    $sheet->getStyle("D$i")->applyFromArray($style_row_2);
                    $sheet->getStyle("E$i")->applyFromArray($style_row_2);
                    $sheet->getStyle("F$i")->applyFromArray($style_row_2);
                    $sheet->getStyle("G$i")->applyFromArray($style_row_2);
                }

                $sheet->setCellValue("A$i", $kunde_key . ' ' . substr($kunde_tour['portionen'], 0, 1));
                if (isset($kunde_tour['bemerkung_speisen_kunde'])) {
                    $sheet->setCellValue("E$i", $kunde_tour['bemerkung_speisen_kunde']);
                }
                if (isset($kunde_tour['bemerkung_kunde'])) {
                    $sheet->setCellValue("F$i", $kunde_tour['bemerkung_kunde']);
                }
                if (isset($kunde_tour['notiz_speise']) || isset($kunde_tour['notiz_speise_tag'])) {
                    $notiz_string = $kunde_tour['notiz_speise'];
                    if ($kunde_tour['notiz_speise_tag']) {
                        $notiz_string .= ' | ' . $kunde_tour['notiz_speise_tag'];
                    }
                    $sheet->setCellValue("G$i", $notiz_string);
                }
                $sheet->getStyle("E$i")->getAlignment()->setWrapText(true);
                $sheet->getStyle("F$i")->getAlignment()->setWrapText(true);
                $sheet->getStyle("G$i")->getAlignment()->setWrapText(true);
                $i++;
            }
        }

        $i++;
        $i++;

        $style_bold = array(
            'font' => array(
                'bold' => true,
                'size' => 13
            ));

        $sheet->getRowDimension($i)->setRowHeight(20);
        $sheet->setCellValue("A$i", 'Die Besonderheiten wurden berücksichtigt:');
        $sheet->getStyle("A$i")->applyFromArray($style_bold);
        $sheet->mergeCells("A$i:F$i");
        $i++;
        $sheet->getStyle("A$i")->applyFromArray($style_handtext);
        $sheet->mergeCells("A$i:B$i");
        $sheet->getRowDimension($i)->setRowHeight(30);
        $i++;
        $sheet->setCellValue("A$i", 'Unterschrift');
        $sheet->mergeCells("A$i:F$i");
        $i++;
        $sheet->getRowDimension($i)->setRowHeight(10);
        $i++;
        $sheet->setCellValue("A$i", 'Bemerkungen:');
        $sheet->getStyle("A$i")->applyFromArray($style_bold);
        $sheet->mergeCells("B$i:F$i");
        $sheet->getStyle("A$i:F$i")->applyFromArray($style_handtext);
        $i++;
        $sheet->getStyle("A$i")->applyFromArray($style_handtext);
        $sheet->mergeCells("A$i:F$i");
        $sheet->getRowDimension($i)->setRowHeight(30);

        $bl = $i;
        $break_line = "A" . $bl;
        $sheet->setBreak($break_line, PHPExcel_Worksheet::BREAK_ROW);

        //ENDE DECKBLATT 2
    }
    /* $i++;
      $sheet->getStyle("A$i")->applyFromArray($style_handtext);
      $sheet->mergeCells("A$i:F$i");
      $sheet->getRowDimension($i)->setRowHeight(30); */

    $writer = PHPExcel_IOFactory::createWriter($xls, "Excel5");

    $heute = strftime('%d_%m_%Y', time());
    $rechner = php_uname('n');
    $speise_bezeichnung = $speise->getBezeichnung();
    $speise_bezeichnung = umlautepas($speise_bezeichnung);
    //var_dump($speise_bezeichnung);
    $speise_bezeichnung = entferneSonderzeichen($speise_bezeichnung);
    if ($_SESSION['is_local_server']) {
        $writer->save('export_tagesaufstellungen/' . $speise_bezeichnung . '_' . $speise_nr . '_' . strftime('%d_%m_%y', $ts) . ".xls");
    } else {
        $writer->save('/is/htdocs/wp1055935_1F55KTNHIC/www/sbar/verwaltung/export_tagesaufstellungen/' . $speise_bezeichnung . '_' . $speise_nr . '_' . strftime('%d_%m_%y', $ts) . ".xls");
    }
    if ($_SESSION['is_local_server']) {
        header('location:export_tagesaufstellungen/' . $speise_bezeichnung . '_' . $speise_nr . '_' . strftime('%d_%m_%y', $ts) . ".xls");
        exit;
    } else {
        header('location:http://www.s-bar.net/verwaltung/export_tagesaufstellungen/' . $speise_bezeichnung . '_' . $speise_nr . '_' . strftime('%d_%m_%y', $ts) . ".xls");
        exit;
    }
}

function erzeugeTagesaufstellungExcel($tag, $monat, $jahr, $starttag, $startmonat, $startjahr, $speise_id, $bestellung, $bestellung_has_speiseVerwaltung, $speiseVerwaltung, $menge_pro_portionVerwaltung, $standardportionenVerwaltung, $portionenaenderungVerwaltung, $kunden, $indifaktorVerwaltung, $bemerkungzutagVerwaltung, $bemerkungzuspeiseVerwaltung, $speise_nr = 1, $kundeVerwaltung = NULL, $color_speisen = array()) {


    require_once('../verwaltung_alt/PHPExcel-1.7.5/PHPExcel.php');
    require_once('../verwaltung_alt/PHPExcel-1.7.5/PHPExcel/IOFactory.php');

    $ts = mktime(12, 0, 0, $monat, $tag, $jahr);
    // neue instanz erstellen
    $xls = new PHPExcel();
    // dokumenteigenschaften schreiben
    $xls->getProperties()->setCreator("S-Bar")
        ->setLastModifiedBy("S-Bar")
        ->setTitle("Tagesaufstellung " . strftime('%d.%m.%y', $ts))
        ->setSubject("Tagesaufstellung " . strftime('%d.%m.%y', $ts));
    // das erste worksheet anwaehlen
    $sheet = $xls->setActiveSheetIndex(0);

    //STYLES START
    $sheet->getStyle('A1')->getFont()->setBold(true);
    $sheet->getStyle('A2:D2')->getFont()->setBold(true);
    $sheet->getColumnDimension('A')->setWidth(32);
    $sheet->getColumnDimension('D')->setWidth(15);
    $sheet->getColumnDimension('E')->setWidth(15);
    $sheet->getColumnDimension('B')->setWidth(15);
    $sheet->getColumnDimension('C')->setWidth(25);
    $sheet->getColumnDimension('F')->setWidth(25);
    $sheet->getColumnDimension('G')->setWidth(25);
    $sheet->getColumnDimension('A')->setAutoSize(false);

    $default_border = array(
        'style' => PHPExcel_Style_Border::BORDER_THIN,
        'color' => array('rgb' => 'BEBEBE')
    );
    $style_header_1 = array(
        'borders' => array(
            'bottom' => $default_border,
            'left' => $default_border,
            'top' => $default_border,
            'right' => $default_border,
        ),
        'fill' => array(
            'type' => PHPExcel_Style_Fill::FILL_SOLID,
            'color' => array('rgb' => '' . str_replace('#', '', $color_speisen[$speise_nr])),
        ),
        'font' => array(
            'bold' => true,
            'color' => array('rgb' => 'ffffff'),
        ),
        'alignment' => array(
            'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER,
            'indent' => 0.5,
        )
    );
    $style_header = array(
        'borders' => array(
            'bottom' => $default_border,
            'left' => $default_border,
            'top' => $default_border,
            'right' => $default_border,
        ),
        'fill' => array(
            'type' => PHPExcel_Style_Fill::FILL_SOLID,
            'color' => array('rgb' => '' . str_replace('#', '', $color_speisen[$speise_nr + 4])),
        ),
        'font' => array(
            'bold' => true,
            'color' => array('rgb' => 'ffffff'),
        ),
        'alignment' => array(
            'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER,
            'indent' => 0.5,
        )
    );
    $style_row = array(
        'borders' => array(
            'bottom' => $default_border,
            'left' => $default_border,
            'top' => $default_border,
            'right' => $default_border,
        ),
        'fill' => array(
            'type' => PHPExcel_Style_Fill::FILL_SOLID,
            'color' => array('rgb' => 'E1E0F7'),
        ),
        'font' => array(
            'bold' => true,
        ),
        'alignment' => array(
            'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER,
            'vertical' => PHPExcel_Style_Alignment::VERTICAL_TOP,
            'indent' => 0.5,
            'wrapText' => true,
        )
    );
    $style_row_2 = array(
        'borders' => array(
            'bottom' => $default_border,
            'left' => $default_border,
            'top' => $default_border,
            'right' => $default_border,
        ),
        'fill' => array(
            'type' => PHPExcel_Style_Fill::FILL_SOLID,
            'color' => array('rgb' => 'FFFFFF'),
        ),
        'font' => array(
            'bold' => true,
        ),
        'alignment' => array(
            'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER,
            'vertical' => PHPExcel_Style_Alignment::VERTICAL_TOP,
            'indent' => 0.5,
            'wrapText' => true,
        )
    );
    $style_row_durchgestr = array(
        'borders' => array(
            'bottom' => $default_border,
            'left' => $default_border,
            'top' => $default_border,
            'right' => $default_border,
        ),
        'fill' => array(
            'type' => PHPExcel_Style_Fill::FILL_SOLID,
            'color' => array('rgb' => 'E1E0F7'),
        ),
        'font' => array(
            'bold' => true,
            'strikethrough' => true,
            'color' => array('rgb' => '888888'),
        ),
        'alignment' => array(
            'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER,
            'vertical' => PHPExcel_Style_Alignment::VERTICAL_TOP,
            'indent' => 0.5,
        )
    );
    $style_row_durchgestr_2 = array(
        'borders' => array(
            'bottom' => $default_border,
            'left' => $default_border,
            'top' => $default_border,
            'right' => $default_border,
        ),
        'fill' => array(
            'type' => PHPExcel_Style_Fill::FILL_SOLID,
            'color' => array('rgb' => 'FFFFFF'),
        ),
        'font' => array(
            'bold' => true,
            'strikethrough' => true,
            'color' => array('rgb' => '888888'),
        ),
        'alignment' => array(
            'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER,
            'vertical' => PHPExcel_Style_Alignment::VERTICAL_TOP,
            'indent' => 0.5,
        )
    );
    $style_row_gesamt = array(
        'borders' => array(
            'bottom' => $default_border,
            'left' => $default_border,
            'top' => $default_border,
            'right' => $default_border,
        ),
        'fill' => array(
            'type' => PHPExcel_Style_Fill::FILL_SOLID,
            'color' => array('rgb' => 'C3C2D9'),
        ),
        'font' => array(
            'bold' => true,
        ),
        'alignment' => array(
            'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER,
            'indent' => 0.5,
        )
    );
    $style_col1 = array(
        'borders' => array(
            'bottom' => $default_border,
            'left' => $default_border,
            'top' => $default_border,
            'right' => $default_border,
        ),
        'fill' => array(
            'type' => PHPExcel_Style_Fill::FILL_SOLID,
            'color' => array('rgb' => 'E1E0F7'),
        ),
        'font' => array(
            'bold' => true,
        ),
        'alignment' => array(
            'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_LEFT,
            'vertical' => PHPExcel_Style_Alignment::VERTICAL_TOP,
            'indent' => 0.5,
            'wrapText' => true,
        )
    );
    $style_col1_2 = array(
        'borders' => array(
            'bottom' => $default_border,
            'left' => $default_border,
            'top' => $default_border,
            'right' => $default_border,
        ),
        'fill' => array(
            'type' => PHPExcel_Style_Fill::FILL_SOLID,
            'color' => array('rgb' => 'FFFFFF'),
        ),
        'font' => array(
            'bold' => true,
        ),
        'alignment' => array(
            'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_LEFT,
            'vertical' => PHPExcel_Style_Alignment::VERTICAL_TOP,
            'indent' => 0.5,
        )
    );

    $style_head_summary = array(
        'borders' => array(
            'bottom' => $default_border,
            'left' => $default_border,
            'top' => array(
                'style' => PHPExcel_Style_Border::BORDER_THICK,
                'color' => array('rgb' => 'ffffff')
            ),
            'right' => $default_border,
        ),
        'fill' => array(
            'type' => PHPExcel_Style_Fill::FILL_SOLID,
            'color' => array('rgb' => 'EC5006'),
        ),
        'font' => array(
            'bold' => true,
            'color' => array('rgb' => 'ffffff'),
            'size' => 18
        ),
        'alignment' => array(
            'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER,
            'vertical' => PHPExcel_Style_Alignment::VERTICAL_TOP,
            'indent' => 0.5,
        )
    );
    $style_tour = array(
        'borders' => array(
            'bottom' => $default_border,
            'left' => $default_border,
            'top' => array(
                'style' => PHPExcel_Style_Border::BORDER_MEDIUM,
                'color' => array('rgb' => 'ffffff')
            ),
            'right' => $default_border,
        ),
        'fill' => array(
            'type' => PHPExcel_Style_Fill::FILL_SOLID,
            'color' => array('rgb' => 'dddddd'),
        ),
        'font' => array(
            'bold' => true,
            'color' => array('rgb' => '888888'),
            'size' => 16
        ),
        'alignment' => array(
            'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER,
            'vertical' => PHPExcel_Style_Alignment::VERTICAL_TOP,
            'indent' => 0.5,
        )
    );
    $format_bold = array(
        'font' => array(
            'bold' => true
        )
    );
    $style_handtext = array(
        'borders' => array(
            'top' => array(),
            'left' => array(),
            'bottom' => array(
                'style' => PHPExcel_Style_Border::BORDER_DOTTED,
                'color' => array('rgb' => '444444')
            ),
            'right' => array(),
        )
    );
    $sheet->getPageMargins()->setTop(0.5);
    $sheet->getPageMargins()->setRight(0.5);
    $sheet->getPageMargins()->setLeft(0.5);
    $sheet->getPageMargins()->setBottom(0.5);
    $sheet->getPageSetup()->setOrientation(PHPExcel_Worksheet_PageSetup::ORIENTATION_LANDSCAPE);
    $sheet->getPageSetup()->setPaperSize(PHPExcel_Worksheet_PageSetup::PAPERSIZE_A4);
    /* $sheet->getPageSetup()->setFitToPage(true);
      $sheet->getPageSetup()->setFitToWidth(1);
      $sheet->getPageSetup()->setFitToHeight(0); */

    $sheet->getStyle('A3:G3')->applyFromArray($style_header_1);
    $sheet->getStyle('A4:G4')->applyFromArray($style_header);
    //STYLES ENDE
    $i = 5;
    $x = $i - 1;

    // den namen vom Worksheet 1 definieren
    $xls->getActiveSheet()->setTitle("Tagesaufstellung " . strftime('%d.%m.%y', $tag));
    $i = 5;
    $bestellung_has_speise_array = $bestellung_has_speiseVerwaltung->findeAnhandVonBestellungId($bestellung->getId());

    $gesamtmenge_tag_speise = 0;
    $gesamtmenge_tag_portionen = 0;
    $c = $i - 2;
    $d = $i - 1;
    $sheet->getStyle("A$d:F$d")->getFont()->setSize(15);
    $sheet->getStyle("A$c:E$c")->getFont()->setSize(15);

    $speise = $speiseVerwaltung->findeAnhandVonId($speise_id);
    $sheet->setCellValue("A$c", $speise->getBezeichnung());
    // den wert test in das Feld A1 schreiben
    $sheet->setCellValue("A$d", "Kunde", $format_bold);
    $sheet->setCellValue("D$d", "Port.");
    $sheet->setCellValue("E$d", "pro P.");
    $sheet->setCellValue("B$d", "Gesamt");
    $sheet->setCellValue("C$d", "Diäten");
    $sheet->setCellValue("F$d", "Kundeninfo");
    $sheet->setCellValue("G$d", "Notiz Speise");
    // $sheet->setCellValue("E$d","Menge/P Standard");
    //$sheet->setCellValue("F$d","Faktor");
    $zeilen_mit_summe = array();

    $zeile_tour_beginn = array();
    $portionen_zu_tour = array();
    $menge_diese_tour = 0;
    $portionen_diese_tour = 0;

    $zusammenfassung_nach_touren_array = array();

    $last_tour = new Kunde();
    $count_k = 1;
    $anzahl_kunden = count($kunden);
    /* echo '<pre>';
      var_dump($kunden);
      echo '</pre>'; */
    foreach ($kunden as $kunde) {

        $einrichtungskategorie_id = $kunde->getEinrichtungskategorieId();
        /* if ($speise_nr <= 2 && $einrichtungskategorie_id == 6 && ($kunde->getBioKunde() || $kunde->getStaedtischerKunde())) {
          $anzahl_kunden--;
          continue;
          } */
        $tour_id = $kunde->getTourId();
        $tour_zu_kunde = $kundeVerwaltung->findeAnhandVonId($tour_id);
        /* echo '<pre>';
          var_dump($kunde->getId(), $last_tour->getId(), $tour_zu_kunde->getId(),'');
          echo '</pre>'; */
        if ($count_k == $anzahl_kunden - 1) {
            $sheet->setCellValue("A$i", "ENDE " . $last_tour->getName());
            $sheet->mergeCells("A$i:G$i");
            $sheet->getStyle("A$i:G$i")->applyFromArray($style_tour);
            $i++;
        }
        /* if ($speise_nr == 2 && $kunde->getAnzahlSpeisen() == 1) {
          $anzahl_kunden--;
          continue;
          } */
        /*   if ($speise_nr <= 2 && ($kunde->getBioKunde() || $kunde->getStaedtischerKunde())) {
          $anzahl_kunden--;
          continue;
          } */
        /* if ($speise_nr > 2 && (!$kunde->getBioKunde() && !$kunde->getStaedtischerKunde()) ) {
          $anzahl_kunden--;
          continue;
          } */

        //  $last_tour = $tour_zu_kunde;
        //if ($speise_nr > 1) {
        $tour_zu_kunde = $kundeVerwaltung->findeTourZuKundenReihenfolge($kunde->getLieferreihenfolge());
        $tour_zu_kunde = $tour_zu_kunde[0];
        $tour_id = $kunde->getTourId();
        if ($einrichtungskategorie_id == 5) {
            $tour_id = $kunde->getId();
        }
        $tour_zu_kunde = $kundeVerwaltung->findeAnhandVonId($tour_id);

        if ($tour_zu_kunde == NULL) {
            $tour_zu_kunde = new Kunde();
        }
        /*  echo '<pre>';
          var_dump($kunde, $last_tour->getId());
          echo '</pre>'; */
        if ($last_tour->getId() != $tour_zu_kunde->getId()) {
            if ($last_tour->getId()) {
                $sheet->setCellValue("A$i", "ENDE " . $last_tour->getName());
                $sheet->mergeCells("A$i:G$i");
                $sheet->getStyle("A$i:G$i")->applyFromArray($style_tour);
                $i++;
            }

            $i++;
            /*  $sheet->setCellValue("A$i", "XX2 " . $tour_zu_kunde->getName());
              $sheet->mergeCells("A$i:G$i");
              $sheet->getStyle("A$i:G$i")->applyFromArray($style_tour);
              $i++; */
        }
        $count_k++;
        $last_tour = $tour_zu_kunde;
        //}


        if ($i == 35 || $i == 70 || $i == 105 || $i == 140 || $i == 175 || $i == 210) {
            /* $sheet->setCellValue("A$i", $speise->getBezeichnung());
              $sheet->getStyle("A$i:E$i")->applyFromArray($style_header_1);
              $i++; */
        }
        if ($i % 2) {
            $sheet->getStyle("A$i")->applyFromArray($style_col1);
            $sheet->getStyle("B$i")->applyFromArray($style_row);
            $sheet->getStyle("C$i")->applyFromArray($style_row);
            $sheet->getStyle("D$i")->applyFromArray($style_row);
            $sheet->getStyle("E$i")->applyFromArray($style_row);
            $sheet->getStyle("F$i")->applyFromArray($style_row);
            $sheet->getStyle("G$i")->applyFromArray($style_row);
            $gelb = 'E1E061';
            $blau = '78D1E1';
            $orange = 'EB8065';
        } else {
            $sheet->getStyle("A$i")->applyFromArray($style_col1_2);
            $sheet->getStyle("B$i")->applyFromArray($style_row_2);
            $sheet->getStyle("C$i")->applyFromArray($style_row_2);
            $sheet->getStyle("D$i")->applyFromArray($style_row_2);
            $sheet->getStyle("E$i")->applyFromArray($style_row_2);
            $sheet->getStyle("F$i")->applyFromArray($style_row_2);
            $sheet->getStyle("G$i")->applyFromArray($style_row_2);
            $gelb = 'FFFF83';
            $blau = '96EFFF';
            $orange = 'FFB297';
        }

        $sheet->getStyle("A$i:F$i")->getFont()->setSize(15);
        $sheet->getStyle("E$i")->getFont()->setSize(8);
        $sheet->getStyle("D$i")->getFont()->setSize(8);
        $sheet->getStyle("C$i")->getFont()->setSize(12);
        $sheet->getStyle("F$i")->getFont()->setSize(12);
        $sheet->getStyle("G$i")->getFont()->setSize(12);
        // $sheet->getStyle("E$i")->applyFromArray( $style_row );
        //$sheet->getStyle("F$i")->applyFromArray( $style_row );
        $speise_id = $speise->getId();
        $indi_faktor = $indifaktorVerwaltung->findeAnhandVonSpeiseIdUndKundeId($speise_id, $kunde->getId());
        $faktor = $indi_faktor->getFaktor();
        $menge_pro_portion = $menge_pro_portionVerwaltung->findeAnhandVonSpeiseIdUndEinrichtungskategorieId($speise_id, $einrichtungskategorie_id);

        $standardportionen = $standardportionenVerwaltung->findeAnhandVonKundenIdUndSpeiseNr($kunde->getId(), $speise_nr);
        $portionenaenderung = $portionenaenderungVerwaltung->findeAnhandVonKundenIdUndWochenstartdatum($kunde->getId(), $starttag, $startmonat, $startjahr, $speise_nr);

        if ($portionenaenderung->getId()) {
            $portionen_mo = $portionenaenderung->getPortionenMo();
            $portionen_di = $portionenaenderung->getPortionenDi();
            $portionen_mi = $portionenaenderung->getPortionenMi();
            $portionen_do = $portionenaenderung->getPortionenDo();
            $portionen_fr = $portionenaenderung->getPortionenFr();
            $aenderung = true;
        } else {
            $portionen_mo = $standardportionen->getPortionenMo();
            $portionen_di = $standardportionen->getPortionenDi();
            $portionen_mi = $standardportionen->getPortionenMi();
            $portionen_do = $standardportionen->getPortionenDo();
            $portionen_fr = $standardportionen->getPortionenFr();
            $aenderung = false;
        }

        $wochentag_string = strftime('%a', mktime(12, 0, 0, $monat, $tag, $jahr));
        switch ($wochentag_string) {
            case 'Mo':
                $portionen = $portionen_mo;
                if ($portionen_mo != $standardportionen->getPortionenMo()) {
                    $portionen .= ' (' . $standardportionen->getPortionenMo() . ')';
                }
                break;
            case 'Di':
                $portionen = $portionen_di;
                if ($portionen_di != $standardportionen->getPortionenDi()) {
                    $portionen .= ' (' . $standardportionen->getPortionenDi() . ')';
                }
                break;
            case 'Mi':
                $portionen = $portionen_mi;
                if ($portionen_mi != $standardportionen->getPortionenMi()) {
                    $portionen .= ' (' . $standardportionen->getPortionenMi() . ')';
                }
                break;
            case 'Do':
                $portionen = $portionen_do;
                if ($portionen_do != $standardportionen->getPortionenDo()) {
                    $portionen .= ' (' . $standardportionen->getPortionenDo() . ')';
                }
                break;
            case 'Fr':
                $portionen = $portionen_fr;
                if ($portionen_fr != $standardportionen->getPortionenFr()) {
                    $portionen .= ' (' . $standardportionen->getPortionenFr() . ')';
                }
                break;
        }

        $gesamtmenge_tag_portionen += $portionen;
        $gesamtmenge_kunde = $portionen * ($menge_pro_portion->getMenge() * $faktor);

        if ($portionen == 0) {
            if ($i % 2) {
                $sheet->getStyle("A$i")->applyFromArray($style_row_durchgestr);
                $sheet->getStyle("B$i")->applyFromArray($style_row_durchgestr);
                $sheet->getStyle("C$i")->applyFromArray($style_row_durchgestr);
                $sheet->getStyle("D$i")->applyFromArray($style_row_durchgestr);
                $sheet->getStyle("E$i")->applyFromArray($style_row_durchgestr);
                $sheet->getStyle("F$i")->applyFromArray($style_row_durchgestr);
                $sheet->getStyle("G$i")->applyFromArray($style_row_durchgestr);
            } else {
                $sheet->getStyle("A$i")->applyFromArray($style_row_durchgestr_2);
                $sheet->getStyle("B$i")->applyFromArray($style_row_durchgestr_2);
                $sheet->getStyle("C$i")->applyFromArray($style_row_durchgestr_2);
                $sheet->getStyle("D$i")->applyFromArray($style_row_durchgestr_2);
                $sheet->getStyle("E$i")->applyFromArray($style_row_durchgestr_2);
                $sheet->getStyle("F$i")->applyFromArray($style_row_durchgestr_2);
                $sheet->getStyle("G$i")->applyFromArray($style_row_durchgestr_2);
            }
        }


        if ($kunde->getEinrichtungskategorieId() == 5) {
            $zusammenfassung_nach_touren_array[$kunde->getName()] = array();
            $tour_name = $kunde->getName();
            /* if ($i > 6) {
              $sheet->setCellValue("A$i", "EE Ende " . $tour_name);
              $sheet->mergeCells("A$i:G$i");
              $sheet->getStyle("A$i:G$i")->applyFromArray($style_tour);
              $i++;
              } */
            $tourbeginn_zeile = $i;
            $menge_diese_tour = 0;
            $portionen_diese_tour = 0;

            $break_before_line = $i - 1;
            $break_before_line_str = "A" . $break_before_line;
            if ($i > 6) {
                $sheet->setBreak($break_before_line_str, PHPExcel_Worksheet::BREAK_ROW);
            }


            $sheet->setCellValue("A$i", $kunde->getName() . ' ' . strftime('%a %d.%m.%Y', mktime(12, 0, 0, $monat, $tag, $jahr)));
            $sheet->mergeCells("A$i:E$i");
            $sheet->getStyle("A$i:E$i")->applyFromArray($style_tour);
            $sheet->mergeCells("F$i:G$i");
            $sheet->setCellValue("F$i", 'Erstellt: ' . strftime('%a %d.%m.%Y - %H:%M Uhr', mktime(date('H'), date('i'), date('s'), date('m'), date('d'), date('Y'))));

            $sheet->getStyle("F$i")->applyFromArray($style_tour);
            $sheet->getStyle("F$i")->getFont()->setSize(12);

            $i++;

            $sheet->setCellValue("A$i", $speise->getBezeichnung());
            $sheet->getStyle("A$i")->getFont()->setSize(15);
            $sheet->mergeCells("A$i:G$i");
            $sheet->getStyle("A$i:G$i")->applyFromArray($style_header_1);
            $i++;

            $sheet->setCellValue("A$i", 'Gesamt');
            $zeilen_mit_summe[] = $i;
            $i++;

            $sheet->setCellValue("A$i", 'Gesamt ' . $kunde->getName());
            $i++;

            $sheet->setCellValue("A$i", "Kunde", $format_bold);
            $sheet->setCellValue("D$i", "Port.");
            $sheet->setCellValue("E$i", "pro P.");
            $sheet->setCellValue("B$i", "Gesamt");
            $sheet->setCellValue("C$i", "Diäten");
            $sheet->setCellValue("F$i", "Kundeninfo");
            $sheet->setCellValue("G$i", "Notiz Speise");

            /*  $sheet->getStyle("A$i:D$i")->getFill()->applyFromArray(
              array(
              'type' => PHPExcel_Style_Fill::FILL_SOLID,
              'startcolor' => array('rgb' => 'cccccc'),
              'endcolor' => array('rgb' => 'cccccc')
              )
              ); */
        } else {
            $add_str = '';
            if ($kunde->getAnzahlSpeisen() > 1) {
                $add_str = '(Speise 1)';
                if ($speise_nr > 1) {
                    $add_str = '(Speise ' . $speise_nr . ')';
                }
            }
            $kitafino_str = '';
            if ($kunde->getKundennummer()) {
                $kitafino_str = '{K} ';
            }
            $sheet->setCellValue("A$i", $kitafino_str . $kunde->getName() . ' ' . $add_str);
            $sheet->setCellValue("D$i", $portionen);
            $bemerkungen_str = '';
            $sheet->setCellValue("E$i", $menge_pro_portion->getMenge() * $faktor . ' ' . $menge_pro_portion->getEinheit());

            $gerade_bleche = '';
            if ($menge_pro_portion->getEinheit() == 'Blech') {
                //BLECHE aufrunden
                $zahl = number_format(round($gesamtmenge_kunde, 2), 2);
                $array = explode(".", $zahl);
                $nachkommastellen = $array[1];

                $zahl = roundTo($zahl, .50);
                $gerade_bleche = /* ' ('. */$zahl/* .') ' */;
                $gesamtmenge_kunde = $gerade_bleche;
            }

            $sheet->setCellValue("B$i", $gesamtmenge_kunde . ' ' . $menge_pro_portion->getEinheit());

            $bemerkung_zu_tag = $bemerkungzutagVerwaltung->findeAnhandVonKundeIdUndDatumUndSpeiseId($kunde->getId(), $tag, $monat, $jahr, $speise->getId());
            $bemerkung_zu_speise = $bemerkungzuspeiseVerwaltung->findeAnhandVonKundeIdUndSpeiseId($kunde->getId(), $speise->getId());

            $zusammenfassung_nach_touren_array[$tour_name][$kunde->getName()]['portionen'] = $portionen;

            if ($bemerkung_zu_speise === NULL) {
                $bemerkung_zu_speise = new BemerkungZuSpeise();
            }
            /* echo '<pre>';
              var_dump($bemerkung_zu_speise->getBemerkung(), $bemerkung_zu_tag->getBemerkung(), $kunde->getBemerkung() );
              echo '</pre>'; */

            if ($bemerkung_zu_speise->getBemerkung() != '' && $portionen > 0) {
                $zusammenfassung_nach_touren_array[$tour_name][$kunde->getName()]['notiz_speise'] = $bemerkung_zu_speise->getBemerkung();
                $bemerkungen_str .= $bemerkung_zu_speise->getBemerkung() . '; ';
            }
            if ($bemerkung_zu_tag->getBemerkung() != '') {
                $zusammenfassung_nach_touren_array[$tour_name][$kunde->getName()]['notiz_speise_tag'] = $bemerkung_zu_tag->getBemerkung();
                $bemerkungen_str .= $bemerkung_zu_tag->getBemerkung() . '; ';
            }
            if ($kunde->getBemerkung() != '') {
                $zusammenfassung_nach_touren_array[$tour_name][$kunde->getName()]['bemerkung_speisen_kunde'] = $kunde->getBemerkung();
                //$zusammenfassung_nach_touren_array[$tour_name][$kunde->getName()]['notiz_kunde'] = $kunde->getBemerkung();
                $bemerkungen_str .= $kunde->getBemerkung() . '; ';
            }
            if ($kunde->getBemerkungKunde() != '') {
                $zusammenfassung_nach_touren_array[$tour_name][$kunde->getName()]['bemerkung_kunde'] = $kunde->getBemerkungKunde();
            }

            $sheet->setCellValue("C$i", $kunde->getBemerkung());

            $bemerkung_kunde = $kunde->getBemerkungKunde();
            $bemerkung_kunde = str_replace('+sauber', '', $bemerkung_kunde);
            $bemerkung_kunde = str_replace('+ sauber', '', $bemerkung_kunde);
            $bemerkung_kunde = str_replace('sauber', '', $bemerkung_kunde);
            $sheet->setCellValue("F$i", $bemerkung_kunde);

            $speisen_notiz = $bemerkung_zu_speise->getBemerkung();
            if ($bemerkung_zu_tag->getBemerkung()) {
                $speisen_notiz .= ' | ' . $bemerkung_zu_tag->getBemerkung();
            }

            $sheet->setCellValue("G$i", $speisen_notiz);

            if ($bemerkung_zu_speise->getBemerkung() || $bemerkung_zu_tag->getBemerkung()) {

                $sheet->getStyle("A$i:G$i")->getFill()->applyFromArray(
                    array(
                        'type' => PHPExcel_Style_Fill::FILL_SOLID,
                        'startcolor' => array('rgb' => $orange),
                        'endcolor' => array('rgb' => $orange)
                    )
                );
            }

            if ($kunde->getBemerkung()) {
                $sheet->getStyle("C$i")->getFill()->applyFromArray(
                    array(
                        'type' => PHPExcel_Style_Fill::FILL_SOLID,
                        'startcolor' => array('rgb' => $gelb),
                        'endcolor' => array('rgb' => $gelb)
                    )
                );
            }
            if ($bemerkung_kunde) {
                $sheet->getStyle("F$i")->getFill()->applyFromArray(
                    array(
                        'type' => PHPExcel_Style_Fill::FILL_SOLID,
                        'startcolor' => array('rgb' => $blau),
                        'endcolor' => array('rgb' => $blau)
                    )
                );
                $sheet->getStyle("A$i")->getFill()->applyFromArray(
                    array(
                        'type' => PHPExcel_Style_Fill::FILL_SOLID,
                        'startcolor' => array('rgb' => $blau),
                        'endcolor' => array('rgb' => $blau)
                    )
                );
            }
        }
        if (!isset($zeile_tour_beginn[$tourbeginn_zeile]) && $tourbeginn_zeile > 0) {
            $zeile_tour_beginn[$tourbeginn_zeile] = 0;
        }
        if (!isset($portionen_zu_tour[$tourbeginn_zeile]) && $tourbeginn_zeile > 0) {
            $portionen_zu_tour[$tourbeginn_zeile] = 0;
        }

        if ($tourbeginn_zeile) {
            $zeile_tour_beginn[$tourbeginn_zeile] += $gesamtmenge_kunde;
            $portionen_zu_tour[$tourbeginn_zeile] += $portionen;
        }

        $sheet->getStyle("C$i")->getAlignment()->setWrapText(true);
        $sheet->getStyle("F$i")->getAlignment()->setWrapText(true);
        $sheet->getStyle("G$i")->getAlignment()->setWrapText(true);
        //  $sheet->setCellValue("E$i",$menge_pro_portion->getMenge() . ' ' . $menge_pro_portion->getEinheit() . ' /P');
        //  $sheet->setCellValue("F$i",$faktor);

        $i++;
        $gesamtmenge_kunde = $portionen * ($menge_pro_portion->getMenge() * $faktor);
        $gesamtmenge_tag_speise += $gesamtmenge_kunde;
        $last_tour = $tour_zu_kunde;
    }
    $sheet->getStyle("E4")->getFont()->setSize(12);
    $sheet->getStyle("D4")->getFont()->setSize(12);

//var_dump($zusammenfassung_nach_touren_array);



    /* $sheet->setCellValue("G$i", $speise->getBezeichnung().'TT');
      $sheet->getStyle("A$i:E$i")->applyFromArray($style_header_1); */
    $i++;
    $sheet->getStyle("A$i:E$i")->getFont()->setSize(15);

    $sheet->setCellValue("A$i", "Insgesamt", $format_bold);
    $sheet->setCellValue("D$i", $gesamtmenge_tag_portionen);
    $sheet->setCellValue("E$i", "");
    $sheet->setCellValue("B$i", $gesamtmenge_tag_speise . ' ' . $menge_pro_portion->getEinheit());

    $ttt = $i + 1;
    if ($menge_pro_portion->getEinheit() == 'g' || $menge_pro_portion->getEinheit() == 'ml') {
        $gesamtmenge_tag_speise_umger = $gesamtmenge_tag_speise / 1000;
        switch ($menge_pro_portion->getEinheit()) {
            case 'g':
                $einheit = 'kg';
                break;
            case 'ml':
                $einheit = 'L';
                break;
        }
    }

    $sheet->setCellValue("B$ttt", $gesamtmenge_tag_speise_umger . ' ' . $einheit);
    $sheet->getStyle("A2:D2")->applyFromArray($style_row_gesamt);
    $sheet->getStyle("A$i:D$i")->applyFromArray($style_row_gesamt);

    if ($speise->getRezept() != '') {
        $i += 5;
        $sheet->setCellValue("A$i", "REZEPT/BEMERK.:");
        $sheet->getStyle("A$i")->getFont()->setBold(true);
        $i++;
        $sheet->mergeCells("A$i:E$i");
        $sheet->setCellValue("A$i", $speise->getRezept());
    }
    $i++;
    foreach ($zeilen_mit_summe as $zeile_mit_summe) {
        //$sheet->setCellValue("A".$zeile_mit_summe, strftime('%d.%m.%Y', mktime(12, 0, 0, $monat, $tag, $jahr)));
        //$sheet->getStyle("A$zeile_mit_summe:E$zeile_mit_summe")->getFont()->setSize(14);
        $sheet->getStyle("A$zeile_mit_summe:D$zeile_mit_summe")->applyFromArray($style_row_gesamt);
        $sheet->setCellValue("A$zeile_mit_summe", "Insgesamt", $format_bold);
        $sheet->setCellValue("D$zeile_mit_summe", $gesamtmenge_tag_portionen);
        $sheet->setCellValue("E$zeile_mit_summe", "");
        $sheet->setCellValue("B$zeile_mit_summe", $gesamtmenge_tag_speise . ' ' . $menge_pro_portion->getEinheit());
        $sheet->getStyle("A$zeile_mit_summe:D$zeile_mit_summe")->applyFromArray($style_row_gesamt);
    }
    foreach ($zeile_tour_beginn as $key_zeile => $menge_diese_tour) {
        $zeile_gesamt_tour = $key_zeile + 3;
        $sheet->setCellValue("D" . $zeile_gesamt_tour, $portionen_zu_tour[$key_zeile]);
        $sheet->setCellValue("B" . $zeile_gesamt_tour, $menge_diese_tour . ' ' . $menge_pro_portion->getEinheit());
        $sheet->getStyle("A$zeile_gesamt_tour:D$zeile_gesamt_tour")->applyFromArray($style_row_gesamt);
    }

    $sheet->setCellValue("A1", strftime('%a %d.%m.%Y', mktime(12, 0, 0, $monat, $tag, $jahr)));
    $sheet->getStyle("A1")->getFont()->setSize(14);

    $sheet->mergeCells("B1:G1");
    $sheet->setCellValue("B1", 'Erstellt: ' . strftime('%a %d.%m.%Y - %H:%M Uhr', mktime(date('H'), date('i'), date('s'), date('m'), date('d'), date('Y'))));
    $sheet->getStyle("B1:G1")->getFont()->setSize(12);
    $sheet->getStyle('B1')->getAlignment()->applyFromArray(
        array('horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER,)
    );

    $sheet->setCellValue("A2", "Insgesamt", $format_bold);
    $sheet->setCellValue("D2", $gesamtmenge_tag_portionen);
    $sheet->setCellValue("E2", "");
    $sheet->setCellValue("B2", $gesamtmenge_tag_speise . ' ' . $menge_pro_portion->getEinheit());

    $sheet->getColumnDimension('A')->setWidth(45);
    $sheet->getColumnDimension('D')->setWidth(7);
    $sheet->getColumnDimension('E')->setWidth(8);
    $sheet->getColumnDimension('B')->setWidth(19);
    $sheet->getColumnDimension('C')->setWidth(14);
    $sheet->getColumnDimension('F')->setWidth(19);
    $sheet->getColumnDimension('G')->setWidth(19);

    //Zusammenfasung
    $i++;
    $i++;
    $i++;
    $bl = $i - 1;
    $break_line = "A" . $bl;
    $sheet->setBreak($break_line, PHPExcel_Worksheet::BREAK_ROW);
    $sheet->setCellValue("A$i", 'Checkliste Besonderheiten am ' . strftime('%d.%m.%Y', mktime(12, 0, 0, $monat, $tag, $jahr)) . ' - ' . $speise->getBezeichnung());
    $sheet->getStyle("A$i")->applyFromArray($style_head_summary);
    $sheet->getStyle("A$i")->getFont()->setSize(18);
    $sheet->mergeCells("A$i:G$i");
    $i++;
    foreach ($zusammenfassung_nach_touren_array as $tourname_key => $tourinfos_array) {
        $show_tour = false;
        foreach ($tourinfos_array as $kunde_in_tour) {
            if (substr($kunde_in_tour['portionen'], 0, 1) > 0 && ($kunde_in_tour['notiz_speise'] != NULL || $kunde_tour['notiz_speise_tag'] != NULL || $kunde_tour['bemerkung_speisen_kunde '] != NULL)) {
                $show_tour = true;
            }
        }

        if (!$show_tour) {
            $anzahl_kunden--;
            continue;
        } else {
            if (count($tourinfos_array) > 0) {
                $sheet->setCellValue("A$i", $tourname_key);
                $sheet->mergeCells("A$i:G$i");
                $sheet->getStyle("A$i")->applyFromArray($style_tour);
                $i++;
            }
        }

        foreach ($tourinfos_array as $kunde_key => $kunde_tour) {
            if (substr($kunde_tour['portionen'], 0, 1) == '0' || $kunde_tour['notiz_speise'] == NULL) {
                $anzahl_kunden--;
                continue;
            }
            if ($i % 2) {
                $sheet->getStyle("A$i")->applyFromArray($style_col1);
                $sheet->getStyle("B$i")->applyFromArray($style_row);
                $sheet->getStyle("C$i")->applyFromArray($style_row);
                $sheet->getStyle("D$i")->applyFromArray($style_row);
                $sheet->getStyle("E$i")->applyFromArray($style_row);
                $sheet->getStyle("F$i")->applyFromArray($style_row);
                $sheet->getStyle("G$i")->applyFromArray($style_row);
            } else {
                $sheet->getStyle("A$i")->applyFromArray($style_col1_2);
                $sheet->getStyle("B$i")->applyFromArray($style_row_2);
                $sheet->getStyle("C$i")->applyFromArray($style_row_2);
                $sheet->getStyle("D$i")->applyFromArray($style_row_2);
                $sheet->getStyle("E$i")->applyFromArray($style_row_2);
                $sheet->getStyle("F$i")->applyFromArray($style_row_2);
                $sheet->getStyle("G$i")->applyFromArray($style_row_2);
            }

            $sheet->setCellValue("A$i", $kunde_key . ' ' . substr($kunde_tour['portionen'], 0, 1));
            if (isset($kunde_tour['bemerkung_speisen_kunde'])) {
                $sheet->setCellValue("E$i", $kunde_tour['bemerkung_speisen_kunde']);
            }
            if (isset($kunde_tour['bemerkung_kunde'])) {
                $sheet->setCellValue("F$i", $kunde_tour['bemerkung_kunde']);
            }
            if (isset($kunde_tour['notiz_speise']) || isset($kunde_tour['notiz_speise_tag'])) {
                $notiz_string = $kunde_tour['notiz_speise'];
                if ($kunde_tour['notiz_speise_tag']) {
                    $notiz_string .= ' | ' . $kunde_tour['notiz_speise_tag'];
                }
                $sheet->setCellValue("G$i", $notiz_string);
            }
            $sheet->getStyle("E$i")->getAlignment()->setWrapText(true);
            $sheet->getStyle("F$i")->getAlignment()->setWrapText(true);
            $sheet->getStyle("G$i")->getAlignment()->setWrapText(true);
            $i++;
        }
    }

    $i++;
    $i++;

    $style_bold = array(
        'font' => array(
            'bold' => true,
            'size' => 13
        ));

    $sheet->setCellValue("A$i", 'Die Besonderheiten wurden berücksichtigt:');
    $sheet->getStyle("A$i")->applyFromArray($style_bold);
    $sheet->mergeCells("A$i:F$i");
    $i++;
    $sheet->getStyle("A$i")->applyFromArray($style_handtext);
    $sheet->mergeCells("A$i:B$i");
    $sheet->getRowDimension($i)->setRowHeight(30);
    $i++;
    $sheet->setCellValue("A$i", 'Unterschrift');
    $sheet->mergeCells("A$i:F$i");
    $i++;
    $sheet->getRowDimension($i)->setRowHeight(10);
    $i++;
    $sheet->setCellValue("A$i", 'Bemerkungen:');
    $sheet->getStyle("A$i")->applyFromArray($style_bold);
    $sheet->mergeCells("B$i:F$i");
    $sheet->getStyle("A$i:F$i")->applyFromArray($style_handtext);
    $i++;
    $sheet->getStyle("A$i")->applyFromArray($style_handtext);
    $sheet->mergeCells("A$i:F$i");
    $sheet->getRowDimension($i)->setRowHeight(30);

    /* $i++;
      $sheet->getStyle("A$i")->applyFromArray($style_handtext);
      $sheet->mergeCells("A$i:F$i");
      $sheet->getRowDimension($i)->setRowHeight(30); */

    $writer = PHPExcel_IOFactory::createWriter($xls, "Excel5");

    $heute = strftime('%d_%m_%Y', time());
    $rechner = php_uname('n');
    $speise_bezeichnung = $speise->getBezeichnung();
    $speise_bezeichnung = umlautepas($speise_bezeichnung);
    //var_dump($speise_bezeichnung);
    $speise_bezeichnung = entferneSonderzeichen($speise_bezeichnung);
    if ($_SESSION['is_local_server']) {
        $writer->save('export_tagesaufstellungen/' . $speise_bezeichnung . '_' . $speise_nr . '_' . strftime('%d_%m_%y', $ts) . ".xls");
    } else {
        $writer->save('/is/htdocs/wp1055935_1F55KTNHIC/www/sbar/verwaltung/export_tagesaufstellungen/' . $speise_bezeichnung . '_' . $speise_nr . '_' . strftime('%d_%m_%y', $ts) . ".xls");
    }
    if ($_SESSION['is_local_server']) {
        header('location:export_tagesaufstellungen/' . $speise_bezeichnung . '_' . $speise_nr . '_' . strftime('%d_%m_%y', $ts) . ".xls");
        exit;
    } else {
        header('location:http://www.s-bar.net/verwaltung/export_tagesaufstellungen/' . $speise_bezeichnung . '_' . $speise_nr . '_' . strftime('%d_%m_%y', $ts) . ".xls");
        exit;
    }
}

function findePortionenZuTagUndKunde($kunde_id, $tagts, $kundeVerwaltung, $standardportionenVerwaltung, $portionenaenderungVerwaltung) {
    $standardportionen = $standardportionenVerwaltung->findeAnhandVonKundenId($kunde_id);
    $wochentag_string = strftime('%a', $tagts);
    switch ($wochentag_string) {
        case 'Mo':
            $wochenstartts = $tagts;
            break;
        case 'Di':
            $wochenstartts = $tagts - 86400;
            break;
        case 'Mi':
            $wochenstartts = $tagts - 86400 * 2;
            break;
        case 'Do':
            $wochenstartts = $tagts - 86400 * 3;
            break;
        case 'Fr':
            $wochenstartts = $tagts - 86400 * 4;
            break;
    }
    $portionenaenderung = $portionenaenderungVerwaltung->findeAnhandVonKundenIdUndWochenstarttagts($kunde_id, $wochenstartts);

    if ($portionenaenderung->getId()) {
        $portionen_mo = $portionenaenderung->getPortionenMo();
        $portionen_di = $portionenaenderung->getPortionenDi();
        $portionen_mi = $portionenaenderung->getPortionenMi();
        $portionen_do = $portionenaenderung->getPortionenDo();
        $portionen_fr = $portionenaenderung->getPortionenFr();
        $aenderung = true;
    } else {
        $portionen_mo = $standardportionen->getPortionenMo();
        $portionen_di = $standardportionen->getPortionenDi();
        $portionen_mi = $standardportionen->getPortionenMi();
        $portionen_do = $standardportionen->getPortionenDo();
        $portionen_fr = $standardportionen->getPortionenFr();
        $aenderung = false;
    }

    switch ($wochentag_string) {
        case 'Mo':
            $portionen = $portionen_mo;
            break;
        case 'Di':
            $portionen = $portionen_di;
            break;
        case 'Mi':
            $portionen = $portionen_mi;
            break;
        case 'Do':
            $portionen = $portionen_do;
            break;
        case 'Fr':
            $portionen = $portionen_fr;
            break;
    }


    return $portionen;
}

function findePortionenZuDatumUndKunde($kunde_id, $tag, $monat, $jahr, $kundeVerwaltung, $standardportionenVerwaltung, $portionenaenderungVerwaltung) {
    $standardportionen = $standardportionenVerwaltung->findeAnhandVonKundenId($kunde_id);

    $tag_in_angezeigter_woche_ts = mktime(12, 0, 0, $monat, $tag, $jahr);

    $wochentag_string = strftime('%a', mktime(12, 0, 0, $monat, $tag, $jahr));
    switch ($wochentag_string) {
        case 'Sa':
            $start_tag_woche = date('d', $tag_in_angezeigter_woche_ts + 86400 * 2);
            $start_monat_woche = date('m', $tag_in_angezeigter_woche_ts + 86400 * 2);
            $start_jahr_woche = date('Y', $tag_in_angezeigter_woche_ts + 86400 * 2);
            //$wochenstarttage['woche1'] = $wochenstarttag_ts = $heute + 86400*2;
            break;
        case 'So':
            //$wochenstarttage['woche1'] = $wochenstarttag_ts = $heute + 86400*1;
            $start_tag_woche = date('d', $tag_in_angezeigter_woche_ts + 86400 * 1);
            $start_monat_woche = date('m', $tag_in_angezeigter_woche_ts + 86400 * 1);
            $start_jahr_woche = date('Y', $tag_in_angezeigter_woche_ts + 86400 * 1);
            break;
        case 'Mo':
            //$wochenstarttage['woche1'] = $wochenstarttag_ts = $heute + 86400*0;
            $start_tag_woche = date('d', $tag_in_angezeigter_woche_ts + 86400 * 0);
            $start_monat_woche = date('m', $tag_in_angezeigter_woche_ts + 86400 * 0);
            $start_jahr_woche = date('Y', $tag_in_angezeigter_woche_ts + 86400 * 0);
            break;
        case 'Di':
            //$wochenstarttage['woche1'] = $wochenstarttag_ts = $heute - 86400*1;
            $start_tag_woche = date('d', $tag_in_angezeigter_woche_ts - 86400 * 1);
            $start_monat_woche = date('m', $tag_in_angezeigter_woche_ts - 86400 * 1);
            $start_jahr_woche = date('Y', $tag_in_angezeigter_woche_ts - 86400 * 1);
            break;
        case 'Mi':
            //$wochenstarttage['woche1'] = $wochenstarttag_ts = $heute - 86400*2;
            $start_tag_woche = date('d', $tag_in_angezeigter_woche_ts - 86400 * 2);
            $start_monat_woche = date('m', $tag_in_angezeigter_woche_ts - 86400 * 2);
            $start_jahr_woche = date('Y', $tag_in_angezeigter_woche_ts - 86400 * 2);
            break;
        case 'Do':
            //$wochenstarttage['woche1'] = $wochenstarttag_ts = $heute - 86400*3;
            $start_tag_woche = date('d', $tag_in_angezeigter_woche_ts - 86400 * 3);
            $start_monat_woche = date('m', $tag_in_angezeigter_woche_ts - 86400 * 3);
            $start_jahr_woche = date('Y', $tag_in_angezeigter_woche_ts - 86400 * 3);
            break;
        case 'Fr':
            //$wochenstarttage['woche1'] = $wochenstarttag_ts = $heute - 86400*4;
            $start_tag_woche = date('d', $tag_in_angezeigter_woche_ts - 86400 * 4);
            $start_monat_woche = date('m', $tag_in_angezeigter_woche_ts - 86400 * 4);
            $start_jahr_woche = date('Y', $tag_in_angezeigter_woche_ts - 86400 * 4);
            break;
    }
    $portionenaenderung = $portionenaenderungVerwaltung->findeAnhandVonKundenIdUndWochenstartdatum($kunde_id, $start_tag_woche, $start_monat_woche, $start_jahr_woche, 1);

    if ($portionenaenderung->getId()) {
        $portionen_mo = $portionenaenderung->getPortionenMo();
        $portionen_di = $portionenaenderung->getPortionenDi();
        $portionen_mi = $portionenaenderung->getPortionenMi();
        $portionen_do = $portionenaenderung->getPortionenDo();
        $portionen_fr = $portionenaenderung->getPortionenFr();
        $aenderung = true;
    } else {
        $portionen_mo = $standardportionen->getPortionenMo();
        $portionen_di = $standardportionen->getPortionenDi();
        $portionen_mi = $standardportionen->getPortionenMi();
        $portionen_do = $standardportionen->getPortionenDo();
        $portionen_fr = $standardportionen->getPortionenFr();
        $aenderung = false;
    }

    switch ($wochentag_string) {
        case 'Mo':
            $portionen = $portionen_mo;
            break;
        case 'Di':
            $portionen = $portionen_di;
            break;
        case 'Mi':
            $portionen = $portionen_mi;
            break;
        case 'Do':
            $portionen = $portionen_do;
            break;
        case 'Fr':
            $portionen = $portionen_fr;
            break;
    }


    return $portionen;
}

function findePortionenZuDatumUndKundeSpeise2($kunde_id, $tag, $monat, $jahr, $kundeVerwaltung, $standardportionenVerwaltung, $portionenaenderungVerwaltung, $speise_nr) {
    $standardportionen = $standardportionenVerwaltung->findeAnhandVonKundenIdUndSpeiseNr($kunde_id, $speise_nr);

    $tag_in_angezeigter_woche_ts = mktime(12, 0, 0, $monat, $tag, $jahr);

    $wochentag_string = strftime('%a', mktime(12, 0, 0, $monat, $tag, $jahr));
    switch ($wochentag_string) {
        case 'Sa':
            $start_tag_woche = date('d', $tag_in_angezeigter_woche_ts + 86400 * 2);
            $start_monat_woche = date('m', $tag_in_angezeigter_woche_ts + 86400 * 2);
            $start_jahr_woche = date('Y', $tag_in_angezeigter_woche_ts + 86400 * 2);
            //$wochenstarttage['woche1'] = $wochenstarttag_ts = $heute + 86400*2;
            break;
        case 'So':
            //$wochenstarttage['woche1'] = $wochenstarttag_ts = $heute + 86400*1;
            $start_tag_woche = date('d', $tag_in_angezeigter_woche_ts + 86400 * 1);
            $start_monat_woche = date('m', $tag_in_angezeigter_woche_ts + 86400 * 1);
            $start_jahr_woche = date('Y', $tag_in_angezeigter_woche_ts + 86400 * 1);
            break;
        case 'Mo':
            //$wochenstarttage['woche1'] = $wochenstarttag_ts = $heute + 86400*0;
            $start_tag_woche = date('d', $tag_in_angezeigter_woche_ts + 86400 * 0);
            $start_monat_woche = date('m', $tag_in_angezeigter_woche_ts + 86400 * 0);
            $start_jahr_woche = date('Y', $tag_in_angezeigter_woche_ts + 86400 * 0);
            break;
        case 'Di':
            //$wochenstarttage['woche1'] = $wochenstarttag_ts = $heute - 86400*1;
            $start_tag_woche = date('d', $tag_in_angezeigter_woche_ts - 86400 * 1);
            $start_monat_woche = date('m', $tag_in_angezeigter_woche_ts - 86400 * 1);
            $start_jahr_woche = date('Y', $tag_in_angezeigter_woche_ts - 86400 * 1);
            break;
        case 'Mi':
            //$wochenstarttage['woche1'] = $wochenstarttag_ts = $heute - 86400*2;
            $start_tag_woche = date('d', $tag_in_angezeigter_woche_ts - 86400 * 2);
            $start_monat_woche = date('m', $tag_in_angezeigter_woche_ts - 86400 * 2);
            $start_jahr_woche = date('Y', $tag_in_angezeigter_woche_ts - 86400 * 2);
            break;
        case 'Do':
            //$wochenstarttage['woche1'] = $wochenstarttag_ts = $heute - 86400*3;
            $start_tag_woche = date('d', $tag_in_angezeigter_woche_ts - 86400 * 3);
            $start_monat_woche = date('m', $tag_in_angezeigter_woche_ts - 86400 * 3);
            $start_jahr_woche = date('Y', $tag_in_angezeigter_woche_ts - 86400 * 3);
            break;
        case 'Fr':
            //$wochenstarttage['woche1'] = $wochenstarttag_ts = $heute - 86400*4;
            $start_tag_woche = date('d', $tag_in_angezeigter_woche_ts - 86400 * 4);
            $start_monat_woche = date('m', $tag_in_angezeigter_woche_ts - 86400 * 4);
            $start_jahr_woche = date('Y', $tag_in_angezeigter_woche_ts - 86400 * 4);
            break;
    }
    $portionenaenderung = $portionenaenderungVerwaltung->findeAnhandVonKundenIdUndWochenstartdatum($kunde_id, $start_tag_woche, $start_monat_woche, $start_jahr_woche, $speise_nr);

    if ($portionenaenderung->getId()) {
        $portionen_mo = $portionenaenderung->getPortionenMo();
        $portionen_di = $portionenaenderung->getPortionenDi();
        $portionen_mi = $portionenaenderung->getPortionenMi();
        $portionen_do = $portionenaenderung->getPortionenDo();
        $portionen_fr = $portionenaenderung->getPortionenFr();
        $aenderung = true;
    } else {
        $portionen_mo = $standardportionen->getPortionenMo();
        $portionen_di = $standardportionen->getPortionenDi();
        $portionen_mi = $standardportionen->getPortionenMi();
        $portionen_do = $standardportionen->getPortionenDo();
        $portionen_fr = $standardportionen->getPortionenFr();
        $aenderung = false;
    }

    switch ($wochentag_string) {
        case 'Mo':
            $portionen = $portionen_mo;
            break;
        case 'Di':
            $portionen = $portionen_di;
            break;
        case 'Mi':
            $portionen = $portionen_mi;
            break;
        case 'Do':
            $portionen = $portionen_do;
            break;
        case 'Fr':
            $portionen = $portionen_fr;
            break;
    }


    return $portionen;
}

function findeGeaendertePortionenZuTagUndKunde($kunde_id, $tag, $monat, $jahr, $kundeVerwaltung, $standardportionenVerwaltung, $portionenaenderungVerwaltung) {
    $standardportionen = $standardportionenVerwaltung->findeAnhandVonKundenId($kunde_id);
    $wochentag_string = strftime('%a', mktime(12, 0, 0, $monat, $tag, $jahr));

    $tag_in_angezeigter_woche_ts = mktime(12, 0, 0, $monat, $tag, $jahr);
    $tagts = $tag_in_angezeigter_woche_ts;
    switch ($wochentag_string) {
        case 'Mo':
            $portionen = $standardportionen->getPortionenMo();
            $start_tag_woche = date('d', $tag_in_angezeigter_woche_ts + 86400 * 0);
            $start_monat_woche = date('m', $tag_in_angezeigter_woche_ts + 86400 * 0);
            $start_jahr_woche = date('Y', $tag_in_angezeigter_woche_ts + 86400 * 0);
            $wochenstartts = $tagts;
            break;
        case 'Di':
            $portionen = $standardportionen->getPortionenDi();
            $start_tag_woche = date('d', $tag_in_angezeigter_woche_ts - 86400 * 1);
            $start_monat_woche = date('m', $tag_in_angezeigter_woche_ts - 86400 * 1);
            $start_jahr_woche = date('Y', $tag_in_angezeigter_woche_ts - 86400 * 1);
            $wochenstartts = $tagts - 86400;
            break;
        case 'Mi':
            $portionen = $standardportionen->getPortionenMi();
            $start_tag_woche = date('d', $tag_in_angezeigter_woche_ts - 86400 * 1);
            $start_monat_woche = date('m', $tag_in_angezeigter_woche_ts - 86400 * 1);
            $start_jahr_woche = date('Y', $tag_in_angezeigter_woche_ts - 86400 * 1);
            $wochenstartts = $tagts - 86400 * 2;
            break;
        case 'Do':
            $portionen = $standardportionen->getPortionenDo();
            $start_tag_woche = date('d', $tag_in_angezeigter_woche_ts - 86400 * 3);
            $start_monat_woche = date('m', $tag_in_angezeigter_woche_ts - 86400 * 3);
            $start_jahr_woche = date('Y', $tag_in_angezeigter_woche_ts - 86400 * 3);
            $wochenstartts = $tagts - 86400 * 3;
            break;
        case 'Fr':
            $portionen = $standardportionen->getPortionenFr();
            $start_tag_woche = date('d', $tag_in_angezeigter_woche_ts - 86400 * 4);
            $start_monat_woche = date('m', $tag_in_angezeigter_woche_ts - 86400 * 4);
            $start_jahr_woche = date('Y', $tag_in_angezeigter_woche_ts - 86400 * 4);
            $wochenstartts = $tagts - 86400 * 4;
            break;
    }


    $portionenaenderung = $portionenaenderungVerwaltung->findeAnhandVonKundenIdUndWochenstartdatum($kunde_id, $start_tag_woche, $start_monat_woche, $start_jahr_woche);

    if ($portionenaenderung->getId()) {
        switch ($wochentag_string) {
            case 'Mo':
                $portionen = $portionenaenderung->getPortionenMo();
                break;
            case 'Di':
                $portionen = $portionenaenderung->getPortionenDi();
                break;
            case 'Mi':
                $portionen = $portionenaenderung->getPortionenMi();
                break;
            case 'Do':
                $portionen = $portionenaenderung->getPortionenDo();
                break;
            case 'Fr':
                $portionen = $portionenaenderung->getPortionenFr();
                break;
        }
    }

    return $portionen;
}

function pruefeAufFehlendeMengen($speisen, $speiseVerwaltung, $einrichtungskategorieVerwaltung, $menge_pro_portionVerwaltung) {
    $einrichtungen = $einrichtungskategorieVerwaltung->findeAlle();
    $z = 0;
    $fehler_in = array();
    foreach ($speisen as $speise) {
        foreach ($einrichtungen as $einrichtung) {
            $menge_pro_portion_check = $menge_pro_portionVerwaltung->findeAnhandVonSpeiseIdUndEinrichtungskategorieId($speise->getId(), $einrichtung->getId());
            if ($menge_pro_portion_check->getMenge() == 0) {
                $fehler_in[] = array($speise->getId(), $einrichtung->getId());
            }
        }
        $z++;
    }
    return $fehler_in;
}

function erzeugeAbrechnungExcel($monat, $jahr, $kunde_id, $kundeVerwaltung, $abrechnungstagVerwaltung, $speiseVerwaltung) {
    /* require_once('../verwaltung_alt/PHPExcel-1.7.5/PHPExcel.php');
     require_once('../verwaltung_alt/PHPExcel-1.7.5/PHPExcel/IOFactory.php');*/

    require '../PhpSpreadsheet/PhpOffice/autoload.php';

    $kunde = $kundeVerwaltung->findeAnhandVonId($kunde_id);
    $abrechnungstage = $abrechnungstagVerwaltung->findeAlleZuMonatUndKunde($monat, $jahr, $kunde_id);

    // neue instanz erstellen
    //$xls = new PHPExcel();
    $xls = new \PhpOffice\PhpSpreadsheet\Spreadsheet();
    // dokumenteigenschaften schreiben
    $xls->getProperties()->setCreator("S-Bar")
        ->setLastModifiedBy("S-Bar")
        ->setTitle("Abrechnung $monat/$jahr")
        ->setSubject("Abrechnung $monat/$jahr");
    // das erste worksheet anwaehlen
    $sheet = $xls->setActiveSheetIndex(0);

    //STYLES START
    $sheet->getStyle('A1')->getFont()->setBold(true);
    $sheet->getStyle('A2:D2')->getFont()->setBold(true);
    $sheet->getColumnDimension('A')->setWidth(30);
    $sheet->getColumnDimension('B')->setWidth(15);
    $sheet->getColumnDimension('C')->setWidth(15);
    $sheet->getColumnDimension('D')->setWidth(15);
    $sheet->getColumnDimension('A')->setAutoSize(false);

    $default_border = array(
        'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
        'color' => array('rgb' => 'BEBEBE')
    );
    $style_header_1 = array(
        'borders' => array(
            'bottom' => $default_border,
            'left' => $default_border,
            'top' => $default_border,
            'right' => $default_border,
        ),
        'fill' => array(
            'fillType' => PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
            'color' => array('rgb' => 'FF0000'),
        ),
        'font' => array(
            'bold' => true,
            'color' => array('rgb' => 'ffffff'),
        ),
        'alignment' => array(
            'indent' => 0.5,
        )
    );
    $style_header = array(
        'borders' => array(
            'bottom' => $default_border,
            'left' => $default_border,
            'top' => $default_border,
            'right' => $default_border,
        ),
        'fill' => array(
            'fillType' => PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
            'color' => array('rgb' => '429E3A'),
        ),
        'font' => array(
            'bold' => true,
            'color' => array('rgb' => 'ffffff'),
        ),
        'alignment' => array(
            'indent' => 0.5,
        )
    );
    $style_row = array(
        'borders' => array(
            'bottom' => $default_border,
            'left' => $default_border,
            'top' => $default_border,
            'right' => $default_border,
        ),
        'fill' => array(
            'fillType' => PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
            'color' => array('rgb' => 'E1E0F7'),
        ),
        'font' => array(
            'bold' => false,
            'color' => array('rgb' => '000000'),
        ),
        'alignment' => array(
            'horizontal' => PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_LEFT,
            'vertical' => PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_TOP,
        )
    );
    $style_row_gesamt = array(
        'borders' => array(
            'bottom' => $default_border,
            'left' => $default_border,
            'top' => $default_border,
            'right' => $default_border,
        ),
        'fill' => array(
            'fillType' => PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
            'color' => array('rgb' => 'C3C2D9'),
        ),
        'font' => array(
            'bold' => true,
        ),
        'alignment' => array(
            'horizontal' => PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER,
            'indent' => 0.5,
        )
    );

    $style_row_durchgestr = array(
        'borders' => array(
            'bottom' => $default_border,
            'left' => $default_border,
            'top' => $default_border,
            'right' => $default_border,
        ),
        'fill' => array(
            'fillType' => PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
            'color' => array('rgb' => 'E1E0F7'),
        ),
        'font' => array(
            'bold' => true,
            'strikethrough' => true,
            'color' => array('rgb' => '888888'),
        ),
        'alignment' => array(
            'indent' => 0.5,
        )
    );
    $style_col1 = array(
        'borders' => array(
            'bottom' => $default_border,
            'left' => $default_border,
            'top' => $default_border,
            'right' => $default_border,
        ),
        'fill' => array(
            'fillType' => PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
            'color' => array('rgb' => 'E1E0F7'),
        ),
        'font' => array(
            'bold' => true,
            'color' => array('rgb' => '000000'),
        ),
        'alignment' => array(
            'horizontal' => PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_LEFT,
            'vertical' => PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_TOP,
            'indent' => 0.5,
        )
    );

    $sheet->getStyle('A1:C1')->applyFromArray($style_header_1);
    $sheet->getStyle('A2:C2')->applyFromArray($style_header);

    //STYLES ENDE
    // den namen vom Worksheet 1 definieren
    $xls->getActiveSheet()->setTitle("Abrechnung " . strftime('%d.%m.%y', $von));
    $von_tag = strftime('%d', $von);
    $bis_tag = strftime('%d', $bis);

    $sheet->setCellValue("A1", $kunde->getKundennummer());
    $sheet->setCellValue("B1", $kunde->getName() . ' ' . $monat . '/' . $jahr);
    $sheet->setCellValue("C1", 'Lex.: ' . $kunde->getLexware());

    $t = 2;
    $sheet->setCellValue("A$t", 'Tag');
    $sheet->setCellValue("B$t", "Speisen");
    $sheet->setCellValue("C$t", "Portionen");

    $portionen_monat_gesamt = 0;

    foreach ($abrechnungstage as $abrechnungstag) {
        if ($abrechnungstag->getPortionen() > 0) {
            $c = $t + 1;
            $sheet->setCellValue("A$c", strftime('%a %d.', mktime(12, 0, 0, $abrechnungstag->getMonat(), $abrechnungstag->getTag2(), $abrechnungstag->getJahr())));
            $speisen_ids = $abrechnungstag->getSpeisenIds();
            $speisen_ids = explode(', ', $speisen_ids);
            $speisen_array = array();
            foreach ($speisen_ids as $speise_id) {
                $speise = $speiseVerwaltung->findeAnhandVonId($speise_id);
                $speisen_array[] = $speise->getBezeichnung();
            }
            $add_string = '';
            if ($kunde->getAnzahlSpeisen() > 1) {
                $add_string = 'Speise ' . $abrechnungstag->getSpeiseNr() . ': ';
            }
            $sheet->setCellValue("B$c", $add_string . implode(', ', $speisen_array));
            $sheet->setCellValue("C$c", $abrechnungstag->getPortionen());
            $portionen_monat_gesamt += $abrechnungstag->getPortionen();

            $sheet->getStyle("A$c")->applyFromArray($style_col1);
            $sheet->getStyle("B$c")->applyFromArray($style_row);
            $sheet->getStyle("C$c")->applyFromArray($style_row);
            $sheet->getStyle("B$t")->getAlignment()->setWrapText(true);
            $t++;
        }
    }
    $c++;
    $sheet->setCellValue("A$c", 'Gesamt');
    $sheet->setCellValue("C$c", $portionen_monat_gesamt);
    $sheet->getStyle("A$c:C$c")->applyFromArray($style_row_gesamt);
    // $sheet->setCellValue("E$d","Menge/P Standard");
    //$sheet->setCellValue("F$d","Faktor");
    //$xls->getActiveSheet()->getColumnDimension('A')->setAutoSize(true);
    //$xls->getActiveSheet()->getColumnDimension('B')->setAutoSize(true);
    //$xls->getActiveSheet()->getColumnDimension('C')->setAutoSize(true);


    $sheet->getColumnDimension('A')->setWidth(10);
    $sheet->getColumnDimension('B')->setWidth(70);
    $sheet->getColumnDimension('C')->setWidth(10);

    $writer = PhpOffice\PhpSpreadsheet\IOFactory::createWriter($xls, "Xls");
    ob_end_clean();

    $heute = strftime('%d_%m_%Y', time());
    $rechner = php_uname('n');
    if ($_SESSION['is_local_server']) {
        $writer->save('export_abrechnungen/Abrg_' . $kunde->getKundennummer() . '_' . $monat . '_' . $jahr . ".xls");
    } else {
        $writer->save('/is/htdocs/wp1055935_1F55KTNHIC/www/sbar/verwaltung/export_abrechnungen/Abrg_' . $kunde->getKundennummer() . '_' . $monat . '_' . $jahr . ".xls");
    }

    if ($_SESSION['is_local_server']) {
        header('location:export_abrechnungen/Abrg_' . $kunde->getKundennummer() . '_' . $monat . '_' . $jahr . ".xls");
    } else {
        header('location:http://www.s-bar.net/verwaltung/export_abrechnungen/Abrg_' . $kunde->getKundennummer() . '_' . $monat . '_' . $jahr . ".xls");
    }

    /* if ($_SESSION['is_local_server']) {
      $writer->save('export_abrechnungen/Abrg_' . $kunde->getKundennummer() . '_' . $monat . '_' . $jahr . ".xls");
      } else {
      $writer->save('/is/htdocs/wp1055935_1F55KTNHIC/www/sbar/verwaltung/export_abrechnungen/Abrg_' . $kunde->getKundennummer() . '_' . $monat . '_' . $jahr . ".xls");
      }
      if ($_SESSION['is_local_server']) {
      header('location:export_abrechnungen/Abrg_' . $kunde->getKundennummer() . '_' . $monat . '_' . $jahr . ".xls");
      } else {
      header('location:http://www.s-bar.net/verwaltung/export_abrechnungen/Abrg_' . $kunde->getKundennummer() . '_' . $monat . '_' . $jahr . ".xls");
      } */
}

function erzeugeAbrechnungUebersichtExcel($daten, $monat, $jahr) {
    /* require_once('../verwaltung_alt/PHPExcel-1.7.5/PHPExcel.php');
       require_once('../verwaltung_alt/PHPExcel-1.7.5/PHPExcel/IOFactory.php');*/

    require 'PhpSpreadsheet/PhpOffice/autoload.php';

    //$xls = new PHPExcel();
    $xls = new \PhpOffice\PhpSpreadsheet\Spreadsheet();
    $xls->getProperties()->setCreator("S-Bar")
        ->setLastModifiedBy("S-Bar")
        ->setTitle("Abrechnung $monat/$jahr")
        ->setSubject("Abrechnung $monat/$jahr");
    $sheet = $xls->setActiveSheetIndex(0);

    $sheet->getStyle('A1')->getFont()->setBold(true);
    $sheet->getStyle('A2:E2')->getFont()->setBold(true);
    $sheet->getColumnDimension('A')->setWidth(40);
    $sheet->getColumnDimension('B')->setWidth(10);
    $sheet->getColumnDimension('C')->setWidth(15);
    $sheet->getColumnDimension('D')->setWidth(15);
    $sheet->getColumnDimension('E')->setWidth(15);
    //  $sheet->getColumnDimension('A')->setAutoSize(true);
    //  $sheet->getColumnDimension('B')->setAutoSize(true);
    // $sheet->getColumnDimension('C')->setAutoSize(true);
    $sheet->getColumnDimension('D')->setAutoSize(true);

    $default_border = array(
        'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_HAIR,
        'color' => array('rgb' => 'BEBEBE')
    );
    $style_header_1 = array(
        'borders' => array(
            'bottom' => $default_border,
            'left' => $default_border,
            'top' => $default_border,
            'right' => $default_border,
        ),
        'fill' => array(
            'fillType' => PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
            'color' => array('rgb' => 'FF0000'),
        ),
        'font' => array(
            'bold' => true,
            'color' => array('rgb' => 'ffffff'),
        ),
        'alignment' => array(
            'indent' => 0.5,
        )
    );
    $style_header = array(
        'borders' => array(
            'bottom' => $default_border,
            'left' => $default_border,
            'top' => $default_border,
            'right' => $default_border,
        ),
        'fill' => array(
            'fillType' => PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
            'color' => array('rgb' => '429E3A'),
        ),
        'font' => array(
            'bold' => true,
            'color' => array('rgb' => 'ffffff'),
        ),
        'alignment' => array(
            'indent' => 0.5,
        )
    );
    $style_row = array(
        'borders' => array(
            'bottom' => $default_border,
            'left' => $default_border,
            'top' => $default_border,
            'right' => $default_border
        ),
        'font' => array(
            'bold' => false,
            'color' => array('rgb' => '000000'),
        ),
        'alignment' => array(
            'horizontal' => PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_LEFT,
            'vertical' => PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_TOP,
        )
    );
    $style_row_gesamt = array(
        'borders' => array(
            'bottom' => $default_border,
            'left' => $default_border,
            'top' => $default_border,
            'right' => $default_border
        ),
        'fill' => array(
            'fillType' => PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
            'color' => array('rgb' => 'C3C2D9'),
        ),
        'font' => array(
            'bold' => true,
        ),
        'alignment' => array(
            'horizontal' => PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_LEFT,
            'indent' => 0.5,
        )
    );

    $style_row_durchgestr = array(
        'borders' => array(
            'bottom' => $default_border,
            'left' => $default_border,
            'top' => $default_border,
            'right' => $default_border,
        ),
        'fill' => array(
            'fillType' => PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
            'color' => array('rgb' => 'E1E0F7'),
        ),
        'font' => array(
            'bold' => true,
            'strikethrough' => true,
            'color' => array('rgb' => '888888'),
        ),
        'alignment' => array(
            'indent' => 0.5,
        )
    );
    $style_col1 = array(
        'borders' => array(
            'bottom' => $default_border,
            'left' => $default_border,
            'top' => $default_border,
            'right' => $default_border,
        ),
        'fill' => array(
            'fillType' => PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
            'color' => array('rgb' => 'E1E0F7'),
        ),
        'font' => array(
            'bold' => true,
            'color' => array('rgb' => '000000'),
        ),
        'alignment' => array(
            'horizontal' => PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_LEFT,
            'vertical' => PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_TOP,
            'indent' => 0.5,
        )
    );

    $sheet->getStyle('A1:E1')->applyFromArray($style_header_1);

    //STYLES ENDE
    // den namen vom Worksheet 1 definieren
    $xls->getActiveSheet()->setTitle("Abrechnung Uebersicht " . $monat . '_' . $jahr);

    $sheet->mergeCells('A1:E1');
    $sheet->setCellValue("A1", "Abrechnung Übersicht " . $monat . '/' . $jahr);

    $t = 2;
    $sheet->setCellValue("A$t", 'Name');
    $sheet->setCellValue("B$t", "Portionen");
    $sheet->setCellValue("C$t", "Info");
    $sheet->setCellValue("D$t", "Status");
    $sheet->setCellValue("E$t", "kitafino ID");
    $t++;

    $portionen_monat_gesamt = 0;

    foreach ($daten as $name => $data) {
        $portionen_monat_gesamt += $data['portionen'];
        $sheet->getStyle("A$t")->getAlignment()->setWrapText(true);
        $sheet->getStyle("C$t")->getAlignment()->setWrapText(true);
        $sheet->getStyle("A$t:E$t")->applyFromArray($style_row);
        $sheet->setCellValue("A$t", $name);
        $sheet->setCellValue("B$t", $data['portionen']);
        $sheet->setCellValue("C$t", $data['info']);
        $sheet->setCellValue("D$t", $data['status']);
        $sheet->setCellValue("E$t", $data['kundennr']);
        $t++;
    }
    $sheet->setCellValue("A$t", 'Gesamt');
    $sheet->setCellValue("B$t", $portionen_monat_gesamt);
    $sheet->getStyle("A$t:B$t")->applyFromArray($style_row_gesamt);

    $writer = PhpOffice\PhpSpreadsheet\IOFactory::createWriter($xls, "Xls");
    ob_end_clean();

    $heute = strftime('%d_%m_%Y', time());
    $rechner = php_uname('n');
    if ($_SESSION['is_local_server']) {
        $writer->save('export_abrechnungen/Abrg_' . $monat . '_' . $jahr . ".xls");
    } else {
        $writer->save('export_abrechnungen/Abrg_' . $monat . '_' . $jahr . ".xls");
    }

    if ($_SESSION['is_local_server']) {
        header('location:export_abrechnungen/Abrg_' . $monat . '_' . $jahr . ".xls");
    } else {
        header('location:export_abrechnungen/Abrg' . '_' . $monat . '_' . $jahr . ".xls");
    }
}

function erzeugeTagesmengenUebersichtExcel($gesamtmengen_array, $tag, $monat, $jahr) {
    /*require_once('../verwaltung_alt/PHPExcel-1.7.5/PHPExcel.php');
    require_once('../verwaltung_alt/PHPExcel-1.7.5/PHPExcel/IOFactory.php');*/

    require '../PhpSpreadsheet/PhpOffice/autoload.php';


    // neue instanz erstellen

    //$xls = new PHPExcel();
    $xls = new \PhpOffice\PhpSpreadsheet\Spreadsheet();
    // dokumenteigenschaften schreiben
    $xls->getProperties()->setCreator("S-Bar")
        ->setLastModifiedBy("S-Bar")
        ->setTitle("Tagesmengen " . strftime('%d.%.m.%y', mktime(12, 0, 0, $monat, $tag, $jahr)))
        ->setSubject("Tagesmengen " . strftime('%d.%.m.%y', mktime(12, 0, 0, $monat, $tag, $jahr)));
    // das erste worksheet anwaehlen
    $sheet = $xls->setActiveSheetIndex(0);

    //STYLES START
    $sheet->getStyle('A1')->getFont()->setBold(true);
    $sheet->getStyle('A2:D2')->getFont()->setBold(true);
    $sheet->getColumnDimension('A')->setWidth(30);
    $sheet->getColumnDimension('B')->setWidth(15);
    $sheet->getColumnDimension('C')->setWidth(15);
    $sheet->getColumnDimension('D')->setWidth(15);
    $sheet->getColumnDimension('A')->setAutoSize(false);

    $sheet->setCellValue("A1", strftime('%a %d.%m.%y', mktime(12, 0, 0, $monat, $tag, $jahr)));
    // den wert test in das Feld A1 schreiben

    $sheet->setCellValue("A2", "Speise 1", $format_bold);
    $sheet->setCellValue("B2", "Gesamt");
    $sheet->setCellValue("C2", "Gesamt 2");
    $i = 3;
    $check_speise_nr = 1;
    foreach ($gesamtmengen_array as $key_sp_nr => $mengenarray_zu_speise_nr) {
        if ($key_sp_nr > $check_speise_nr) {
            $i++;
            $sheet->setCellValue("A$i", "Speise " . $key_sp_nr, $format_bold);
            $sheet->setCellValue("B$i", "Gesamt");
            $sheet->setCellValue("C$i", "Gesamt 2");
            $sheet->getStyle("A$i:D$i")->getFont()->setBold(true);
            $i++;
            $check_speise_nr = $key_sp_nr;
        }
        foreach ($mengenarray_zu_speise_nr as $satz) {
            $sheet->setCellValue("A$i", $satz[0], $format_bold);
            $sheet->setCellValue("B$i", str_replace(".", ",", $satz[1]) . ' ' . $satz[2]);
            $menge_umg = '';
            $einheit = '';
            if ($satz[2] == 'g' || $satz[2] == 'ml') {
                $menge_umg = $satz[1] / 1000;
                switch ($satz[2]) {
                    case 'g':
                        $einheit = 'kg';
                        break;
                    case 'ml':
                        $einheit = 'L';
                        break;
                }
            }

            $menge_umg = str_replace(".", ",", $menge_umg);
            $sheet->setCellValue("C$i", $menge_umg . ' ' . $einheit);
            $i++;
        }
    }
    $heute = strftime('%d_%m_%Y', time());
    $rechner = php_uname('n');

    // $writer = PHPExcel_IOFactory::createWriter($xls, "Excel5");
    $objWriter = PhpOffice\PhpSpreadsheet\IOFactory::createWriter($xls, 'Xls');
    if ($_SESSION['is_local_server']) {
        $objWriter->save('export_tagesmengen/Mengen_' . strftime('%d_%m_%y', mktime(12, 0, 0, $monat, $tag, $jahr)) . ".xls");
    } else {
        $objWriter->save('/is/htdocs/wp1055935_1F55KTNHIC/www/sbar/verwaltung/export_tagesmengen/Mengen_' . strftime('%d_%m_%y', mktime(12, 0, 0, $monat, $tag, $jahr)) . ".xls");
    }

    if ($_SESSION['is_local_server']) {
        header('location:export_tagesmengen/Mengen_' . strftime('%d_%m_%y', mktime(12, 0, 0, $monat, $tag, $jahr)) . ".xls");
    } else {
        header('location:http://www.s-bar.net/verwaltung/export_tagesmengen/Mengen_' . strftime('%d_%m_%y', mktime(12, 0, 0, $monat, $tag, $jahr)) . ".xls");
    }
}

function erzeugeTagesaufstellungExcelEtikettendruck($tag, $monat, $jahr, $starttag, $startmonat, $startjahr, $speise_id, $bestellung, $bestellung_has_speiseVerwaltung, $speiseVerwaltung, $menge_pro_portionVerwaltung, $standardportionenVerwaltung, $portionenaenderungVerwaltung, $kunden, $indifaktorVerwaltung, $bemerkungzutagVerwaltung, $bemerkungzuspeiseVerwaltung, $speise_nr = 1, $kundeVerwaltung = NULL) {
    /* require_once('../verwaltung_alt/PHPExcel-1.7.5/PHPExcel.php');
     require_once('../verwaltung_alt/PHPExcel-1.7.5/PHPExcel/IOFactory.php');*/

    require '../PhpSpreadsheet/PhpOffice/autoload.php';
    $ts = mktime(12, 0, 0, $monat, $tag, $jahr);
    // neue instanz erstellen
    //$xls = new PHPExcel();
    $xls = new \PhpOffice\PhpSpreadsheet\Spreadsheet();
    // dokumenteigenschaften schreiben
    $xls->getProperties()->setCreator("S-Bar")
        ->setLastModifiedBy("S-Bar")
        ->setTitle("Tagesaufstellung " . strftime('%d.%.m.%y', mktime(12, 0, 0, $monat, $tag, $jahr)))
        ->setSubject("Tagesaufstellung " . strftime('%d.%m.%y', mktime(12, 0, 0, $monat, $tag, $jahr)));
    // das erste worksheet anwaehlen
    $sheet = $xls->setActiveSheetIndex(0);

    //STYLES START
    //$sheet->getStyle('A1')->getFont()->setBold(true);
    // $sheet->getStyle('A2:D2')->getFont()->setBold(true);
    $sheet->getColumnDimension('A')->setWidth(30);
    $sheet->getColumnDimension('B')->setWidth(15);
    $sheet->getColumnDimension('C')->setWidth(15);
    $sheet->getColumnDimension('D')->setWidth(15);
    $sheet->getColumnDimension('F')->setWidth(15);
    $sheet->getColumnDimension('A')->setAutoSize(false);

    $default_border = array(
    );
    $style_header_1 = array(
    );
    $style_header = array(
    );
    $style_row = array(
    );
    $style_row_2 = array(
    );
    $style_row_durchgestr = array(
    );
    $style_row_durchgestr_2 = array(
    );
    $style_row_gesamt = array(
    );
    $style_col1 = array(
    );
    $style_col1_2 = array(
    );
    //$sheet->getStyle('A3:E3')->applyFromArray( $style_header_1 );
    //$sheet->getStyle('A4:E4')->applyFromArray( $style_header );
    //STYLES ENDE

    $x = $i - 1;
    // den namen vom Worksheet 1 definieren
    $xls->getActiveSheet()->setTitle("Tagesaufstellung " . strftime('%d.%m.%y', mktime(12, 0, 0, $monat, $tag, $jahr)));
    $i = 1;
    $bestellung_has_speise_array = $bestellung_has_speiseVerwaltung->findeAnhandVonBestellungId($bestellung->getId());

    $gesamtmenge_tag_speise = 0;
    $gesamtmenge_tag_portionen = 0;
    $c = $i - 2;
    $d = $i - 1;
    //$sheet->getStyle("A$d:E$d")->getFont()->setSize(15);
    //$sheet->getStyle("A$c:E$c")->getFont()->setSize(15);



    $speise = $speiseVerwaltung->findeAnhandVonId($speise_id);
    //$sheet->setCellValue("A$c",$speise->getBezeichnung());
    // den wert test in das Feld A1 schreiben
    // $sheet->setCellValue("E$d","Menge/P Standard");
    //$sheet->setCellValue("F$d","Faktor");
    $last_tour = new Kunde();
    foreach ($kunden as $kunde) {

        if ($speise_nr == 2 && $kunde->getAnzahlSpeisen() == 1 && !$kunde->getStaedtischerKunde()) {
            continue;
        }
        if ($speise_nr <= 2 && ($kunde->getBioKunde() || $kunde->getStaedtischerKunde())) {
            continue;
        }
        if ($speise_nr > 2 && (!$kunde->getBioKunde() && !$kunde->getStaedtischerKunde())) {
            continue;
        }

        /*
          if ($i == 35 || $i == 70 || $i == 105 || $i == 140 || $i == 175 || $i == 210) {
          $sheet->setCellValue("A$i",$speise->getBezeichnung());
          $sheet->getStyle("A$i:E$i")->applyFromArray( $style_header_1 );
          $i++;
          } */
        if ($i % 2) {
            $sheet->getStyle("A$i")->applyFromArray($style_col1);
            $sheet->getStyle("B$i")->applyFromArray($style_row);
            $sheet->getStyle("C$i")->applyFromArray($style_row);
            $sheet->getStyle("D$i")->applyFromArray($style_row);
            $sheet->getStyle("E$i")->applyFromArray($style_row);
            $sheet->getStyle("F$i")->applyFromArray($style_row);
        } else {
            $sheet->getStyle("A$i")->applyFromArray($style_col1_2);
            $sheet->getStyle("B$i")->applyFromArray($style_row_2);
            $sheet->getStyle("C$i")->applyFromArray($style_row_2);
            $sheet->getStyle("D$i")->applyFromArray($style_row_2);
            $sheet->getStyle("E$i")->applyFromArray($style_row_2);
            $sheet->getStyle("F$i")->applyFromArray($style_row_2);
        }

        /// $sheet->getStyle("A$i:E$i")->getFont()->setSize(15);
        //  $sheet->getStyle("C$i")->getFont()->setSize(8);
        //  $sheet->getStyle("B$i")->getFont()->setSize(8);
        //  $sheet->getStyle("E$i")->getFont()->setSize(12);
        // $sheet->getStyle("E$i")->applyFromArray( $style_row );
        //$sheet->getStyle("F$i")->applyFromArray( $style_row );
        $speise_id = $speise->getId();
        $indi_faktor = $indifaktorVerwaltung->findeAnhandVonSpeiseIdUndKundeId($speise_id, $kunde->getId());
        $faktor = $indi_faktor->getFaktor();
        $einrichtungskategorie_id = $kunde->getEinrichtungskategorieId();
        $menge_pro_portion = $menge_pro_portionVerwaltung->findeAnhandVonSpeiseIdUndEinrichtungskategorieId($speise_id, $einrichtungskategorie_id);

        $standardportionen = $standardportionenVerwaltung->findeAnhandVonKundenIdUndSpeiseNr($kunde->getId(), $speise_nr);
        $portionenaenderung = $portionenaenderungVerwaltung->findeAnhandVonKundenIdUndWochenstartdatum($kunde->getId(), $starttag, $startmonat, $startjahr, $speise_nr);
        if ($portionenaenderung->getId()) {
            $portionen_mo = $portionenaenderung->getPortionenMo();
            $portionen_di = $portionenaenderung->getPortionenDi();
            $portionen_mi = $portionenaenderung->getPortionenMi();
            $portionen_do = $portionenaenderung->getPortionenDo();
            $portionen_fr = $portionenaenderung->getPortionenFr();
            $aenderung = true;
        } else {
            $portionen_mo = $standardportionen->getPortionenMo();
            $portionen_di = $standardportionen->getPortionenDi();
            $portionen_mi = $standardportionen->getPortionenMi();
            $portionen_do = $standardportionen->getPortionenDo();
            $portionen_fr = $standardportionen->getPortionenFr();
            $aenderung = false;
        }



        $wochentag_string = strftime('%a', mktime(12, 0, 0, $monat, $tag, $jahr));
        switch ($wochentag_string) {
            case 'Mo':
                $portionen = $portionen_mo;
                if ($portionen_mo != $standardportionen->getPortionenMo()) {
                    //$portionen .= ' (' . $standardportionen->getPortionenMo().')';
                }
                break;
            case 'Di':
                $portionen = $portionen_di;
                if ($portionen_di != $standardportionen->getPortionenDi()) {
                    //$portionen .= ' (' . $standardportionen->getPortionenDi().')';
                }
                break;
            case 'Mi':
                $portionen = $portionen_mi;
                if ($portionen_mi != $standardportionen->getPortionenMi()) {
                    //$portionen .= ' (' . $standardportionen->getPortionenMi().')';
                }
                break;
            case 'Do':
                $portionen = $portionen_do;
                if ($portionen_do != $standardportionen->getPortionenDo()) {
                    //$portionen .= ' (' . $standardportionen->getPortionenDo().')';
                }
                break;
            case 'Fr':
                $portionen = $portionen_fr;
                if ($portionen_fr != $standardportionen->getPortionenFr()) {
                    //$portionen .= ' (' . $standardportionen->getPortionenFr().')';
                }
                break;
        }

        $gesamtmenge_tag_portionen += $portionen;
        $gesamtmenge_kunde = $portionen * ($menge_pro_portion->getMenge() * $faktor);
        $einheit = $menge_pro_portion->getEinheit();
        if ($portionen == 0) {
            if ($i % 2) {
                $sheet->getStyle("A$i")->applyFromArray($style_row_durchgestr);
                $sheet->getStyle("B$i")->applyFromArray($style_row_durchgestr);
                $sheet->getStyle("C$i")->applyFromArray($style_row_durchgestr);
                $sheet->getStyle("D$i")->applyFromArray($style_row_durchgestr);
                $sheet->getStyle("E$i")->applyFromArray($style_row_durchgestr);
            } else {
                $sheet->getStyle("A$i")->applyFromArray($style_row_durchgestr_2);
                $sheet->getStyle("B$i")->applyFromArray($style_row_durchgestr_2);
                $sheet->getStyle("C$i")->applyFromArray($style_row_durchgestr_2);
                $sheet->getStyle("D$i")->applyFromArray($style_row_durchgestr_2);
                $sheet->getStyle("E$i")->applyFromArray($style_row_durchgestr_2);
            }
        }

        ///$sheet->setCellValue("A$i", "" . $kunde->getName() .' '.$portionen. ' test '. $i );
        if ($kunde->getEinrichtungskategorieId() == 5) {
            // $i--;
            // $i++;
            /* $sheet->setCellValue("A$i",$kunde->getName());
              $sheet->getStyle("A$i:D$i")->getFill()->applyFromArray(
              array(
              'type'       => PHPExcel_Style_Fill::FILL_SOLID,
              'startcolor' => array('rgb' => 'cccccc'),
              'endcolor' => array('rgb' => 'cccccc')
              )
              ); */
        } else {


            if ($portionen > 0) {
                $tour_zu_kunde = $kundeVerwaltung->findeTourZuKundenReihenfolge($kunde->getLieferreihenfolge());
                $tour_zu_kunde = $tour_zu_kunde[0];
                if ($tour_zu_kunde == NULL) {
                    $tour_zu_kunde = new Kunde();
                }
                if ($speise_nr > 1 && $last_tour->getId() != $tour_zu_kunde->getId()) {


                    /*   $sheet->setCellValue("A$i", "" . $tour_zu_kunde->getName() . ' Sammel 1 ' . '[Sp ' . $speise_nr . ']');
                      $sheet->setCellValue("E$i", strftime('%a %d.%m.%y', mktime(12, 0, 0, $monat, $tag, $jahr)));
                      $sheet->setCellValue("D$i", $speise->getBezeichnung());
                      $code = $kunde->getTourId() . '-' . $tag . $monat . $jahr . '-' . $speise_nr . '-' . 'S-' . $speise->getId() . '-1';
                      $sheet->setCellValue("F$i", $code);
                      $sheet->setCellValue("G$i", $tour_zu_kunde->getName());
                      $i++; */

                    for ($r = 3; $r >= 1; $r--) {
                        $sheet->setCellValue("A$i", "" . $tour_zu_kunde->getName() . ' Sammel ' . $r . ' [Sp ' . $speise_nr . ']');
                        $sheet->setCellValue("B$i", $datum_str);
                        $sheet->setCellValue("D$i", $speise->getBezeichnung());
                        $sheet->setCellValue("E$i", strftime('%a %d.%m.%y', mktime(12, 0, 0, $monat, $tag, $jahr)));
                        $sheet->setCellValue("F$i", $tour_zu_kunde->getId() . '-' . sprintf('%02d', $tag) . sprintf('%02d', $monat) . sprintf('%02d', $jahr) . '-' . $speise_nr . '-' . 'S' . '-' . $speise->getId() . '-' . $r);
                        $sheet->setCellValue("G$i", $tour_zu_kunde->getName());
                        $i++;
                    }
                    /*   for ($r = 2; $r <= 3; $r++) {
                      $sheet->setCellValue("A$i", "" . $tour_zu_kunde->getName() . ' Sammel ' . $r . ' [Sp ' . $speise_nr . ']');
                      $sheet->setCellValue("B$i", $datum_str);
                      $sheet->setCellValue("D$i", $speise->getBezeichnung());
                      $sheet->setCellValue("E$i", strftime('%a %d.%m.%y', mktime(12, 0, 0, $monat, $tag, $jahr)));
                      $sheet->setCellValue("F$i", $tour_zu_kunde->getId() . '-' . sprintf('%02d', $tag) . sprintf('%02d', $monat) . sprintf('%02d', $jahr) . '-' . $speise_nr . '-' . 'S' . '-' . $speise->getId() . '-' . $r);
                      $sheet->setCellValue("G$i", $tour_zu_kunde->getName());
                      $i++;
                      } */
                }
                if ($tour_zu_kunde == NULL) {
                    $tour_zu_kunde = new Kunde();
                }
                $last_tour = $tour_zu_kunde;

                $sheet->setCellValue("A$i", $kunde->getName());
                // $sheet->setCellValue("B$i",$portionen);
                $bemerkungen_str = '';
                //$sheet->setCellValue("C$i",$menge_pro_portion->getMenge()*$faktor. ' '. $menge_pro_portion->getEinheit());
                $sheet->setCellValue("B$i", $gesamtmenge_kunde /* .' '.$menge_pro_portion->getEinheit() */);
                $bemerkung_zu_tag = $bemerkungzutagVerwaltung->findeAnhandVonKundeIdUndDatumUndSpeiseId($kunde->getId(), $tag, $monat, $jahr, $speise->getId());
                $bemerkung_zu_speise = $bemerkungzuspeiseVerwaltung->findeAnhandVonKundeIdUndSpeiseId($kunde->getId(), $speise->getId());

                if ($bemerkung_zu_speise->getBemerkung() != '') {
                    $bemerkungen_str .= $bemerkung_zu_speise->getBemerkung() . '; ';
                }
                if ($bemerkung_zu_tag->getBemerkung() != '') {
                    $bemerkungen_str .= $bemerkung_zu_tag->getBemerkung() . '; ';
                }
                if ($kunde->getBemerkung() != '') {
                    // $bemerkungen_str .= $kunde->getBemerkung().'; ';
                }
                $code = '';
                if ($speise->getKaltVerpackt()) {
                    $code = $kunde->getId() . '-' . $tag . $monat . $jahr . '-' . $speise_nr . '-' . '0' . '-' . $speise->getId();
                }
                if ($speise->getCooled()) {
                    //$code = $kunde->getId() . '-' . $tag . $monat . $jahr . '-' . $speise_nr . '-' . 'K' . '-' . $speise->getId();
                    $code = '';
                }

                $sheet->setCellValue("C$i", $bemerkungen_str);
                $sheet->setCellValue("D$i", $speise->getBezeichnung());
                $sheet->setCellValue("E$i", strftime('%a %d.%m.%y', mktime(12, 0, 0, $monat, $tag, $jahr)));
                $sheet->setCellValue("F$i", $code);
                $sheet->setCellValue("G$i", $tour_zu_kunde->getName());
                if ($kunde->getEinrichtungskategorieId() == 6) {
                    /*   $sheet->setCellValue("A$i", $kunde->getName() . ' Sammel 1 [Sp ' . $speise_nr . ']');
                      $sheet->setCellValue("B$i", '');
                      $sheet->setCellValue("C$i", '');
                      $sheet->setCellValue("D$i", $speise->getBezeichnung());
                      $code = $kunde->getTourId() . '-' . $tag . $monat . $jahr . '-' . $speise_nr . '-' . 'S-' . $speise->getId() . '-1';
                      $sheet->setCellValue("F$i", $code);
                      $sheet->setCellValue("G$i", $tour_zu_kunde->getName()); */
                    $i--;
                    for ($r = 3; $r >= 1; $r--) {
                        $i++;
                        $sheet->setCellValue("A$i", "" . $tour_zu_kunde->getName() . ' Sammel ' . $r . ' [Sp ' . $speise_nr . ']');
                        $sheet->setCellValue("B$i", $datum_str);
                        $sheet->setCellValue("D$i", $speise->getBezeichnung());
                        $sheet->setCellValue("E$i", strftime('%a %d.%m.%y', mktime(12, 0, 0, $monat, $tag, $jahr)));
                        $sheet->setCellValue("F$i", $tour_zu_kunde->getId() . '-' . sprintf('%02d', $tag) . sprintf('%02d', $monat) . sprintf('%02d', $jahr) . '-' . $speise_nr . '-' . 'S' . '-' . $speise->getId() . '-' . $r);

                        $sheet->setCellValue("G$i", $tour_zu_kunde->getName());
                    }
                }
                $i++;
            }
        }


        $sheet->getStyle("E$i")->getAlignment()->setWrapText(true);
        //  $sheet->setCellValue("E$i",$menge_pro_portion->getMenge() . ' ' . $menge_pro_portion->getEinheit() . ' /P');
        //  $sheet->setCellValue("F$i",$faktor);
        /*
          var_dump($kunde->getEinrichtungskategorieId()); */
//var_dump($portionen*1, $kunde->getName());
        if ($portionen * 1 > 0) {
            // $i++;
        }
        $gesamtmenge_kunde = $portionen * ($menge_pro_portion->getMenge() * $faktor);
        $gesamtmenge_tag_speise += $gesamtmenge_kunde;
        //$i++;
    }

    $sheet->setCellValue("A$i", 'Gesamt');
    if ($einheit == 'g') {
        $gesamtmenge_tag_speise_kg = $gesamtmenge_tag_speise / 1000;
        $sheet->setCellValue("B$i", $gesamtmenge_tag_speise_kg . ' kg');
    } else {
        $sheet->setCellValue("B$i", $gesamtmenge_tag_speise);
    }

    $sheet->setCellValue("C$i", '');
    $sheet->setCellValue("D$i", $speise->getBezeichnung() . ' ');
    $sheet->setCellValue("E$i", strftime('%a %d.%m.%y', mktime(12, 0, 0, $monat, $tag, $jahr)));

    $sheet->getStyle("C4")->getFont()->setSize(12);
    $sheet->getStyle("B4")->getFont()->setSize(12);

    //$sheet->setCellValue("A$i",$speise->getBezeichnung());
    $sheet->getStyle("A$i:E$i")->applyFromArray($style_header_1);
    $i++;
    $sheet->getStyle("A$i:E$i")->getFont()->setSize(15);

    //$sheet->setCellValue("A$i","Insgesamt", $format_bold);
    //$sheet->setCellValue("B$i",$gesamtmenge_tag_portionen);
    //$sheet->setCellValue("C$i","");
    //$sheet->setCellValue("D$i",$gesamtmenge_tag_speise .' '.$menge_pro_portion->getEinheit());

    $ttt = $i + 1;
    if ($menge_pro_portion->getEinheit() == 'g' || $menge_pro_portion->getEinheit() == 'ml') {
        $gesamtmenge_tag_speise_umger = $gesamtmenge_tag_speise / 1000;
        switch ($menge_pro_portion->getEinheit()) {
            case 'g':
                $einheit = 'kg';
                break;
            case 'ml':
                $einheit = 'L';
                break;
        }
    }

    $writer = PhpOffice\PhpSpreadsheet\IOFactory::createWriter($xls, "Xls");
    $sheet->getColumnDimension('A')->setWidth(37);
    $sheet->getColumnDimension('B')->setWidth(7);
    $sheet->getColumnDimension('C')->setWidth(8);
    $sheet->getColumnDimension('D')->setWidth(12);
    $sheet->getColumnDimension('E')->setWidth(27);

    $heute = strftime('%d_%m_%Y', time());
    $rechner = php_uname('n');
    $speise_bezeichnung = $speise->getBezeichnung();
    $speise_bezeichnung = umlautepas($speise_bezeichnung);
    //var_dump($speise_bezeichnung);

    $speise_bezeichnung = entferneSonderzeichen($speise_bezeichnung);

    if ($_SESSION['is_local_server']) {
        $writer->save('export_etiketten/Eti_' . $speise_bezeichnung . '_Sp' . $speise_nr . '_' . strftime('%d_%m_%y', mktime(12, 0, 0, $monat, $tag, $jahr)) . ".xls");
    } else {
        $writer->save('/is/htdocs/wp1055935_1F55KTNHIC/www/sbar/verwaltung/export_etiketten/Eti_' . $speise_bezeichnung . '_Sp' . $speise_nr . '_' . strftime('%d_%m_%y', mktime(12, 0, 0, $monat, $tag, $jahr)) . ".xls");
    }

    if ($_SESSION['is_local_server']) {
        header('location:export_etiketten/Eti_' . $speise_bezeichnung . '_Sp' . $speise_nr . '_' . strftime('%d_%m_%y', mktime(12, 0, 0, $monat, $tag, $jahr)) . ".xls");
    } else {
        header('location:http://www.s-bar.net/verwaltung/export_etiketten/Eti_' . $speise_bezeichnung . '_Sp' . $speise_nr . '_' . strftime('%d_%m_%y', mktime(12, 0, 0, $monat, $tag, $jahr)) . ".xls");
    }
}

function erzeugeKundenArrayNachLieferreihenfolgeUndTourenNachSpeiseNr($kundeVerwaltung, $speise_nr, $standardportionenVerwaltung, $portionenaenderungVerwaltung, $ts, $starttag, $startmonat, $startjahr) {
    $return = array();
    $kunden = $kundeVerwaltung->findeAlleAktivenZuSpeiseNr($speise_nr);
    if ($speise_nr == 3 || $speise_nr == 4) {
        //kunden mit produktionsreihenfolge erstellen
        $kunden_touren = $kundeVerwaltung->findeAlleTouren('produktionsreihenfolge');
        $kunden = array();
        foreach ($kunden_touren as $k_tour) {
            $kunden_zu_tour = array();
            $kunden_zu_tour = $kundeVerwaltung->findeAlleAktivenZuSpeiseNrUndTourIdProduktion($speise_nr, $k_tour->getId());
            if (count($kunden_zu_tour)) {
                $kunden[] = $k_tour;
                $kunden = array_merge($kunden, $kunden_zu_tour);
            }
        }
    }


    $tour_id = 0;
    $return_temp = array();
    $touren_array = array();
    $portionen_array = array();
    foreach ($kunden as $kunde) {
        $portionen = 0;
        if ($kunde->getEinrichtungskategorieId() != 5) {
            $standardportionen = $standardportionenVerwaltung->findeAnhandVonKundenIdUndSpeiseNr($kunde->getId(), $speise_nr);
            $portionenaenderung = $portionenaenderungVerwaltung->findeAnhandVonKundenIdUndWochenstartdatum($kunde->getId(), $starttag, $startmonat, $startjahr, $speise_nr);

            if ($portionenaenderung->getId()) {
                $portionen_mo = $portionenaenderung->getPortionenMo();
                $portionen_di = $portionenaenderung->getPortionenDi();
                $portionen_mi = $portionenaenderung->getPortionenMi();
                $portionen_do = $portionenaenderung->getPortionenDo();
                $portionen_fr = $portionenaenderung->getPortionenFr();
                $aenderung = true;
            } else {
                $portionen_mo = $standardportionen->getPortionenMo();
                $portionen_di = $standardportionen->getPortionenDi();
                $portionen_mi = $standardportionen->getPortionenMi();
                $portionen_do = $standardportionen->getPortionenDo();
                $portionen_fr = $standardportionen->getPortionenFr();
                $aenderung = false;
            }

            $wochentag_string = strftime('%a', $ts);
            switch ($wochentag_string) {
                case 'Mo':
                    $portionen = $portionen_mo;
                    break;
                case 'Di':
                    $portionen = $portionen_di;
                    break;
                case 'Mi':
                    $portionen = $portionen_mi;
                    break;
                case 'Do':
                    $portionen = $portionen_do;
                    break;
                case 'Fr':
                    $portionen = $portionen_fr;
                    break;
            }

            $portionen_array[$kunde->getId()][$speise_nr] = $portionen;
            $portionen_array['tour_' . $kunde->getTourId()][$speise_nr] += $portionen;
        }



        if ($kunde->getEinrichtungskategorieId() == 6) {
            $tour_id = $kunde->getId();
            $return_temp[$kunde->getId()] = array();
            $touren_array[$kunde->getId()] = $kunde;
        } else {
            $return_temp[$tour_id][] = $kunde;
        }
    }

    $return_temp = array_filter($return_temp);

    foreach ($return_temp as $tour_id => $kunden_temp) {
        //$return[] = $kd_temp;
        if ($tour_id) {
            $tour = $touren_array[$tour_id];
            $return[] = $tour;
        }
        foreach ($kunden_temp as $kd_temp) {
            $return[] = $kd_temp;
        }
    }
    $return_all = array();
    $return_all['kunden'] = $return;
    $return_all['portionen'] = $portionen_array;
    return $return_all;
}

function erzeugeEinrichtungslisteZuSpeiseNr($kundeVerwaltung, $tag, $monat, $jahr, $starttag, $startmonat, $startjahr, $speise_id, $bestellung, $bestellung_has_speiseVerwaltung, $speiseVerwaltung, $menge_pro_portionVerwaltung, $standardportionenVerwaltung, $portionenaenderungVerwaltung, $kunden, $indifaktorVerwaltung, $bemerkungzutagVerwaltung, $bemerkungzuspeiseVerwaltung, $speise_nr) {
    /*require_once('../verwaltung_alt/PHPExcel-1.7.5/PHPExcel.php');
    require_once('../verwaltung_alt/PHPExcel-1.7.5/PHPExcel/IOFactory.php');*/

    require '../PhpSpreadsheet/PhpOffice/autoload.php';

    $ts = mktime(12, 0, 0, $monat, $tag, $jahr);
    $kunden_arr = erzeugeKundenArrayNachLieferreihenfolgeUndTourenNachSpeiseNr($kundeVerwaltung, $speise_nr, $standardportionenVerwaltung, $portionenaenderungVerwaltung, $ts, $starttag, $startmonat, $startjahr);

    $kunden = array_reverse($kunden_arr['kunden']);

// neue instanz erstellen

    //$xls = new PHPExcel();
    $xls = new \PhpOffice\PhpSpreadsheet\Spreadsheet();
    // dokumenteigenschaften schreiben
    $xls->getProperties()->setCreator("S-Bar")
        ->setLastModifiedBy("S-Bar")
        ->setTitle("Tagesaufstellung " . strftime('%d.%m.%y', $ts))
        ->setSubject("Tagesaufstellung " . strftime('%d.%m.%y', $ts));
    // das erste worksheet anwaehlen
    $sheet = $xls->setActiveSheetIndex(0);

    //STYLES START
    //$sheet->getStyle('A1')->getFont()->setBold(true);
    // $sheet->getStyle('A2:D2')->getFont()->setBold(true);
    $sheet->getColumnDimension('A')->setWidth(50);
    $sheet->getColumnDimension('B')->setWidth(20);
    $sheet->getColumnDimension('C')->setWidth(25);
    $sheet->getColumnDimension('D')->setWidth(15);
    $sheet->getColumnDimension('A')->setAutoSize(true);

    $default_border = array(
    );
    $style_header_1 = array(
    );
    $style_header = array(
    );
    $style_row = array(
    );
    $style_row_2 = array(
    );
    $style_row_durchgestr = array(
    );
    $style_row_durchgestr_2 = array(
    );
    $style_row_gesamt = array(
    );
    $style_col1 = array(
    );
    $style_col1_2 = array(
    );

    $i = 1;
    $x = $i - 1;

    $xls->getActiveSheet()->setTitle("Einrichtungsliste Sp " . $speise_nr . ' ' . strftime('%d.%m.%y', $ts));
    $i = 1;
    $bestellung_has_speise_array = $bestellung_has_speiseVerwaltung->findeAnhandVonBestellungId($bestellung->getId());

    $speisen_cooled_ids = array();
    foreach ($bestellung_has_speise_array as $best_has_speise) {
        if ($best_has_speise->getSpeiseNr() !== $speise_nr) {
            continue;
        }

        $speise_id_check_cooled = $best_has_speise->getSpeiseId();
        $speise_check = $speiseVerwaltung->findeAnhandVonId($speise_id_check_cooled);
        if ($speise_check->getCooled()) {
            $speisen_cooled_ids[$speise_id_check_cooled] = $speise_check->getBezeichnung();
        }
    }

    $gesamtmenge_tag_speise = 0;
    $gesamtmenge_tag_portionen = 0;
    $c = $i - 2;
    $d = $i - 1;
    //$sheet->getStyle("A$d:E$d")->getFont()->setSize(15);
    //$sheet->getStyle("A$c:E$c")->getFont()->setSize(15);



    $speise = $speiseVerwaltung->findeAnhandVonId($speise_id);
    //$sheet->setCellValue("A$c",$speise->getBezeichnung());
    // den wert test in das Feld A1 schreiben
    // $sheet->setCellValue("E$d","Menge/P Standard");
    //$sheet->setCellValue("F$d","Faktor");

    $last_tour = new Kunde();
    $tour_zu_kunde = new Kunde();

    foreach ($kunden as $kunde) {

        $speise_id = $speise->getId();
        $indi_faktor = $indifaktorVerwaltung->findeAnhandVonSpeiseIdUndKundeId($speise_id, $kunde->getId());
        $faktor = $indi_faktor->getFaktor();
        $einrichtungskategorie_id = $kunde->getEinrichtungskategorieId();
        $menge_pro_portion = $menge_pro_portionVerwaltung->findeAnhandVonSpeiseIdUndEinrichtungskategorieId($speise_id, $einrichtungskategorie_id);

        $bestelltag_ts_check = mktime(12, 0, 0, $monat, $tag, $jahr);
        if ($kunde->getStartdatum() && $bestelltag_ts_check < $kunde->getStartdatum()) {
            continue;
        }
        //keine Biotouren in Speise 1 und 2
        if (($speise_nr <= 2 && $einrichtungskategorie_id == 5 && ($kunde->getBioKunde() || $kunde->getStaedtischerKunde())) || $kunde->getAktiv() == 0) {
            continue;
        }


        $portionen = $kunden_arr['portionen'][$kunde->getId()][$speise_nr] * 1;

        $gesamtmenge_tag_portionen += $portionen;
        $gesamtmenge_kunde = $portionen * ($menge_pro_portion->getMenge() * $faktor);

        if ($portionen == 0) {
            if ($i % 2) {
                $sheet->getStyle("A$i")->applyFromArray($style_row_durchgestr);
                $sheet->getStyle("B$i")->applyFromArray($style_row_durchgestr);
                $sheet->getStyle("C$i")->applyFromArray($style_row_durchgestr);
                $sheet->getStyle("D$i")->applyFromArray($style_row_durchgestr);
                $sheet->getStyle("E$i")->applyFromArray($style_row_durchgestr);
            } else {
                $sheet->getStyle("A$i")->applyFromArray($style_row_durchgestr_2);
                $sheet->getStyle("B$i")->applyFromArray($style_row_durchgestr_2);
                $sheet->getStyle("C$i")->applyFromArray($style_row_durchgestr_2);
                $sheet->getStyle("D$i")->applyFromArray($style_row_durchgestr_2);
                $sheet->getStyle("E$i")->applyFromArray($style_row_durchgestr_2);
            }
        }

        if (!$last_kunde) {
            $last_kunde = new Kunde();
        }
        if ($last_kunde->getEinrichtungskategorieId() == 5 && $kunde->getEinrichtungskategorieId() == 5) {
            $i . - 1;
        }

        /* $tour_zu_kunde = $kundeVerwaltung->findeTourZuKundenReihenfolge($kunde->getLieferreihenfolge());
          $tour_zu_kunde = $tour_zu_kunde[0];
          if (!$tour_zu_kunde) {
          $tour_zu_kunde = new Kunde();
          } */

        $tour_id = $kunde->getTourId();
        if ($kunde->getEinrichtungskategorieId() == 5) {
            $tour_id = $kunde->getId();
        }
        $tour_zu_kunde = $kundeVerwaltung->findeAnhandVonId($tour_id);

        $portionen_zu_tour = $kunden_arr['portionen']['tour_' . $tour_zu_kunde->getId()][$speise_nr];
        if (/* $kunde->getEinrichtungskategorieId() == 5 || */ ($portionen == 0 && $kunde->getEinrichtungskategorieId() != 5) || ($portionen_zu_tour == 0 && $kunde->getEinrichtungskategorieId() == 5)) {

        } else {
            $code = $kunde->getId() . '-' . sprintf('%02d', $tag) . sprintf('%02d', $monat) . sprintf('%02d', $jahr);
            $datum_str = $wochentag_string . ' ' . $tag . '.' . $monat . '.' . $jahr;

            if ($speise_nr == 2 && $kunde->getAnzahlSpeisen() == 1 && $kunde->getEinrichtungskategorieId() != 5) {
                continue;
            }
            if ($speise_nr <= 2 && ($kunde->getBioKunde() || $kunde->getStaedtischerKunde()) && $kunde->getEinrichtungskategorieId() != 5) {
                continue;
            }
            if ($speise_nr > 2 && !$kunde->getBioKunde() && !$kunde->getStaedtischerKunde() && $kunde->getEinrichtungskategorieId() != 5) {
                continue;
            }


            /*
              if ($speise_nr > 1 && $kunde->getAnzahlSpeisen() == 1 && !$kunde->getStaedtischerKunde() && !$kunde->getBioKunde()) {
              continue;
              }
              if ($speise_nr <= 2 && ($kunde->getBioKunde() || $kunde->getStaedtischerKunde())) {
              continue;
              }
              if ($speise_nr > 2 && ($kunde->getBioKunde() || $kunde->getStaedtischerKunde())) {
              continue;
              } */



            //  $kunden_zu_tour_ende = $kundeVerwaltung->findeTourEndeZuKundenReihenfolge($kunde->getLieferreihenfolge());
            //  $kunden_zu_tour = $kundeVerwaltung->findeTourEndeZuKundenReihenfolge($kunde->getLieferreihenfolge(), $kunden_zu_tour_ende[0]->getLieferreihenfolge());
            /*  echo '<pre>';
              var_dump($tour_zu_kunde->getName(),$kunden_zu_tour);
              echo '</pre>'; */
            /* if ($last_tour->getId() && $last_tour->getId() != $tour_zu_kunde->getId() ) {

              $sheet->setCellValue("A$i", "" . $last_tour->getName() . ' [Sp ' . $speise_nr . '] ' . 'ENDE');
              $sheet->setCellValue("B$i", $datum_str);
              $sheet->setCellValue("C$i", 'T' . str_pad($last_tour->getId(), 4, '0', STR_PAD_LEFT) . '-' . sprintf('%02d', $tag) . sprintf('%02d', $monat) . sprintf('%02d', $jahr). '-' . $speise_nr);
              $i++;
              } */

            if (/* $speise_nr * 1 > 1 && */ $last_tour->getId() != $tour_zu_kunde->getId() && $kunde->getEinrichtungskategorieId() == 5) {


                $sheet->setCellValue("A$i", $tour_zu_kunde->getName() . ' Sammel 1' . ' [Sp ' . $speise_nr . ']');
                $sheet->setCellValue("B$i", $datum_str);
                $sheet->setCellValue("C$i", $tour_zu_kunde->getId() . '-' . sprintf('%02d', $tag) . sprintf('%02d', $monat) . sprintf('%02d', $jahr) . '-' . $speise_nr . '-' . 'S' . '-1');
                $i++;
            }
            $last_tour = $tour_zu_kunde;

            /*
              $bestellung_has_speise_array = $bestellung_has_speiseVerwaltung->findeAnhandVonBestellungId($bestellung->getId());
              foreach ($bestellung_has_speise_array as $bestellung_has_speise) {

              $speise = $speiseVerwaltung->findeAnhandVonId($bestellung_has_speise->getSpeiseId());
              $speise_id = $speise->getId();
              if ($speise_id) {
              $code .= '-' . $speise_id;
              }
              } */

            $add = '';

            for ($r = $speise_nr; $r <= $speise_nr; $r++) {
                $add1 = '';
                if ($kunde->getAnzahlSpeisen() > 1 || $kunde->getStaedtischerKunde()) {
                    $add1 .= ' [Sp ' . $r . ']';
                }
                for ($c = $kunde->getAnzahlBoxen(); $c >= 1; $c--) {
                    $add2 = '';
                    if ($kunde->getAnzahlBoxen() > 1) {
                        $add2 .= ' [Box ' . $c . ']';
                    }

                    if ($kunde->getEinrichtungskategorieId() != 5) {
                        $sheet->setCellValue("A$i", $kunde->getName() . $add1 . $add2);

                        $sheet->setCellValue("B$i", $datum_str);

                        if ($kunde->getEinrichtungskategorieId() != 5) {
                            $sheet->setCellValue("C$i", $code . '-' . $r . '-' . $c);
                        }
                        /* if ($kunde->getEinrichtungskategorieId() == 6) {
                          $sheet->setCellValue("A$i", $kunde->getName() . 'XXX Sammel 1');
                          $sheet->setCellValue("C$i", $tour_zu_kunde->getId() . '-' . sprintf('%02d', $tag) . sprintf('%02d', $monat) . sprintf('%02d', $jahr) . '-' . $speise_nr . '-S-1');
                          } */

                        if ($kunde->getEinrichtungskategorieId() != 5 && $tour_zu_kunde->getId()) {
                            $sheet->setCellValue("D$i", $tour_zu_kunde->getName());
                        }

                        /* echo '<pre>';
                          var_dump(preg_match('#vegi#i', $kunde->getBemerkung()),$kunde->getBemerkung(), $kunde->getBemerkungKunde());
                          echo '</pre>'; */

                        $info_string = '';
                        if (preg_match('#vegi#i', $kunde->getBemerkung())) {
                            $info_string .= 'V ';
                        }
                        if (preg_match('#diät#i', $kunde->getBemerkung())) {
                            $info_string .= 'D ';
                        }
                        if (preg_match('#wärme#i', $kunde->getBemerkungKunde())) {
                            $info_string .= 'W ';
                        }
                        if (preg_match('#sauber#i', $kunde->getBemerkungKunde())) {
                            $info_string .= 'S ';
                        }
                        if ($info_string) {

                            $sheet->setCellValue("E$i", $info_string);
                        }
                        $i++;
                    }

                    /* echo '<pre>';
                      var_dump($info_string);
                      echo '</pre>'; */
                }
            }

            /*  if ($kunde->getEinrichtungskategorieId() != 6 && $kunde->getEinrichtungskategorieId() != 5) {
              foreach ($speisen_cooled_ids as $cooled_id => $speise_string) {
              $sheet->setCellValue("A$i", "" . $kunde->getName() . ' Kühlbox ' . $speise_string . ' [Sp ' . $speise_nr . ']');
              $sheet->setCellValue("B$i", $datum_str);
              $sheet->setCellValue("C$i", $kunde->getId() . '-' . sprintf('%02d', $tag) . sprintf('%02d', $monat) . sprintf('%02d', $jahr) . '-' . $speise_nr . '-' . 'K' . '-1' . '-' . $cooled_id);
              $sheet->setCellValue("D$i", $tour_zu_kunde->getName());
              $sheet->setCellValue("E$i", $info_string);
              $i++;
              }
              } */
        }

        $last_kunde = $kunde;

        $sheet->getStyle("E$i")->getAlignment()->setWrapText(true);

        $gesamtmenge_kunde = $portionen * ($menge_pro_portion->getMenge() * $faktor);
        $gesamtmenge_tag_speise += $gesamtmenge_kunde;
    }
    /* $sheet->setCellValue("A$i", "" . $last_tour->getName(). ' [Sp ' . $speise_nr . ']' . 'ENDE');
      $sheet->setCellValue("B$i", $datum_str);
      $sheet->setCellValue("C$i", 'T' . str_pad($last_tour->getId(), 4, '0', STR_PAD_LEFT) . '-' . sprintf('%02d', $tag) . sprintf('%02d', $monat) . sprintf('%02d', $jahr). '-' . $speise_nr );
      $i++; */

    //$sheet->getStyle("C4")->getFont()->setSize(12);
    //$sheet->getStyle("B4")->getFont()->setSize(12);
    //$sheet->setCellValue("A$i",$speise->getBezeichnung());
    $sheet->getStyle("A$i:E$i")->applyFromArray($style_header_1);
    $i++;
    $sheet->getStyle("A$i:E$i")->getFont()->setSize(15);

    //$sheet->setCellValue("A$i","Insgesamt", $format_bold);
    //$sheet->setCellValue("B$i",$gesamtmenge_tag_portionen);
    //$sheet->setCellValue("C$i","");
    //$sheet->setCellValue("D$i",$gesamtmenge_tag_speise .' '.$menge_pro_portion->getEinheit());

    $ttt = $i + 1;
    if ($menge_pro_portion->getEinheit() == 'g' || $menge_pro_portion->getEinheit() == 'ml') {
        $gesamtmenge_tag_speise_umger = $gesamtmenge_tag_speise / 1000;
        switch ($menge_pro_portion->getEinheit()) {
            case 'g':
                $einheit = 'kg';
                break;
            case 'ml':
                $einheit = 'L';
                break;
        }
    }


    $writer = PhpOffice\PhpSpreadsheet\IOFactory::createWriter($xls, "Xls");
    $sheet->getColumnDimension('A')->setWidth(37);
    $sheet->getColumnDimension('B')->setWidth(15);
    $sheet->getColumnDimension('C')->setWidth(20);
    $sheet->getColumnDimension('D')->setWidth(20);
    $sheet->getColumnDimension('E')->setWidth(20);

    $heute = strftime('%d_%m_%Y', time());
    $rechner = php_uname('n');
    $speise_bezeichnung = $speise->getBezeichnung();
    $speise_bezeichnung = umlautepas($speise_bezeichnung);
    //var_dump($speise_bezeichnung);


    if ($_SESSION['is_local_server']) {
        $writer->save('export_einrichtungslisten/Einrichtungen_' . 'Sp' . $speise_nr . '_' . strftime('%d_%m_%y', $ts) . ".xls");
    } else {
        $writer->save('/is/htdocs/wp1055935_1F55KTNHIC/www/sbar/verwaltung/export_einrichtungslisten/Einrichtungen_' . 'Sp' . $speise_nr . '_' . strftime('%d_%m_%y', $ts) . ".xls");
    }

    if ($_SESSION['is_local_server']) {
        header('location:export_einrichtungslisten/Einrichtungen_' . 'Sp' . $speise_nr . '_' . strftime('%d_%m_%y', $ts) . ".xls");
    } else {
        header('location:http://www.s-bar.net/verwaltung/export_einrichtungslisten/Einrichtungen_' . 'Sp' . $speise_nr . '_' . strftime('%d_%m_%y', $ts) . ".xls");
    }

    /*
      if ($rechner == 'HP14603320956') {
      $writer->save('export_einrichtungslisten/Einrichtungen_' . strftime('%d_%m_%y', $ts) . ".xls");
      } else {
      $writer->save('/is/htdocs/wp1055935_1F55KTNHIC/www/sbar/verwaltung/export_einrichtungslisten/Einrichtungen_' . strftime('%d_%m_%y', $ts) . ".xls");
      }

      if ($rechner == 'HP14603320956') {
      header('location:export_einrichtungslisten/Einrichtungen_' . strftime('%d_%m_%y', $ts) . ".xls");
      } else {
      header('location:http://www.s-bar.net/verwaltung/export_einrichtungslisten/Einrichtungen_' . strftime('%d_%m_%y', $ts) . ".xls");
      } */
}

function erzeugeEinrichtungsliste($tag, $monat, $jahr, $starttag, $startmonat, $startjahr, $speise_id, $bestellung, $bestellung_has_speiseVerwaltung, $speiseVerwaltung, $menge_pro_portionVerwaltung, $standardportionenVerwaltung, $portionenaenderungVerwaltung, $kunden, $indifaktorVerwaltung, $bemerkungzutagVerwaltung, $bemerkungzuspeiseVerwaltung, $speise_nr) {
    require_once('../verwaltung_alt/PHPExcel-1.7.5/PHPExcel.php');
    require_once('../verwaltung_alt/PHPExcel-1.7.5/PHPExcel/IOFactory.php');

    $ts = mktime(12, 0, 0, $monat, $tag, $jahr);
    // neue instanz erstellen
    $xls = new PHPExcel();
    // dokumenteigenschaften schreiben
    $xls->getProperties()->setCreator("S-Bar")
        ->setLastModifiedBy("S-Bar")
        ->setTitle("Tagesaufstellung " . strftime('%d.%m.%y', $ts))
        ->setSubject("Tagesaufstellung " . strftime('%d.%m.%y', $ts));
    // das erste worksheet anwaehlen
    $sheet = $xls->setActiveSheetIndex(0);

    //STYLES START
    //$sheet->getStyle('A1')->getFont()->setBold(true);
    // $sheet->getStyle('A2:D2')->getFont()->setBold(true);
    $sheet->getColumnDimension('A')->setWidth(30);
    $sheet->getColumnDimension('B')->setWidth(20);
    $sheet->getColumnDimension('C')->setWidth(25);
    $sheet->getColumnDimension('D')->setWidth(15);
    $sheet->getColumnDimension('A')->setAutoSize(false);

    $default_border = array(
    );
    $style_header_1 = array(
    );
    $style_header = array(
    );
    $style_row = array(
    );
    $style_row_2 = array(
    );
    $style_row_durchgestr = array(
    );
    $style_row_durchgestr_2 = array(
    );
    $style_row_gesamt = array(
    );
    $style_col1 = array(
    );
    $style_col1_2 = array(
    );

    //$sheet->getStyle('A3:E3')->applyFromArray( $style_header_1 );
    //$sheet->getStyle('A4:E4')->applyFromArray( $style_header );
    //STYLES ENDE
    $i = 1;
    $x = $i - 1;
    // den namen vom Worksheet 1 definieren

    $xls->getActiveSheet()->setTitle("Einrichtungsliste " . strftime('%d.%m.%y', $ts));
    $i = 1;
    $bestellung_has_speise_array = $bestellung_has_speiseVerwaltung->findeAnhandVonBestellungId($bestellung->getId());

    $gesamtmenge_tag_speise = 0;
    $gesamtmenge_tag_portionen = 0;
    $c = $i - 2;
    $d = $i - 1;
    //$sheet->getStyle("A$d:E$d")->getFont()->setSize(15);
    //$sheet->getStyle("A$c:E$c")->getFont()->setSize(15);



    $speise = $speiseVerwaltung->findeAnhandVonId($speise_id);
    //$sheet->setCellValue("A$c",$speise->getBezeichnung());
    // den wert test in das Feld A1 schreiben
    // $sheet->setCellValue("E$d","Menge/P Standard");
    //$sheet->setCellValue("F$d","Faktor");
    foreach ($kunden as $kunde) {

        /*
          if ($i == 35 || $i == 70 || $i == 105 || $i == 140 || $i == 175 || $i == 210) {
          $sheet->setCellValue("A$i",$speise->getBezeichnung());
          $sheet->getStyle("A$i:E$i")->applyFromArray( $style_header_1 );
          $i++;
          } */
        if ($i % 2) {
            $sheet->getStyle("A$i")->applyFromArray($style_col1);
            $sheet->getStyle("B$i")->applyFromArray($style_row);
            $sheet->getStyle("C$i")->applyFromArray($style_row);
            $sheet->getStyle("D$i")->applyFromArray($style_row);
            $sheet->getStyle("E$i")->applyFromArray($style_row);
        } else {
            $sheet->getStyle("A$i")->applyFromArray($style_col1_2);
            $sheet->getStyle("B$i")->applyFromArray($style_row_2);
            $sheet->getStyle("C$i")->applyFromArray($style_row_2);
            $sheet->getStyle("D$i")->applyFromArray($style_row_2);
            $sheet->getStyle("E$i")->applyFromArray($style_row_2);
        }

        /// $sheet->getStyle("A$i:E$i")->getFont()->setSize(15);
        //  $sheet->getStyle("C$i")->getFont()->setSize(8);
        //  $sheet->getStyle("B$i")->getFont()->setSize(8);
        //  $sheet->getStyle("E$i")->getFont()->setSize(12);
        // $sheet->getStyle("E$i")->applyFromArray( $style_row );
        //$sheet->getStyle("F$i")->applyFromArray( $style_row );
        $speise_id = $speise->getId();
        $indi_faktor = $indifaktorVerwaltung->findeAnhandVonSpeiseIdUndKundeId($speise_id, $kunde->getId());
        $faktor = $indi_faktor->getFaktor();
        $einrichtungskategorie_id = $kunde->getEinrichtungskategorieId();
        $menge_pro_portion = $menge_pro_portionVerwaltung->findeAnhandVonSpeiseIdUndEinrichtungskategorieId($speise_id, $einrichtungskategorie_id);

        $standardportionen = $standardportionenVerwaltung->findeAnhandVonKundenId($kunde->getId());
        $portionenaenderung = $portionenaenderungVerwaltung->findeAnhandVonKundenIdUndWochenstartdatum($kunde->getId(), $starttag, $startmonat, $startjahr);

        if ($portionenaenderung->getId()) {
            $portionen_mo = $portionenaenderung->getPortionenMo();
            $portionen_di = $portionenaenderung->getPortionenDi();
            $portionen_mi = $portionenaenderung->getPortionenMi();
            $portionen_do = $portionenaenderung->getPortionenDo();
            $portionen_fr = $portionenaenderung->getPortionenFr();
            $aenderung = true;
        } else {
            $portionen_mo = $standardportionen->getPortionenMo();
            $portionen_di = $standardportionen->getPortionenDi();
            $portionen_mi = $standardportionen->getPortionenMi();
            $portionen_do = $standardportionen->getPortionenDo();
            $portionen_fr = $standardportionen->getPortionenFr();
            $aenderung = false;
        }

        if ($kunde->getAnzahlSpeisen() > 1) {

            $standardportionen = $standardportionenVerwaltung->findeAnhandVonKundenIdUndSpeiseNr($kunde->getId(), 1);
            $portionenaenderung = $portionenaenderungVerwaltung->findeAnhandVonKundenIdUndWochenstartdatum($kunde->getId(), $starttag, $startmonat, $startjahr, 1);
            if ($portionenaenderung->getId()) {
                $portionen_mo = $portionenaenderung->getPortionenMo();
                $portionen_di = $portionenaenderung->getPortionenDi();
                $portionen_mi = $portionenaenderung->getPortionenMi();
                $portionen_do = $portionenaenderung->getPortionenDo();
                $portionen_fr = $portionenaenderung->getPortionenFr();
                $aenderung = true;
            } else {
                $portionen_mo = $standardportionen->getPortionenMo();
                $portionen_di = $standardportionen->getPortionenDi();
                $portionen_mi = $standardportionen->getPortionenMi();
                $portionen_do = $standardportionen->getPortionenDo();
                $portionen_fr = $standardportionen->getPortionenFr();
                $aenderung = false;
            }
        }
        $wochentag_string = strftime('%a', $ts);
        switch ($wochentag_string) {
            case 'Mo':
                $portionen = $portionen_mo;
                if ($portionen_mo != $standardportionen->getPortionenMo()) {
                    //$portionen .= ' (' . $standardportionen->getPortionenMo().')';
                }
                break;
            case 'Di':
                $portionen = $portionen_di;
                if ($portionen_di != $standardportionen->getPortionenDi()) {
                    //$portionen .= ' (' . $standardportionen->getPortionenDi().')';
                }
                break;
            case 'Mi':
                $portionen = $portionen_mi;
                if ($portionen_mi != $standardportionen->getPortionenMi()) {
                    //$portionen .= ' (' . $standardportionen->getPortionenMi().')';
                }
                break;
            case 'Do':
                $portionen = $portionen_do;
                if ($portionen_do != $standardportionen->getPortionenDo()) {
                    //$portionen .= ' (' . $standardportionen->getPortionenDo().')';
                }
                break;
            case 'Fr':
                $portionen = $portionen_fr;
                if ($portionen_fr != $standardportionen->getPortionenFr()) {
                    //$portionen .= ' (' . $standardportionen->getPortionenFr().')';
                }
                break;
        }

        $gesamtmenge_tag_portionen += $portionen;
        $gesamtmenge_kunde = $portionen * ($menge_pro_portion->getMenge() * $faktor);

        if ($portionen == 0) {
            if ($i % 2) {
                $sheet->getStyle("A$i")->applyFromArray($style_row_durchgestr);
                $sheet->getStyle("B$i")->applyFromArray($style_row_durchgestr);
                $sheet->getStyle("C$i")->applyFromArray($style_row_durchgestr);
                $sheet->getStyle("D$i")->applyFromArray($style_row_durchgestr);
                $sheet->getStyle("E$i")->applyFromArray($style_row_durchgestr);
            } else {
                $sheet->getStyle("A$i")->applyFromArray($style_row_durchgestr_2);
                $sheet->getStyle("B$i")->applyFromArray($style_row_durchgestr_2);
                $sheet->getStyle("C$i")->applyFromArray($style_row_durchgestr_2);
                $sheet->getStyle("D$i")->applyFromArray($style_row_durchgestr_2);
                $sheet->getStyle("E$i")->applyFromArray($style_row_durchgestr_2);
            }
        }

        if ($kunde->getEinrichtungskategorieId() == 5 || $portionen == 0) {
            /* $sheet->setCellValue("A$i",$kunde->getName());
              $sheet->getStyle("A$i:D$i")->getFill()->applyFromArray(
              array(
              'type'       => PHPExcel_Style_Fill::FILL_SOLID,
              'startcolor' => array('rgb' => 'cccccc'),
              'endcolor' => array('rgb' => 'cccccc')
              )
              ); */
        } else {

            $code = $kunde->getId() . '-' . sprintf('%02d', $tag) . sprintf('%02d', $monat) . sprintf('%02d', $jahr);
            $datum_str = $wochentag_string . ' ' . $tag . '.' . $monat . '.' . $jahr;

            /*
              $bestellung_has_speise_array = $bestellung_has_speiseVerwaltung->findeAnhandVonBestellungId($bestellung->getId());
              foreach ($bestellung_has_speise_array as $bestellung_has_speise) {

              $speise = $speiseVerwaltung->findeAnhandVonId($bestellung_has_speise->getSpeiseId());
              $speise_id = $speise->getId();
              if ($speise_id) {
              $code .= '-' . $speise_id;
              }
              } */

            $add = '';
            for ($r = 1; $r <= $kunde->getAnzahlSpeisen(); $r++) {
                $add1 = '';
                if ($kunde->getAnzahlSpeisen() > 1) {
                    $add1 .= ' [Sp ' . $r . ']';
                }
                for ($c = 1; $c <= $kunde->getAnzahlBoxen(); $c++) {
                    $add2 = '';
                    if ($kunde->getAnzahlBoxen() > 1) {
                        $add2 .= ' [Box ' . $c . ']';
                    }
                    $sheet->setCellValue("A$i", $kunde->getName() . $add1 . $add2);

                    $sheet->setCellValue("B$i", $datum_str);
                    if ($kunde->getEinrichtungskategorieId() != 5) {
                        $sheet->setCellValue("C$i", $code . '-' . $r . '-' . $c);
                    }
                    $i++;
                }
            }


            $bemerkungen_str = '';
            //  $sheet->setCellValue("C$i",$menge_pro_portion->getMenge()*$faktor. ' '. $menge_pro_portion->getEinheit());
            //   $sheet->setCellValue("D$i",$gesamtmenge_kunde /*.' '.$menge_pro_portion->getEinheit()*/);
            $bemerkung_zu_tag = $bemerkungzutagVerwaltung->findeAnhandVonKundeIdUndDatumUndSpeiseId($kunde->getId(), $tag, $monat, $jahr, $speise->getId());
            $bemerkung_zu_speise = $bemerkungzuspeiseVerwaltung->findeAnhandVonKundeIdUndSpeiseId($kunde->getId(), $speise->getId());

            if ($bemerkung_zu_speise->getBemerkung() != '') {
                $bemerkungen_str .= $bemerkung_zu_speise->getBemerkung() . '; ';
            }
            if ($bemerkung_zu_tag->getBemerkung() != '') {
                $bemerkungen_str .= $bemerkung_zu_tag->getBemerkung() . '; ';
            }
            if ($kunde->getBemerkung() != '') {
                // $bemerkungen_str .= $kunde->getBemerkung().'; ';
            }

            //  $sheet->setCellValue("E$i",$bemerkungen_str);
            //  $sheet->setCellValue("F$i",$speise->getBezeichnung());
            // $sheet->setCellValue("G$i", strftime('%d.%m.%y',$tag));
        }


        $sheet->getStyle("E$i")->getAlignment()->setWrapText(true);
        //  $sheet->setCellValue("E$i",$menge_pro_portion->getMenge() . ' ' . $menge_pro_portion->getEinheit() . ' /P');
        //  $sheet->setCellValue("F$i",$faktor);
        if ($kunde->getEinrichtungskategorieId() != 5 && $portionen != 0) {
            // $i++;
        }
        $gesamtmenge_kunde = $portionen * ($menge_pro_portion->getMenge() * $faktor);
        $gesamtmenge_tag_speise += $gesamtmenge_kunde;
    }
    //$sheet->getStyle("C4")->getFont()->setSize(12);
    //$sheet->getStyle("B4")->getFont()->setSize(12);
    //$sheet->setCellValue("A$i",$speise->getBezeichnung());
    $sheet->getStyle("A$i:E$i")->applyFromArray($style_header_1);
    $i++;
    $sheet->getStyle("A$i:E$i")->getFont()->setSize(15);

    //$sheet->setCellValue("A$i","Insgesamt", $format_bold);
    //$sheet->setCellValue("B$i",$gesamtmenge_tag_portionen);
    //$sheet->setCellValue("C$i","");
    //$sheet->setCellValue("D$i",$gesamtmenge_tag_speise .' '.$menge_pro_portion->getEinheit());

    $ttt = $i + 1;
    if ($menge_pro_portion->getEinheit() == 'g' || $menge_pro_portion->getEinheit() == 'ml') {
        $gesamtmenge_tag_speise_umger = $gesamtmenge_tag_speise / 1000;
        switch ($menge_pro_portion->getEinheit()) {
            case 'g':
                $einheit = 'kg';
                break;
            case 'ml':
                $einheit = 'L';
                break;
        }
    }

    //$sheet->setCellValue("D$ttt",$gesamtmenge_tag_speise_umger .' '.$einheit);
    // $sheet->getStyle("A2:D2")->applyFromArray( $style_row_gesamt );
    // $sheet->getStyle("A$i:D$i")->applyFromArray( $style_row_gesamt );
    /*
      if ($speise->getRezept() != '') {
      $i += 5;
      $sheet->setCellValue("A$i","REZEPT/BEMERK.:");
      $sheet->getStyle("A$i")->getFont()->setBold(true);
      $i++;
      $sheet->mergeCells("A$i:E$i");
      $sheet->setCellValue("A$i", $speise->getRezept());
      } */

    // $sheet->setCellValue("A1",strftime('%d.%m.%Y', $tag));
    // $sheet->getStyle("A1:E1")->getFont()->setSize(14);
    // $sheet->setCellValue("A2","Insgesamt", $format_bold);
    // $sheet->setCellValue("B2",$gesamtmenge_tag_portionen);
    // $sheet->setCellValue("C2","");
    // $sheet->setCellValue("D2",$gesamtmenge_tag_speise .' '.$menge_pro_portion->getEinheit());

    $writer = PHPExcel_IOFactory::createWriter($xls, "Excel5");
    $sheet->getColumnDimension('A')->setWidth(37);
    $sheet->getColumnDimension('B')->setWidth(15);
    $sheet->getColumnDimension('C')->setWidth(20);
    $sheet->getColumnDimension('D')->setWidth(12);
    $sheet->getColumnDimension('E')->setWidth(27);

    $heute = strftime('%d_%m_%Y', time());
    $rechner = php_uname('n');
    $speise_bezeichnung = $speise->getBezeichnung();
    $speise_bezeichnung = umlautepas($speise_bezeichnung);
    //var_dump($speise_bezeichnung);


    if ($_SESSION['is_local_server']) {
        $writer->save('export_einrichtungslisten/Einrichtungen_' . strftime('%d_%m_%y', $ts) . ".xls");
    } else {
        $writer->save('/is/htdocs/wp1055935_1F55KTNHIC/www/sbar/verwaltung/export_einrichtungslisten/Einrichtungen_' . strftime('%d_%m_%y', $ts) . ".xls");
    }

    if ($_SESSION['is_local_server']) {
        header('location:export_einrichtungslisten/Einrichtungen_' . strftime('%d_%m_%y', $ts) . ".xls");
    } else {
        header('location:http://www.s-bar.net/verwaltung/export_einrichtungslisten/Einrichtungen_' . strftime('%d_%m_%y', $ts) . ".xls");
    }

    /*
      if ($rechner == 'HP14603320956') {
      $writer->save('export_einrichtungslisten/Einrichtungen_' . strftime('%d_%m_%y', $ts) . ".xls");
      } else {
      $writer->save('/is/htdocs/wp1055935_1F55KTNHIC/www/sbar/verwaltung/export_einrichtungslisten/Einrichtungen_' . strftime('%d_%m_%y', $ts) . ".xls");
      }

      if ($rechner == 'HP14603320956') {
      header('location:export_einrichtungslisten/Einrichtungen_' . strftime('%d_%m_%y', $ts) . ".xls");
      } else {
      header('location:http://www.s-bar.net/verwaltung/export_einrichtungslisten/Einrichtungen_' . strftime('%d_%m_%y', $ts) . ".xls");
      } */
}

function aktualisiereDatenMitTagMonatJahr($abrechnungstagVerwaltung, $bestellungVerwaltung, $portionenaenderungVerwaltung, $bemerkungzutagVerwaltung) {
    /* $abrechnungstage = $abrechnungstagVerwaltung->findeAlle();
      $c = 0;
      foreach ($abrechnungstage as $abrechnungstag) {
      if ($abrechnungstag->getTag2() == NULL && $abrechnungstag->getMonat() == NULL) {
      $abrechnungstag->setTag2(date('d',$abrechnungstag->getTag()+3600));
      $abrechnungstag->setMonat(date('m',$abrechnungstag->getTag()+3600));
      $abrechnungstag->setJahr(date('Y',$abrechnungstag->getTag()+3600));
      $abrechnungstagVerwaltung->speichere($abrechnungstag);
      }
      $c++;

      } */
    $bestellungen = $bestellungVerwaltung->findeAlle();
    $c = 0;
    foreach ($bestellungen as $bestellung) {
        if ($bestellung->getTag2() == NULL && $bestellung->getMonat() == NULL) {
            $bestellung->setTag2(date('d', $bestellung->getTag() + 3600));
            $bestellung->setMonat(date('m', $bestellung->getTag() + 3600));
            $bestellung->setJahr(date('Y', $bestellung->getTag() + 3600));
            $bestellungVerwaltung->speichere($bestellung);
        }
        $c++;
    }

    /*
      $bemerkungen_zu_tag = $bemerkungzutagVerwaltung->findeAlle();
      $c = 0;
      foreach ($bemerkungen_zu_tag as $bemerkung_zu_tag) {
      if ($bemerkung_zu_tag->getTag2() == NULL && $bemerkung_zu_tag->getMonat() == NULL) {
      $bemerkung_zu_tag->setTag2(date('d',$bemerkung_zu_tag->getTag()+3600));
      $bemerkung_zu_tag->setMonat(date('m',$bemerkung_zu_tag->getTag()+3600));
      $bemerkung_zu_tag->setJahr(date('Y',$bemerkung_zu_tag->getTag()+3600));
      $bemerkungzutagVerwaltung->speichere($bemerkung_zu_tag);
      }
      $c++;

      }


      $portionenaenderungen = $portionenaenderungVerwaltung->findeAlle();
      $c = 0;
      foreach ($portionenaenderungen as $portionenaenderung) {
      if ($portionenaenderung->getStarttag() == NULL && $portionenaenderung->getStartmonat() == NULL) {
      $portionenaenderung->setStarttag(date('d',$portionenaenderung->getWochenstarttagts()));
      $portionenaenderung->setStartmonat(date('m',$portionenaenderung->getWochenstarttagts()));
      $portionenaenderung->setStartjahr(date('Y',$portionenaenderung->getWochenstarttagts()));
      $portionenaenderungVerwaltung->speichere($portionenaenderung);
      }
      $c++;
      } */
}

function erzeugeWochenmengenUebersichtExcelAltesLayout($mengen_woche, $starttag, $startmonat, $startjahr, $color_speisen) {
    require_once('../verwaltung_alt/PHPExcel-1.7.5/PHPExcel.php');
    require_once('../verwaltung_alt/PHPExcel-1.7.5/PHPExcel/IOFactory.php');
    /* echo '<pre>';
      var_dump($mengen_woche);
      echo '</pre>'; */
    // neue instanz erstellen
    $xls = new PHPExcel();
    // dokumenteigenschaften schreiben
    $xls->getProperties()->setCreator("S-Bar")
        ->setLastModifiedBy("S-Bar")
        ->setTitle("Tagesmengen " . strftime('%d.%.m.%y', mktime(12, 0, 0, $startmonat, $starttag, $startjahr)))
        ->setSubject("Tagesmengen " . strftime('%d.%.m.%y', mktime(12, 0, 0, $startmonat, $starttag, $startjahr)));
    // das erste worksheet anwaehlen
    $sheet = $xls->setActiveSheetIndex(0);

    //STYLES START
    $sheet->getStyle('A1')->getFont()->setBold(true);
    $sheet->getStyle('A2:D2')->getFont()->setBold(true);
    $sheet->getColumnDimension('A')->setWidth(30);
    $sheet->getColumnDimension('B')->setWidth(15);
    $sheet->getColumnDimension('C')->setWidth(15);
    $sheet->getColumnDimension('D')->setWidth(5);
    $sheet->getColumnDimension('E')->setWidth(30);
    $sheet->getColumnDimension('F')->setWidth(15);
    $sheet->getColumnDimension('G')->setWidth(15);
    $sheet->getColumnDimension('I')->setWidth(30);
    $sheet->getColumnDimension('J')->setWidth(15);
    $sheet->getColumnDimension('K')->setWidth(15);
    $sheet->getColumnDimension('M')->setWidth(30);
    $sheet->getColumnDimension('N')->setWidth(15);
    $sheet->getColumnDimension('O')->setWidth(15);
    $sheet->getColumnDimension('A')->setAutoSize(false);

    $i = 1;
    $x = 0;

    foreach ($mengen_woche as $menge_tag) {
        $keys = array_keys($menge_tag);

        $tag_deu = strftime('%A', $menge_tag[$keys[0]]['Timestamp']);
        //$sheet->setCellValue("A$i",strftime('%d.%m.%y',mktime(12,0,0,$startmonat,$starttag,$startjahr)+$x*86400), $format_bold);
        $sheet->setCellValue("A$i", $tag_deu . ' ' . $menge_tag[$keys[0]]['Datum'], $format_bold);
        $sheet->setCellValue("E$i", $tag_deu . ' ' . $menge_tag[$keys[0]]['Datum'], $format_bold);
        $sheet->setCellValue("I$i", $tag_deu . ' ' . $menge_tag[$keys[0]]['Datum'], $format_bold);
        $sheet->setCellValue("M$i", $tag_deu . ' ' . $menge_tag[$keys[0]]['Datum'], $format_bold);
        $sheet->getStyle("A$i:M$i")->getFont()->setBold(true);
        $sheet->getStyle("A$i")->applyFromArray(
            array(
                'fill' => array(
                    'type' => PHPExcel_Style_Fill::FILL_SOLID,
                    'color' => array('rgb' => str_replace('#', '', $color_speisen[1]))
                )
            )
        );
        $sheet->getStyle("E$i")->applyFromArray(
            array(
                'fill' => array(
                    'type' => PHPExcel_Style_Fill::FILL_SOLID,
                    'color' => array('rgb' => str_replace('#', '', $color_speisen[2]))
                )
            )
        );
        $sheet->getStyle("I$i")->applyFromArray(
            array(
                'fill' => array(
                    'type' => PHPExcel_Style_Fill::FILL_SOLID,
                    'color' => array('rgb' => str_replace('#', '', $color_speisen[3]))
                )
            )
        );
        $sheet->getStyle("M$i")->applyFromArray(
            array(
                'fill' => array(
                    'type' => PHPExcel_Style_Fill::FILL_SOLID,
                    'color' => array('rgb' => str_replace('#', '', $color_speisen[4]))
                )
            )
        );

        $i++;

        // den wert test in das Feld A1 schreiben
        $sheet->getStyle("A$i")->applyFromArray(
            array(
                'fill' => array(
                    'type' => PHPExcel_Style_Fill::FILL_SOLID,
                    'color' => array('rgb' => 'dddddd')
                )
            )
        );
        $sheet->setCellValue("A$i", "Speise 1", $format_bold);
        $sheet->setCellValue("B$i", "Gesamt");
        $sheet->setCellValue("C$i", "umgerechnet");
        // $sheet->setCellValue("E$i", "Speise #");


        $sheet->getStyle("E$i")->applyFromArray(
            array(
                'fill' => array(
                    'type' => PHPExcel_Style_Fill::FILL_SOLID,
                    'color' => array('rgb' => 'dddddd')
                )
            )
        );
        $sheet->getStyle("I$i")->applyFromArray(
            array(
                'fill' => array(
                    'type' => PHPExcel_Style_Fill::FILL_SOLID,
                    'color' => array('rgb' => 'dddddd')
                )
            )
        );
        $sheet->getStyle("M$i")->applyFromArray(
            array(
                'fill' => array(
                    'type' => PHPExcel_Style_Fill::FILL_SOLID,
                    'color' => array('rgb' => 'dddddd')
                )
            )
        );
        $sheet->setCellValue("E$i", "Speise 2", $format_bold);
        $sheet->setCellValue("F$i", "Gesamt");
        $sheet->setCellValue("G$i", "umgerechnet");

        $sheet->setCellValue("I$i", "Speise 3", $format_bold);
        $sheet->setCellValue("J$i", "Gesamt");
        $sheet->setCellValue("K$i", "umgerechnet");

        $sheet->setCellValue("M$i", "Speise 4", $format_bold);
        $sheet->setCellValue("N$i", "Gesamt");
        $sheet->setCellValue("O$i", "umgerechnet");
        //  $sheet->setCellValue("E$i", "Speise #");

        $sheet->getStyle("A$i:O$i")->getFont()->setBold(true);
        $i++;
        $start_zeile_tag = $i;
        $start_zeile_tag3 = $i;
        $start_zeile_tag4 = $i;
        foreach ($menge_tag as $speise) {
            switch ($speise['Speisennr']) {
                case 1:
                    $sheet->setCellValue("A$i", $speise['Speise'], $format_bold);
                    $sheet->setCellValue("B$i", $speise['Menge']);
                    $sheet->setCellValue("C$i", $speise['Umgerechnet']);
                    $i++;
                    break;
                case 2:
                    $sheet->setCellValue("E$start_zeile_tag", $speise['Speise'], $format_bold);
                    $sheet->setCellValue("F$start_zeile_tag", $speise['Menge']);
                    $sheet->setCellValue("G$start_zeile_tag", $speise['Umgerechnet']);
                    $start_zeile_tag++;
                    if ($start_zeile_tag > $i) {
                        $i++;
                    }
                    break;
                case 3:
                    $sheet->setCellValue("I$start_zeile_tag3", $speise['Speise'], $format_bold);
                    $sheet->setCellValue("J$start_zeile_tag3", $speise['Menge']);
                    $sheet->setCellValue("K$start_zeile_tag3", $speise['Umgerechnet']);
                    $start_zeile_tag3++;
                    if ($start_zeile_tag3 > $i) {
                        $i++;
                    }
                    break;
                case 4:
                    $sheet->setCellValue("M$start_zeile_tag4", $speise['Speise'], $format_bold);
                    $sheet->setCellValue("N$start_zeile_tag4", $speise['Menge']);
                    $sheet->setCellValue("O$start_zeile_tag4", $speise['Umgerechnet']);
                    $start_zeile_tag4++;
                    if ($start_zeile_tag4 > $i) {
                        $i++;
                    }
                    break;
            }
            if ($speise['Speisennr'] == 1) {

            } else {

            }
            //$sheet->setCellValue("E$i", $speise['Speisennr']);
        }
        $i++;
        $x++;
    }

    $heute = strftime('%d_%m_%Y', time());
    $rechner = php_uname('n');

    $writer = PHPExcel_IOFactory::createWriter($xls, "Excel5");

    if ($rechner == 'HP14603320956' || strpos($rechner, 'SCHIEN')) {
        $writer->save('export_wochenmengen/Mengen_' . strftime('%d_%m_%y', mktime(12, 0, 0, $startmonat, $starttag, $startjahr)) . ".xls");
    } else {
        $writer->save('/is/htdocs/wp1055935_1F55KTNHIC/www/sbar/verwaltung/export_wochenmengen/Mengen_' . strftime('%d_%m_%y', mktime(12, 0, 0, $startmonat, $starttag, $startjahr)) . ".xls");
    }

    if ($rechner == 'HP14603320956' || strpos($rechner, 'SCHIEN')) {
        header('location:export_wochenmengen/Mengen_' . strftime('%d_%m_%y', mktime(12, 0, 0, $startmonat, $starttag, $startjahr)) . ".xls");
    } else {
        header('location:http://www.s-bar.net/verwaltung/export_wochenmengen/Mengen_' . strftime('%d_%m_%y', mktime(12, 0, 0, $startmonat, $starttag, $startjahr)) . ".xls");
    }
}

function erzeugeWochenmengenUebersichtExcel($mengen_woche, $starttag, $startmonat, $startjahr, $color_speisen) {
    /* require_once('../verwaltung_alt/PHPExcel-1.7.5/PHPExcel.php');
    require_once('../verwaltung_alt/PHPExcel-1.7.5/PHPExcel/IOFactory.php');*/

    require 'PhpSpreadsheet/PhpOffice/autoload.php';
    /* echo '<pre>';
      var_dump($mengen_woche);
      echo '</pre>'; */
    // neue instanz erstellen
    //$xls = new PHPExcel();
    $xls = new \PhpOffice\PhpSpreadsheet\Spreadsheet();
    // dokumenteigenschaften schreiben
    $xls->getProperties()->setCreator("S-Bar")
        ->setLastModifiedBy("S-Bar")
        ->setTitle("Tagesmengen " . strftime('%d.%.m.%y', mktime(12, 0, 0, $startmonat, $starttag, $startjahr)))
        ->setSubject("Tagesmengen " . strftime('%d.%.m.%y', mktime(12, 0, 0, $startmonat, $starttag, $startjahr)));
    // das erste worksheet anwaehlen
    $sheet = $xls->setActiveSheetIndex(0);

    //STYLES START

    $sheet->getColumnDimension('A')->setWidth(45);
    $sheet->getColumnDimension('B')->setWidth(15);
    $sheet->getColumnDimension('C')->setWidth(15);
    $sheet->getColumnDimension('D')->setWidth(5);
    $sheet->getColumnDimension('E')->setWidth(45);
    $sheet->getColumnDimension('F')->setWidth(15);
    $sheet->getColumnDimension('G')->setWidth(15);
    $sheet->getColumnDimension('I')->setWidth(45);
    $sheet->getColumnDimension('J')->setWidth(15);
    $sheet->getColumnDimension('K')->setWidth(15);
    $sheet->getColumnDimension('M')->setWidth(45);
    $sheet->getColumnDimension('N')->setWidth(15);
    $sheet->getColumnDimension('O')->setWidth(15);
    $sheet->getColumnDimension('Q')->setWidth(45);
    $sheet->getColumnDimension('R')->setWidth(15);
    $sheet->getColumnDimension('S')->setWidth(15);
    $sheet->getColumnDimension('A')->setAutoSize(false);
    $style_borders = array(
        'borders' => array(
            'allBorders' => array(
                'style' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_HAIR,
                'color' => array('rgb' => 'DDDDDD')
            )
        )
    );

    $format_bold = array(
        'font' => array(
            'bold' => true
        )
    );

    $style_fill = array(
        'fill' => array(
            'fillType' => PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
            'color' => array('rgb' => 'dddddd')
        )
    );
    $style_fill_speisecolor = array();
    for ($c = 1; $c <= 4; $c++) {
        $style_fill_speisecolor[$c] = array(
            'fill' => array(
                'fillType' => PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
                'color' => array('rgb' => str_replace('#', '', $color_speisen[$c]))
            )
        );
    }

    $x = 0;

    $tag_count = 0;
    $count_zeilenzahl_max = 0;
    $spalten_fuer_tage = array(
        '1' => array('A', 'B', 'C'),
        '2' => array('E', 'F', 'G'),
        '3' => array('I', 'J', 'K'),
        '4' => array('M', 'N', 'O'),
        '5' => array('Q', 'R', 'S'),
    );

    $zeilenanzahl_speisen = array(
        '1' => 1,
        '2' => 0,
        '3' => 0,
        '4' => 0
    );
    foreach ($mengen_woche as $tag_str => $menge_tage) {
        foreach ($menge_tage as $speise_nr => $menge_tag) {
            /*  if ($speise_nr == 1) {
              continue;
              } */
            if (count($menge_tag) > $zeilenanzahl_speisen[$speise_nr]) {

                /* echo '<pre>';
                  var_dump($speise_nr, count($menge_tag));
                  echo '</pre>'; */
                $zeilenanzahl_speisen[$speise_nr] = count($menge_tag);
            }
        }
    }
    $zeilen_start_speisen = array();
    $zeilen_start_speisen[1] = 1;
    foreach ($zeilenanzahl_speisen as $speise_nr => $zeilen_speise) {
        for ($r = 1; $r <= $speise_nr; $r++) {
            $zeilen_start_speisen[$speise_nr + 1] += $zeilenanzahl_speisen[$r] + 3;
            $zeilen_start_speisen[$speise_nr + 1]++;
        }
    }
    /* echo '<pre>';
      var_dump($zeilen_start_speisen, $zeilenanzahl_speisen);
      echo '</pre>'; */
    //exit;
    $c_tag = 0;
    foreach ($mengen_woche as $tag_str => $menge_tage) {
        $c_tag++;
        $spalte_1 = $spalten_fuer_tage[$c_tag][0];
        $spalte_2 = $spalten_fuer_tage[$c_tag][1];
        $spalte_3 = $spalten_fuer_tage[$c_tag][2];
        $sheet->setBreak($spalte_1 . '1', PhpOffice\PhpSpreadsheet\Worksheet\Worksheet::BREAK_COLUMN);
        foreach ($menge_tage as $speise_nr => $menge_tag) { //jede Speise Nr
            $i = $zeilen_start_speisen[$speise_nr];

            $sheet->getStyle($spalte_1 . '' . $i)->applyFromArray($style_fill_speisecolor[$speise_nr]);
            $sheet->getStyle($spalte_1 . '' . $i)->applyFromArray($format_bold);
            $sheet->setCellValue($spalte_1 . '' . $i, $tag_str, $format_bold);
            $i++;
            $sheet->getStyle($spalte_1 . $i . ':' . $spalte_3 . $i)->applyFromArray($format_bold);
            $sheet->getStyle($spalte_1 . $i . ':' . $spalte_3 . $i)->applyFromArray($style_fill);
            $sheet->setCellValue($spalte_1 . '' . $i, "Speise " . $speise_nr, $format_bold);
            $sheet->setCellValue($spalte_2 . '' . $i, "Gesamt");
            $sheet->setCellValue($spalte_3 . '' . $i, "umgerechnet");
            $i++;
            foreach ($menge_tag as $komponente_str => $komp_daten) {
                $sheet->getStyle($spalte_1 . $i . ':' . $spalte_3 . $i)->applyFromArray($style_borders);
                $sheet->setCellValue($spalte_1 . '' . $i, $komp_daten['Speise'], $format_bold);
                $sheet->setCellValue($spalte_2 . '' . $i, $komp_daten['Menge']);
                $sheet->setCellValue($spalte_3 . '' . $i, $komp_daten['Umgerechnet']);
                $i++;
            }
            /* echo '<pre>';
              var_dump($tag_str, $speise_nr, $menge_tag);
              echo '</pre>'; */
        }

        $heute = strftime('%d_%m_%Y', time());
        $rechner = php_uname('n');

        $writer = PhpOffice\PhpSpreadsheet\IOFactory::createWriter($xls, "Xls");

        if ($rechner == 'HP14603320956' || strpos($rechner, 'SCHIEN')) {
            $writer->save('export_wochenmengen/Mengen_' . strftime('%d_%m_%y', mktime(12, 0, 0, $startmonat, $starttag, $startjahr)) . ".xls");
        } else {
            $writer->save('export_wochenmengen/Mengen_' . strftime('%d_%m_%y', mktime(12, 0, 0, $startmonat, $starttag, $startjahr)) . ".xls");
        }

        if ($rechner == 'HP14603320956' || strpos($rechner, 'SCHIEN')) {
            header('location:export_wochenmengen/Mengen_' . strftime('%d_%m_%y', mktime(12, 0, 0, $startmonat, $starttag, $startjahr)) . ".xls");
        } else {
            header('location:export_wochenmengen/Mengen_' . strftime('%d_%m_%y', mktime(12, 0, 0, $startmonat, $starttag, $startjahr)) . ".xls");
        }
    }
    exit;

    foreach ($mengen_woche as $menge_tag) {
        $keys = array_keys($menge_tag);

        $tag_deu = strftime('%A', $menge_tag[$keys[0]]['Timestamp']);
        //$sheet->setCellValue("A$i",strftime('%d.%m.%y',mktime(12,0,0,$startmonat,$starttag,$startjahr)+$x*86400), $format_bold);
        $sheet->setCellValue("A$i", $tag_deu . ' ' . $menge_tag[$keys[0]]['Datum'], $format_bold);
        $sheet->setCellValue("E$i", $tag_deu . ' ' . $menge_tag[$keys[0]]['Datum'], $format_bold);
        $sheet->setCellValue("I$i", $tag_deu . ' ' . $menge_tag[$keys[0]]['Datum'], $format_bold);
        $sheet->setCellValue("M$i", $tag_deu . ' ' . $menge_tag[$keys[0]]['Datum'], $format_bold);
        $sheet->getStyle("A$i:M$i")->getFont()->setBold(true);
        $sheet->getStyle("A$i")->applyFromArray(
            array(
                'fill' => array(
                    'type' => PHPExcel_Style_Fill::FILL_SOLID,
                    'color' => array('rgb' => str_replace('#', '', $color_speisen[1]))
                )
            )
        );
        $sheet->getStyle("E$i")->applyFromArray(
            array(
                'fill' => array(
                    'type' => PHPExcel_Style_Fill::FILL_SOLID,
                    'color' => array('rgb' => str_replace('#', '', $color_speisen[2]))
                )
            )
        );
        $sheet->getStyle("I$i")->applyFromArray(
            array(
                'fill' => array(
                    'type' => PHPExcel_Style_Fill::FILL_SOLID,
                    'color' => array('rgb' => str_replace('#', '', $color_speisen[3]))
                )
            )
        );
        $sheet->getStyle("M$i")->applyFromArray(
            array(
                'fill' => array(
                    'type' => PHPExcel_Style_Fill::FILL_SOLID,
                    'color' => array('rgb' => str_replace('#', '', $color_speisen[4]))
                )
            )
        );

        $i++;

        // den wert test in das Feld A1 schreiben
        $sheet->getStyle("A$i")->applyFromArray(
            array(
                'fill' => array(
                    'type' => PHPExcel_Style_Fill::FILL_SOLID,
                    'color' => array('rgb' => 'dddddd')
                )
            )
        );

        $sheet->setCellValue("A$i", "Speise 1", $format_bold);
        $sheet->setCellValue("B$i", "Gesamt");
        $sheet->setCellValue("C$i", "umgerechnet");
        // $sheet->setCellValue("E$i", "Speise #");


        $sheet->getStyle("E$i")->applyFromArray(
            array(
                'fill' => array(
                    'type' => PHPExcel_Style_Fill::FILL_SOLID,
                    'color' => array('rgb' => 'dddddd')
                )
            )
        );
        $sheet->getStyle("I$i")->applyFromArray(
            array(
                'fill' => array(
                    'type' => PHPExcel_Style_Fill::FILL_SOLID,
                    'color' => array('rgb' => 'dddddd')
                )
            )
        );
        $sheet->getStyle("M$i")->applyFromArray(
            array(
                'fill' => array(
                    'type' => PHPExcel_Style_Fill::FILL_SOLID,
                    'color' => array('rgb' => 'dddddd')
                )
            )
        );
        $sheet->setCellValue("E$i", "Speise 2", $format_bold);
        $sheet->setCellValue("F$i", "Gesamt");
        $sheet->setCellValue("G$i", "umgerechnet");

        $sheet->setCellValue("I$i", "Speise 3", $format_bold);
        $sheet->setCellValue("J$i", "Gesamt");
        $sheet->setCellValue("K$i", "umgerechnet");

        $sheet->setCellValue("M$i", "Speise 4", $format_bold);
        $sheet->setCellValue("N$i", "Gesamt");
        $sheet->setCellValue("O$i", "umgerechnet");
        //  $sheet->setCellValue("E$i", "Speise #");

        $sheet->getStyle("A$i:O$i")->getFont()->setBold(true);
        $i++;
        $start_zeile_tag = $i;
        $start_zeile_tag3 = $i;
        $start_zeile_tag4 = $i;
        foreach ($menge_tag as $speise) {
            switch ($speise['Speisennr']) {
                case 1:
                    $sheet->setCellValue("A$i", $speise['Speise'], $format_bold);
                    $sheet->setCellValue("B$i", $speise['Menge']);
                    $sheet->setCellValue("C$i", $speise['Umgerechnet']);
                    $i++;
                    break;
                case 2:
                    $sheet->setCellValue("E$start_zeile_tag", $speise['Speise'], $format_bold);
                    $sheet->setCellValue("F$start_zeile_tag", $speise['Menge']);
                    $sheet->setCellValue("G$start_zeile_tag", $speise['Umgerechnet']);
                    $start_zeile_tag++;
                    if ($start_zeile_tag > $i) {
                        $i++;
                    }
                    break;
                case 3:
                    $sheet->setCellValue("I$start_zeile_tag3", $speise['Speise'], $format_bold);
                    $sheet->setCellValue("J$start_zeile_tag3", $speise['Menge']);
                    $sheet->setCellValue("K$start_zeile_tag3", $speise['Umgerechnet']);
                    $start_zeile_tag3++;
                    if ($start_zeile_tag3 > $i) {
                        $i++;
                    }
                    break;
                case 4:
                    $sheet->setCellValue("M$start_zeile_tag4", $speise['Speise'], $format_bold);
                    $sheet->setCellValue("N$start_zeile_tag4", $speise['Menge']);
                    $sheet->setCellValue("O$start_zeile_tag4", $speise['Umgerechnet']);
                    $start_zeile_tag4++;
                    if ($start_zeile_tag4 > $i) {
                        $i++;
                    }
                    break;
            }
            if ($speise['Speisennr'] == 1) {

            } else {

            }
            //$sheet->setCellValue("E$i", $speise['Speisennr']);
        }
        $i++;
        $x++;
    }

    $heute = strftime('%d_%m_%Y', time());
    $rechner = php_uname('n');

    $writer = PHPExcel_IOFactory::createWriter($xls, "Excel5");

    if ($rechner == 'HP14603320956' || strpos($rechner, 'SCHIEN')) {
        $writer->save('export_wochenmengen/Mengen_' . strftime('%d_%m_%y', mktime(12, 0, 0, $startmonat, $starttag, $startjahr)) . ".xls");
    } else {
        $writer->save('/is/htdocs/wp1055935_1F55KTNHIC/www/sbar/verwaltung/export_wochenmengen/Mengen_' . strftime('%d_%m_%y', mktime(12, 0, 0, $startmonat, $starttag, $startjahr)) . ".xls");
    }

    if ($rechner == 'HP14603320956' || strpos($rechner, 'SCHIEN')) {
        header('location:export_wochenmengen/Mengen_' . strftime('%d_%m_%y', mktime(12, 0, 0, $startmonat, $starttag, $startjahr)) . ".xls");
    } else {
        header('location:http://www.s-bar.net/verwaltung/export_wochenmengen/Mengen_' . strftime('%d_%m_%y', mktime(12, 0, 0, $startmonat, $starttag, $startjahr)) . ".xls");
    }
}

?>
