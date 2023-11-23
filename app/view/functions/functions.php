<?php

function pruefeAufStartdatumUndAktiviere($kundeVerwaltung) {
    $kunden = $kundeVerwaltung->findeAlleInaktivenMitStartdatum();
    foreach($kunden as $kunde) {
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
    $upas = Array("ä" => "ae", "ü" => "ue", "ö" => "oe", "Ä" => "Ae", "Ü" => "Ue", "Ö" => "Oe");
    return strtr($string, $upas);
}

function entferneSonderzeichen($string) {
    $string = str_replace(' ', '_', $string);
    $string = str_replace('/', '_', $string);
    return $string;
}

function register_generate_salt() {
    $pattern = "1234567890abcdefghijklmnopqrstuvwxyz";
    for ($i=0; $i<10; $i++) {
        if (isset($key)) {
            $key .= $pattern{rand(0,35)};
        } else {
            $key = $pattern{rand(0,35)};
        }
    }
    return $key;
}

function register_generate_pw() {
    $pattern = "1234567890abcdefghijklmnopqrstuvwxyz";
    for ($i=0; $i<8; $i++) {
        if (isset($pw)) {
            $pw .= $pattern{rand(0,35)};
        } else {
            $pw = $pattern{rand(0,35)};
        }
    }
    return $pw;
}

function erzeugeTagesaufstellungExcel($tag,$monat,$jahr,$starttag,$startmonat,$startjahr, $speise_id, $bestellung, $bestellung_has_speiseVerwaltung, $speiseVerwaltung,$menge_pro_portionVerwaltung,$standardportionenVerwaltung,$portionenaenderungVerwaltung, $kunden, $indifaktorVerwaltung, $bemerkungzutagVerwaltung, $bemerkungzuspeiseVerwaltung) {
    require_once('PHPExcel-1.7.5/PHPExcel.php');
    require_once('PHPExcel-1.7.5/PHPExcel/IOFactory.php');

    $ts = mktime(12,0,0,$monat,$tag,$jahr);
    // neue instanz erstellen
    $xls=new PHPExcel();
    // dokumenteigenschaften schreiben
    $xls->getProperties()->setCreator("S-Bar")
        ->setLastModifiedBy("S-Bar")
        ->setTitle("Tagesaufstellung ". strftime('%d.%.m.%y',$ts))
        ->setSubject("Tagesaufstellung ". strftime('%d.%.m.%y',$ts));
    // das erste worksheet anwaehlen
    $sheet=$xls->setActiveSheetIndex(0);

    //STYLES START
    $sheet->getStyle('A1')->getFont()->setBold(true);
    $sheet->getStyle('A2:D2')->getFont()->setBold(true);
    $sheet->getColumnDimension('A')->setWidth(30);
    $sheet->getColumnDimension('B')->setWidth(15);
    $sheet->getColumnDimension('C')->setWidth(15);
    $sheet->getColumnDimension('D')->setWidth(15);
    $sheet->getColumnDimension('A')->setAutoSize(false);

    $default_border = array(
        'style' => PHPExcel_Style_Border::BORDER_THIN,
        'color' => array('rgb'=>'BEBEBE')
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
        'color' => array('rgb'=>'FF0000'),
        ),
        'font' => array(
        'bold' => true,
        'color' => array('rgb'=>'ffffff'),
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
        'color' => array('rgb'=>'429E3A'),
        ),
        'font' => array(
        'bold' => true,
        'color' => array('rgb'=>'ffffff'),
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
        'color' => array('rgb'=>'E1E0F7'),
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
        'color' => array('rgb'=>'FFFFFF'),
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
        'color' => array('rgb'=>'E1E0F7'),
        ),
        'font' => array(
        'bold' => true,
        'strikethrough'=> true,
        'color' => array('rgb'=>'888888'),
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
        'color' => array('rgb'=>'FFFFFF'),
        ),
        'font' => array(
        'bold' => true,
        'strikethrough'=> true,
        'color' => array('rgb'=>'888888'),
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
        'color' => array('rgb'=>'C3C2D9'),
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
        'color' => array('rgb'=>'E1E0F7'),
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
        'color' => array('rgb'=>'FFFFFF'),
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

    $sheet->getStyle('A3:E3')->applyFromArray( $style_header_1 );
    $sheet->getStyle('A4:E4')->applyFromArray( $style_header );
    //STYLES ENDE

    $x = $i-1;
    // den namen vom Worksheet 1 definieren
    $xls->getActiveSheet()->setTitle("Tagesaufstellung ". strftime('%d.%.m.%y',$tag));
    $i = 5;
    $bestellung_has_speise_array = $bestellung_has_speiseVerwaltung->findeAnhandVonBestellungId($bestellung->getId());

    $gesamtmenge_tag_speise = 0;
    $gesamtmenge_tag_portionen = 0;
    $c = $i - 2;
    $d = $i - 1;
    $sheet->getStyle("A$d:E$d")->getFont()->setSize(15);
    $sheet->getStyle("A$c:E$c")->getFont()->setSize(15);



    $speise = $speiseVerwaltung->findeAnhandVonId($speise_id);
    $sheet->setCellValue("A$c",$speise->getBezeichnung());
    // den wert test in das Feld A1 schreiben
    $sheet->setCellValue("A$d","Kunde", $format_bold);
    $sheet->setCellValue("B$d","Port.");
    $sheet->setCellValue("C$d","pro P.");
    $sheet->setCellValue("D$d","Gesamt");
    $sheet->setCellValue("E$d","Bemerkung");
    // $sheet->setCellValue("E$d","Menge/P Standard");
    //$sheet->setCellValue("F$d","Faktor");
    foreach($kunden as $kunde) {
        if ($i == 35 || $i == 70 || $i == 105 || $i == 140 || $i == 175 || $i == 210) {
            $sheet->setCellValue("A$i",$speise->getBezeichnung());
            $sheet->getStyle("A$i:E$i")->applyFromArray( $style_header_1 );
            $i++;
        }
        if ($i %2) {
            $sheet->getStyle("A$i")->applyFromArray( $style_col1 );
            $sheet->getStyle("B$i")->applyFromArray( $style_row );
            $sheet->getStyle("C$i")->applyFromArray( $style_row );
            $sheet->getStyle("D$i")->applyFromArray( $style_row );
            $sheet->getStyle("E$i")->applyFromArray( $style_row );
        } else {
            $sheet->getStyle("A$i")->applyFromArray( $style_col1_2 );
            $sheet->getStyle("B$i")->applyFromArray( $style_row_2 );
            $sheet->getStyle("C$i")->applyFromArray( $style_row_2 );
            $sheet->getStyle("D$i")->applyFromArray( $style_row_2 );
            $sheet->getStyle("E$i")->applyFromArray( $style_row_2 );
        }

        $sheet->getStyle("A$i:E$i")->getFont()->setSize(15);
        $sheet->getStyle("C$i")->getFont()->setSize(8);
        $sheet->getStyle("B$i")->getFont()->setSize(8);
        $sheet->getStyle("E$i")->getFont()->setSize(12);
        // $sheet->getStyle("E$i")->applyFromArray( $style_row );
        //$sheet->getStyle("F$i")->applyFromArray( $style_row );
        $speise_id = $speise->getId();
        $indi_faktor = $indifaktorVerwaltung->findeAnhandVonSpeiseIdUndKundeId($speise_id,$kunde->getId());
        $faktor = $indi_faktor->getFaktor();
        $einrichtungskategorie_id = $kunde->getEinrichtungskategorieId();
        $menge_pro_portion = $menge_pro_portionVerwaltung->findeAnhandVonSpeiseIdUndEinrichtungskategorieId($speise_id,$einrichtungskategorie_id);

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

        $wochentag_string = strftime('%a', mktime(12,0,0,$monat,$tag,$jahr));
        switch ($wochentag_string) {
            case 'Mo':
                $portionen = $portionen_mo;
                if ($portionen_mo != $standardportionen->getPortionenMo()) {
                    $portionen .= ' (' . $standardportionen->getPortionenMo().')';
                }
                break;
            case 'Di':
                $portionen = $portionen_di;
                if ($portionen_di != $standardportionen->getPortionenDi()) {
                    $portionen .= ' (' . $standardportionen->getPortionenDi().')';
                }
                break;
            case 'Mi':
                $portionen = $portionen_mi;
                if ($portionen_mi != $standardportionen->getPortionenMi()) {
                    $portionen .= ' (' . $standardportionen->getPortionenMi().')';
                }
                break;
            case 'Do':
                $portionen = $portionen_do;
                if ($portionen_do != $standardportionen->getPortionenDo()) {
                    $portionen .= ' (' . $standardportionen->getPortionenDo().')';
                }
                break;
            case 'Fr':
                $portionen = $portionen_fr;
                if ($portionen_fr != $standardportionen->getPortionenFr()) {
                    $portionen .= ' (' . $standardportionen->getPortionenFr().')';
                }
                break;
        }

        $gesamtmenge_tag_portionen += $portionen;
        $gesamtmenge_kunde = $portionen * ($menge_pro_portion->getMenge()*$faktor);

        if ($portionen == 0) {
            if ($i %2) {
                $sheet->getStyle("A$i")->applyFromArray( $style_row_durchgestr );
                $sheet->getStyle("B$i")->applyFromArray( $style_row_durchgestr );
                $sheet->getStyle("C$i")->applyFromArray( $style_row_durchgestr );
                $sheet->getStyle("D$i")->applyFromArray( $style_row_durchgestr );
                $sheet->getStyle("E$i")->applyFromArray( $style_row_durchgestr );
            } else {
                $sheet->getStyle("A$i")->applyFromArray( $style_row_durchgestr_2 );
                $sheet->getStyle("B$i")->applyFromArray( $style_row_durchgestr_2 );
                $sheet->getStyle("C$i")->applyFromArray( $style_row_durchgestr_2 );
                $sheet->getStyle("D$i")->applyFromArray( $style_row_durchgestr_2 );
                $sheet->getStyle("E$i")->applyFromArray( $style_row_durchgestr_2 );
            }
        }


        if ($kunde->getEinrichtungskategorieId() == 5) {
            $sheet->setCellValue("A$i",$kunde->getName());
            $sheet->getStyle("A$i:D$i")->getFill()->applyFromArray(
                array(
                'type'       => PHPExcel_Style_Fill::FILL_SOLID,
                'startcolor' => array('rgb' => 'cccccc'),
                'endcolor' => array('rgb' => 'cccccc')
                )
            );
        } else {
            $sheet->setCellValue("A$i",$kunde->getName());
            $sheet->setCellValue("B$i",$portionen);
            $bemerkungen_str = '';
            $sheet->setCellValue("C$i",$menge_pro_portion->getMenge()*$faktor. ' '. $menge_pro_portion->getEinheit());
            $sheet->setCellValue("D$i",$gesamtmenge_kunde .' '.$menge_pro_portion->getEinheit());
            $bemerkung_zu_tag = $bemerkungzutagVerwaltung->findeAnhandVonKundeIdUndDatumUndSpeiseId($kunde->getId(), $tag, $monat, $jahr, $speise->getId());
            $bemerkung_zu_speise = $bemerkungzuspeiseVerwaltung->findeAnhandVonKundeIdUndSpeiseId($kunde->getId(),$speise->getId());



            if($bemerkung_zu_speise->getBemerkung() != '') {
                $bemerkungen_str .= $bemerkung_zu_speise->getBemerkung().'; ';
            }
            if($bemerkung_zu_tag->getBemerkung() != '') {
                $bemerkungen_str .= $bemerkung_zu_tag->getBemerkung().'; ';
            }
            if($kunde->getBemerkung() != '') {
                $bemerkungen_str .= $kunde->getBemerkung().'; ';
            }

            $sheet->setCellValue("E$i",$bemerkungen_str);
        }
        if ($bemerkungen_str !== '') {

            $sheet->getStyle("E$i")->getFill()->applyFromArray(
                array(
                'type'       => PHPExcel_Style_Fill::FILL_SOLID,
                'startcolor' => array('rgb' => 'FFFF83'),
                'endcolor' => array('rgb' => 'FFFF83')
                )
            );
        }

        $sheet->getStyle("E$i")->getAlignment()->setWrapText(true);
        //  $sheet->setCellValue("E$i",$menge_pro_portion->getMenge() . ' ' . $menge_pro_portion->getEinheit() . ' /P');
        //  $sheet->setCellValue("F$i",$faktor);

        $i++;
        $gesamtmenge_kunde = $portionen * ($menge_pro_portion->getMenge()*$faktor);
        $gesamtmenge_tag_speise += $gesamtmenge_kunde;

    }
    $sheet->getStyle("C4")->getFont()->setSize(12);
    $sheet->getStyle("B4")->getFont()->setSize(12);



    $sheet->setCellValue("A$i",$speise->getBezeichnung());
    $sheet->getStyle("A$i:E$i")->applyFromArray( $style_header_1 );
    $i++;
    $sheet->getStyle("A$i:E$i")->getFont()->setSize(15);

    $sheet->setCellValue("A$i","Insgesamt", $format_bold);
    $sheet->setCellValue("B$i",$gesamtmenge_tag_portionen);
    $sheet->setCellValue("C$i","");
    $sheet->setCellValue("D$i",$gesamtmenge_tag_speise .' '.$menge_pro_portion->getEinheit());

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

    $sheet->setCellValue("D$ttt",$gesamtmenge_tag_speise_umger .' '.$einheit);
    $sheet->getStyle("A2:D2")->applyFromArray( $style_row_gesamt );
    $sheet->getStyle("A$i:D$i")->applyFromArray( $style_row_gesamt );

    if ($speise->getRezept() != '') {
        $i += 5;
        $sheet->setCellValue("A$i","REZEPT/BEMERK.:");
        $sheet->getStyle("A$i")->getFont()->setBold(true);
        $i++;
        $sheet->mergeCells("A$i:E$i");
        $sheet->setCellValue("A$i", $speise->getRezept());
    }

    $sheet->setCellValue("A1",strftime('%d.%m.%Y', mktime(12,0,0,$monat,$tag,$jahr)));
    $sheet->getStyle("A1:E1")->getFont()->setSize(14);
    $sheet->setCellValue("A2","Insgesamt", $format_bold);
    $sheet->setCellValue("B2",$gesamtmenge_tag_portionen);
    $sheet->setCellValue("C2","");
    $sheet->setCellValue("D2",$gesamtmenge_tag_speise .' '.$menge_pro_portion->getEinheit());

    $writer=PHPExcel_IOFactory::createWriter($xls,"Excel5");
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
    if ($rechner == 'HP14603320956') {
        $writer->save('export_tagesaufstellungen/'.$speise_bezeichnung.'_'. strftime('%d_%m_%y',$ts).".xls");
    } else {
        $writer->save('/is/htdocs/wp1055935_1F55KTNHIC/www/sbar/verwaltung/export_tagesaufstellungen/'.$speise_bezeichnung.'_'. strftime('%d_%m_%y',$ts).".xls");

    }

    if ($rechner == 'HP14603320956') {
        header('location:export_tagesaufstellungen/'.$speise_bezeichnung.'_'.  strftime('%d_%m_%y',$ts).".xls");
    } else {
        header('location:http://www.s-bar.net/verwaltung/export_tagesaufstellungen/'.$speise_bezeichnung.'_'.  strftime('%d_%m_%y',$ts).".xls");

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
            $wochenstartts = $tagts - 86400*2;
            break;
        case 'Do':
            $wochenstartts = $tagts - 86400*3;
            break;
        case 'Fr':
            $wochenstartts = $tagts - 86400*4;
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

    $tag_in_angezeigter_woche_ts = mktime(12,0,0,$monat,$tag,$jahr);

    $wochentag_string = strftime('%a', mktime(12,0,0,$monat,$tag,$jahr));
    switch ($wochentag_string) {
        case 'Sa':
            $start_tag_woche = date('d', $tag_in_angezeigter_woche_ts+ 86400*2);
            $start_monat_woche = date('m', $tag_in_angezeigter_woche_ts+ 86400*2);
            $start_jahr_woche = date('Y', $tag_in_angezeigter_woche_ts+ 86400*2);
            //$wochenstarttage['woche1'] = $wochenstarttag_ts = $heute + 86400*2;
            break;
        case 'So':
        //$wochenstarttage['woche1'] = $wochenstarttag_ts = $heute + 86400*1;
            $start_tag_woche = date('d', $tag_in_angezeigter_woche_ts+ 86400*1);
            $start_monat_woche = date('m', $tag_in_angezeigter_woche_ts+ 86400*1);
            $start_jahr_woche = date('Y', $tag_in_angezeigter_woche_ts+ 86400*1);
            break;
        case 'Mo':
        //$wochenstarttage['woche1'] = $wochenstarttag_ts = $heute + 86400*0;
            $start_tag_woche = date('d', $tag_in_angezeigter_woche_ts+ 86400*0);
            $start_monat_woche = date('m', $tag_in_angezeigter_woche_ts+ 86400*0);
            $start_jahr_woche = date('Y', $tag_in_angezeigter_woche_ts+ 86400*0);
            break;
        case 'Di':
        //$wochenstarttage['woche1'] = $wochenstarttag_ts = $heute - 86400*1;
            $start_tag_woche = date('d', $tag_in_angezeigter_woche_ts- 86400*1);
            $start_monat_woche = date('m', $tag_in_angezeigter_woche_ts- 86400*1);
            $start_jahr_woche = date('Y', $tag_in_angezeigter_woche_ts- 86400*1);
            break;
        case 'Mi':
        //$wochenstarttage['woche1'] = $wochenstarttag_ts = $heute - 86400*2;
            $start_tag_woche = date('d', $tag_in_angezeigter_woche_ts- 86400*2);
            $start_monat_woche = date('m', $tag_in_angezeigter_woche_ts- 86400*2);
            $start_jahr_woche = date('Y', $tag_in_angezeigter_woche_ts- 86400*2);
            break;
        case 'Do':
        //$wochenstarttage['woche1'] = $wochenstarttag_ts = $heute - 86400*3;
            $start_tag_woche = date('d', $tag_in_angezeigter_woche_ts- 86400*3);
            $start_monat_woche = date('m', $tag_in_angezeigter_woche_ts- 86400*3);
            $start_jahr_woche = date('Y', $tag_in_angezeigter_woche_ts- 86400*3);
            break;
        case 'Fr':
        //$wochenstarttage['woche1'] = $wochenstarttag_ts = $heute - 86400*4;
            $start_tag_woche = date('d', $tag_in_angezeigter_woche_ts- 86400*4);
            $start_monat_woche = date('m', $tag_in_angezeigter_woche_ts- 86400*4);
            $start_jahr_woche = date('Y', $tag_in_angezeigter_woche_ts- 86400*4);
            break;
    }
    $portionenaenderung = $portionenaenderungVerwaltung->findeAnhandVonKundenIdUndWochenstartdatum($kunde_id, $start_tag_woche, $start_monat_woche, $start_jahr_woche);

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
    $wochentag_string = strftime('%a', mktime(12,0,0,$monat, $tag, $jahr));

    $tag_in_angezeigter_woche_ts = mktime(12,0,0,$monat,$tag,$jahr);

    switch ($wochentag_string) {
        case 'Mo':
            $portionen = $standardportionen->getPortionenMo();
            $start_tag_woche = date('d', $tag_in_angezeigter_woche_ts+ 86400*0);
            $start_monat_woche = date('m', $tag_in_angezeigter_woche_ts+ 86400*0);
            $start_jahr_woche = date('Y', $tag_in_angezeigter_woche_ts+ 86400*0);
            $wochenstartts = $tagts;
            break;
        case 'Di':
            $portionen = $standardportionen->getPortionenDi();
            $start_tag_woche = date('d', $tag_in_angezeigter_woche_ts- 86400*1);
            $start_monat_woche = date('m', $tag_in_angezeigter_woche_ts- 86400*1);
            $start_jahr_woche = date('Y', $tag_in_angezeigter_woche_ts- 86400*1);
            $wochenstartts = $tagts - 86400;
            break;
        case 'Mi':
            $portionen = $standardportionen->getPortionenMi();
            $start_tag_woche = date('d', $tag_in_angezeigter_woche_ts- 86400*1);
            $start_monat_woche = date('m', $tag_in_angezeigter_woche_ts- 86400*1);
            $start_jahr_woche = date('Y', $tag_in_angezeigter_woche_ts- 86400*1);
            $wochenstartts = $tagts - 86400*2;
            break;
        case 'Do':
            $portionen = $standardportionen->getPortionenDo();
            $start_tag_woche = date('d', $tag_in_angezeigter_woche_ts- 86400*3);
            $start_monat_woche = date('m', $tag_in_angezeigter_woche_ts- 86400*3);
            $start_jahr_woche = date('Y', $tag_in_angezeigter_woche_ts- 86400*3);
            $wochenstartts = $tagts - 86400*3;
            break;
        case 'Fr':
            $portionen = $standardportionen->getPortionenFr();
            $start_tag_woche = date('d', $tag_in_angezeigter_woche_ts- 86400*4);
            $start_monat_woche = date('m', $tag_in_angezeigter_woche_ts- 86400*4);
            $start_jahr_woche = date('Y', $tag_in_angezeigter_woche_ts- 86400*4);
            $wochenstartts = $tagts - 86400*4;
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
            $menge_pro_portion_check = $menge_pro_portionVerwaltung->findeAnhandVonSpeiseIdUndEinrichtungskategorieId($speise->getId(),$einrichtung->getId());
            if ($menge_pro_portion_check->getMenge() == 0) {
                $fehler_in[] = array($speise->getId(),$einrichtung->getId());
            }
        }
        $z++;
    }
    return $fehler_in;
}


function erzeugeAbrechnungExcel($monat, $jahr, $kunde_id, $kundeVerwaltung, $abrechnungstagVerwaltung, $speiseVerwaltung) {
    require_once('PHPExcel-1.7.5/PHPExcel.php');
    require_once('PHPExcel-1.7.5/PHPExcel/IOFactory.php');

    $kunde = $kundeVerwaltung->findeAnhandVonId($kunde_id);
    $abrechnungstage = $abrechnungstagVerwaltung->findeAlleZuMonatUndKunde($monat,$jahr,$kunde_id);

    // neue instanz erstellen
    $xls=new PHPExcel();
    // dokumenteigenschaften schreiben
    $xls->getProperties()->setCreator("S-Bar")
        ->setLastModifiedBy("S-Bar")
        ->setTitle("Abrechnung $monat/$jahr")
        ->setSubject("Abrechnung $monat/$jahr");
    // das erste worksheet anwaehlen
    $sheet=$xls->setActiveSheetIndex(0);

    //STYLES START
    $sheet->getStyle('A1')->getFont()->setBold(true);
    $sheet->getStyle('A2:D2')->getFont()->setBold(true);
    $sheet->getColumnDimension('A')->setWidth(30);
    $sheet->getColumnDimension('B')->setWidth(15);
    $sheet->getColumnDimension('C')->setWidth(15);
    $sheet->getColumnDimension('D')->setWidth(15);
    $sheet->getColumnDimension('A')->setAutoSize(false);


    $default_border = array(
        'style' => PHPExcel_Style_Border::BORDER_THIN,
        'color' => array('rgb'=>'BEBEBE')
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
        'color' => array('rgb'=>'FF0000'),
        ),
        'font' => array(
        'bold' => true,
        'color' => array('rgb'=>'ffffff'),
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
        'type' => PHPExcel_Style_Fill::FILL_SOLID,
        'color' => array('rgb'=>'429E3A'),
        ),
        'font' => array(
        'bold' => true,
        'color' => array('rgb'=>'ffffff'),
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
        'type' => PHPExcel_Style_Fill::FILL_SOLID,
        'color' => array('rgb'=>'E1E0F7'),
        ),
        'font' => array(
        'bold' => false,
        'color' => array('rgb'=>'000000'),
        ),
        'alignment' => array(
        'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_LEFT,
        'vertical' => PHPExcel_Style_Alignment::VERTICAL_TOP,
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
        'color' => array('rgb'=>'C3C2D9'),
        ),
        'font' => array(
        'bold' => true,
        ),
        'alignment' => array(
        'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER,
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
        'type' => PHPExcel_Style_Fill::FILL_SOLID,
        'color' => array('rgb'=>'E1E0F7'),
        ),
        'font' => array(
        'bold' => true,
        'strikethrough'=> true,
        'color' => array('rgb'=>'888888'),
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
        'type' => PHPExcel_Style_Fill::FILL_SOLID,
        'color' => array('rgb'=>'E1E0F7'),
        ),
        'font' => array(
        'bold' => true,
        'color' => array('rgb'=>'000000'),
        ),
        'alignment' => array(
        'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_LEFT,
        'vertical' => PHPExcel_Style_Alignment::VERTICAL_TOP,
        'indent' => 0.5,
        )
    );

    $sheet->getStyle('A1:C1')->applyFromArray( $style_header_1 );
    $sheet->getStyle('A2:C2')->applyFromArray( $style_header );

    //STYLES ENDE

    // den namen vom Worksheet 1 definieren
    $xls->getActiveSheet()->setTitle("Abrechnung ". strftime('%d.%.m.%y',$von));
    $von_tag = strftime('%d', $von);
    $bis_tag = strftime('%d', $bis);

    $sheet->setCellValue("A1",$kunde->getKundennummer());
    $sheet->setCellValue("B1",$kunde->getName() . ' ' . strftime('%m/%Y',$von));
    $sheet->setCellValue("C1",'Lex.: '.$kunde->getLexware());

    $t = 2;
    $sheet->setCellValue("A$t",'Tag');
    $sheet->setCellValue("B$t","Speisen");
    $sheet->setCellValue("C$t","Portionen");


    $portionen_monat_gesamt = 0;

    foreach ($abrechnungstage as $abrechnungstag) {
        if ($abrechnungstag->getPortionen() > 0) {


            $c = $t+1;
            $sheet->setCellValue("A$c", strftime('%a %d.',mktime(12,0,0,$abrechnungstag->getMonat(),$abrechnungstag->getTag2(),$abrechnungstag->getJahr())));
            $speisen_ids = $abrechnungstag->getSpeisenIds();
            $speisen_ids = explode(', ', $speisen_ids);
            $speisen_array = array();
            foreach ($speisen_ids as $speise_id) {
                $speise = $speiseVerwaltung->findeAnhandVonId($speise_id);
                $speisen_array[] = $speise->getBezeichnung();
            }
            $sheet->setCellValue("B$c", implode(', ', $speisen_array));
            $sheet->setCellValue("C$c",$abrechnungstag->getPortionen());
            $portionen_monat_gesamt += $abrechnungstag->getPortionen();

            $sheet->getStyle("A$c")->applyFromArray( $style_col1 );
            $sheet->getStyle("B$c")->applyFromArray( $style_row );
            $sheet->getStyle("C$c")->applyFromArray( $style_row );
            $sheet->getStyle("B$t")->getAlignment()->setWrapText(true);
            $t++;
    }}
    $c++;
    $sheet->setCellValue("A$c", 'Gesamt');
    $sheet->setCellValue("C$c", $portionen_monat_gesamt);
    $sheet->getStyle("A$c:C$c")->applyFromArray( $style_row_gesamt );
    // $sheet->setCellValue("E$d","Menge/P Standard");
    //$sheet->setCellValue("F$d","Faktor");


    //$xls->getActiveSheet()->getColumnDimension('A')->setAutoSize(true);
    //$xls->getActiveSheet()->getColumnDimension('B')->setAutoSize(true);
    //$xls->getActiveSheet()->getColumnDimension('C')->setAutoSize(true);


    $sheet->getColumnDimension('A')->setWidth(10);
    $sheet->getColumnDimension('B')->setWidth(70);
    $sheet->getColumnDimension('C')->setWidth(10);
    $writer=PHPExcel_IOFactory::createWriter($xls,"Excel5");

    $heute = strftime('%d_%m_%Y', time());
    $rechner = php_uname('n');
    if ($rechner == 'HP14603320956') {
        $writer->save('export_abrechnungen/Abrg_'.$kunde->getKundennummer().'_'. $monat.'_'.$jahr.".xls");
    } else {
        $writer->save('/is/htdocs/wp1055935_1F55KTNHIC/www/sbar/verwaltung/export_abrechnungen/Abrg_'.$kunde->getKundennummer().'_'. $monat.'_'.$jahr.".xls");

    }




    if ($rechner == 'HP14603320956') {
        header('location:export_abrechnungen/Abrg_'.$kunde->getKundennummer().'_'. $monat.'_'.$jahr.".xls");
    } else {
        header('location:http://www.s-bar.net/verwaltung/export_abrechnungen/Abrg_'.$kunde->getKundennummer().'_'. $monat.'_'.$jahr.".xls");

    }


}


function erzeugeTagesmengenUebersichtExcel($gesamtmengen_array, $tag, $monat, $jahr) {
    require_once('PHPExcel-1.7.5/PHPExcel.php');
    require_once('PHPExcel-1.7.5/PHPExcel/IOFactory.php');

    // neue instanz erstellen
    $xls=new PHPExcel();
    // dokumenteigenschaften schreiben
    $xls->getProperties()->setCreator("S-Bar")
        ->setLastModifiedBy("S-Bar")
        ->setTitle("Tagesmengen ". strftime('%d.%.m.%y',mktime(12,0,0,$monat,$tag,$jahr)))
        ->setSubject("Tagesmengen ". strftime('%d.%.m.%y',mktime(12,0,0,$monat,$tag,$jahr)));
    // das erste worksheet anwaehlen
    $sheet=$xls->setActiveSheetIndex(0);

    //STYLES START
    $sheet->getStyle('A1')->getFont()->setBold(true);
    $sheet->getStyle('A2:D2')->getFont()->setBold(true);
    $sheet->getColumnDimension('A')->setWidth(30);
    $sheet->getColumnDimension('B')->setWidth(15);
    $sheet->getColumnDimension('C')->setWidth(15);
    $sheet->getColumnDimension('D')->setWidth(15);
    $sheet->getColumnDimension('A')->setAutoSize(false);

    $sheet->setCellValue("A1",strftime('%d.%m.%y',mktime(12,0,0,$monat,$tag,$jahr)));
    // den wert test in das Feld A1 schreiben
    $sheet->setCellValue("A2","Speise", $format_bold);
    $sheet->setCellValue("B2","Gesamt");
    $sheet->setCellValue("C2","Gesamt 2");

    $i = 3;

    foreach($gesamtmengen_array as $satz) {
        $sheet->setCellValue("A$i",$satz[0], $format_bold);
        $sheet->setCellValue("B$i",str_replace(".",",",$satz[1]).' '.$satz[2]);
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

        $menge_umg = str_replace(".",",",$menge_umg);
        $sheet->setCellValue("C$i",$menge_umg . ' ' . $einheit);
        $i++;
    }

    $heute = strftime('%d_%m_%Y', time());
    $rechner = php_uname('n');

    $writer=PHPExcel_IOFactory::createWriter($xls,"Excel5");

    if ($rechner == 'HP14603320956') {
        $writer->save('export_tagesmengen/Mengen_'. strftime('%d_%m_%y',mktime(12,0,0,$monat,$tag,$jahr)).".xls");
    } else {
        $writer->save('/is/htdocs/wp1055935_1F55KTNHIC/www/sbar/verwaltung/export_tagesmengen/Mengen_'. strftime('%d_%m_%y',mktime(12,0,0,$monat,$tag,$jahr)).".xls");

    }

    if ($rechner == 'HP14603320956') {
        header('location:export_tagesmengen/Mengen_'.  strftime('%d_%m_%y',mktime(12,0,0,$monat,$tag,$jahr)).".xls");
    } else {
        header('location:http://www.s-bar.net/verwaltung/export_tagesmengen/Mengen_'.  strftime('%d_%m_%y',mktime(12,0,0,$monat,$tag,$jahr)).".xls");

    }
}


function erzeugeTagesaufstellungExcelEtikettendruck($tag, $monat, $jahr, $starttag, $startmonat, $startjahr, $speise_id, $bestellung, $bestellung_has_speiseVerwaltung, $speiseVerwaltung,$menge_pro_portionVerwaltung,$standardportionenVerwaltung,$portionenaenderungVerwaltung, $kunden, $indifaktorVerwaltung, $bemerkungzutagVerwaltung, $bemerkungzuspeiseVerwaltung) {
    require_once('PHPExcel-1.7.5/PHPExcel.php');
    require_once('PHPExcel-1.7.5/PHPExcel/IOFactory.php');

    $ts = mktime(12,0,0,$monat,$tag,$jahr);
    // neue instanz erstellen
    $xls=new PHPExcel();
    // dokumenteigenschaften schreiben
    $xls->getProperties()->setCreator("S-Bar")
        ->setLastModifiedBy("S-Bar")
        ->setTitle("Tagesaufstellung ". strftime('%d.%.m.%y',mktime(12,0,0,$monat,$tag,$jahr)))
        ->setSubject("Tagesaufstellung ". strftime('%d.%m.%y',mktime(12,0,0,$monat,$tag,$jahr)));
    // das erste worksheet anwaehlen
    $sheet=$xls->setActiveSheetIndex(0);

    //STYLES START
    //$sheet->getStyle('A1')->getFont()->setBold(true);
    // $sheet->getStyle('A2:D2')->getFont()->setBold(true);
    $sheet->getColumnDimension('A')->setWidth(30);
    $sheet->getColumnDimension('B')->setWidth(15);
    $sheet->getColumnDimension('C')->setWidth(15);
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

    $x = $i-1;
    // den namen vom Worksheet 1 definieren
    $xls->getActiveSheet()->setTitle("Tagesaufstellung ". strftime('%d.%m.%y',mktime(12,0,0,$monat,$tag,$jahr)));
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

    foreach($kunden as $kunde) {

    /*
if ($i == 35 || $i == 70 || $i == 105 || $i == 140 || $i == 175 || $i == 210) {
            $sheet->setCellValue("A$i",$speise->getBezeichnung());
            $sheet->getStyle("A$i:E$i")->applyFromArray( $style_header_1 );
            $i++;
        }*/
        if ($i %2) {
            $sheet->getStyle("A$i")->applyFromArray( $style_col1 );
            $sheet->getStyle("B$i")->applyFromArray( $style_row );
            $sheet->getStyle("C$i")->applyFromArray( $style_row );
            $sheet->getStyle("D$i")->applyFromArray( $style_row );
            $sheet->getStyle("E$i")->applyFromArray( $style_row );
        } else {
            $sheet->getStyle("A$i")->applyFromArray( $style_col1_2 );
            $sheet->getStyle("B$i")->applyFromArray( $style_row_2 );
            $sheet->getStyle("C$i")->applyFromArray( $style_row_2 );
            $sheet->getStyle("D$i")->applyFromArray( $style_row_2 );
            $sheet->getStyle("E$i")->applyFromArray( $style_row_2 );
        }

        /// $sheet->getStyle("A$i:E$i")->getFont()->setSize(15);
        //  $sheet->getStyle("C$i")->getFont()->setSize(8);
        //  $sheet->getStyle("B$i")->getFont()->setSize(8);
        //  $sheet->getStyle("E$i")->getFont()->setSize(12);
        // $sheet->getStyle("E$i")->applyFromArray( $style_row );
        //$sheet->getStyle("F$i")->applyFromArray( $style_row );
        $speise_id = $speise->getId();
        $indi_faktor = $indifaktorVerwaltung->findeAnhandVonSpeiseIdUndKundeId($speise_id,$kunde->getId());
        $faktor = $indi_faktor->getFaktor();
        $einrichtungskategorie_id = $kunde->getEinrichtungskategorieId();
        $menge_pro_portion = $menge_pro_portionVerwaltung->findeAnhandVonSpeiseIdUndEinrichtungskategorieId($speise_id,$einrichtungskategorie_id);

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

        $wochentag_string = strftime('%a', mktime(12,0,0,$monat,$tag,$jahr));
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
        $gesamtmenge_kunde = $portionen * ($menge_pro_portion->getMenge()*$faktor);

        if ($portionen == 0) {
            if ($i %2) {
                $sheet->getStyle("A$i")->applyFromArray( $style_row_durchgestr );
                $sheet->getStyle("B$i")->applyFromArray( $style_row_durchgestr );
                $sheet->getStyle("C$i")->applyFromArray( $style_row_durchgestr );
                $sheet->getStyle("D$i")->applyFromArray( $style_row_durchgestr );
                $sheet->getStyle("E$i")->applyFromArray( $style_row_durchgestr );
            } else {
                $sheet->getStyle("A$i")->applyFromArray( $style_row_durchgestr_2 );
                $sheet->getStyle("B$i")->applyFromArray( $style_row_durchgestr_2 );
                $sheet->getStyle("C$i")->applyFromArray( $style_row_durchgestr_2 );
                $sheet->getStyle("D$i")->applyFromArray( $style_row_durchgestr_2 );
                $sheet->getStyle("E$i")->applyFromArray( $style_row_durchgestr_2 );
            }
        }


        if ($kunde->getEinrichtungskategorieId() == 5) {
            /* $sheet->setCellValue("A$i",$kunde->getName());
             $sheet->getStyle("A$i:D$i")->getFill()->applyFromArray(
                array(
                'type'       => PHPExcel_Style_Fill::FILL_SOLID,
                'startcolor' => array('rgb' => 'cccccc'),
                'endcolor' => array('rgb' => 'cccccc')
                )
            );*/

        } else {



            if ($portionen > 0) {
                $sheet->setCellValue("A$i",$kunde->getName());
                // $sheet->setCellValue("B$i",$portionen);
                $bemerkungen_str = '';
                //$sheet->setCellValue("C$i",$menge_pro_portion->getMenge()*$faktor. ' '. $menge_pro_portion->getEinheit());
                $sheet->setCellValue("B$i",$gesamtmenge_kunde /*.' '.$menge_pro_portion->getEinheit()*/);
                $bemerkung_zu_tag = $bemerkungzutagVerwaltung->findeAnhandVonKundeIdUndDatumUndSpeiseId($kunde->getId(), $tag, $monat, $jahr, $speise->getId());
                $bemerkung_zu_speise = $bemerkungzuspeiseVerwaltung->findeAnhandVonKundeIdUndSpeiseId($kunde->getId(),$speise->getId());




                if($bemerkung_zu_speise->getBemerkung() != '') {
                    $bemerkungen_str .= $bemerkung_zu_speise->getBemerkung().'; ';
                }
                if($bemerkung_zu_tag->getBemerkung() != '') {
                    $bemerkungen_str .= $bemerkung_zu_tag->getBemerkung().'; ';
                }
                if($kunde->getBemerkung() != '') {
                // $bemerkungen_str .= $kunde->getBemerkung().'; ';
                }

                $sheet->setCellValue("C$i",$bemerkungen_str);
                $sheet->setCellValue("D$i",$speise->getBezeichnung());
                $sheet->setCellValue("E$i", strftime('%d.%m.%y',mktime(12,0,0,$monat,$tag,$jahr)));

        }}


        $sheet->getStyle("E$i")->getAlignment()->setWrapText(true);
        //  $sheet->setCellValue("E$i",$menge_pro_portion->getMenge() . ' ' . $menge_pro_portion->getEinheit() . ' /P');
        //  $sheet->setCellValue("F$i",$faktor);
        if ($portionen > 0) {
            $i++;
        }
        $gesamtmenge_kunde = $portionen * ($menge_pro_portion->getMenge()*$faktor);
        $gesamtmenge_tag_speise += $gesamtmenge_kunde;

    }

    $sheet->setCellValue("A$i",'Gesamt');
    $sheet->setCellValue("B$i",$gesamtmenge_tag_speise);
    $sheet->setCellValue("C$i",'');
    $sheet->setCellValue("D$i",$speise->getBezeichnung());
    $sheet->setCellValue("E$i", strftime('%d.%m.%y',$tag+$winterzeit));

    $sheet->getStyle("C4")->getFont()->setSize(12);
    $sheet->getStyle("B4")->getFont()->setSize(12);



    //$sheet->setCellValue("A$i",$speise->getBezeichnung());
    $sheet->getStyle("A$i:E$i")->applyFromArray( $style_header_1 );
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

    $writer=PHPExcel_IOFactory::createWriter($xls,"Excel5");
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

    if ($rechner == 'HP14603320956') {
        $writer->save('export_etiketten/Eti_'.$speise_bezeichnung.'_'. strftime('%d_%m_%y',mktime(12,0,0,$moant,$tag,$jahr)).".xls");
    } else {
        $writer->save('/is/htdocs/wp1055935_1F55KTNHIC/www/sbar/verwaltung/export_etiketten/Eti_'.$speise_bezeichnung.'_'. strftime('%d_%m_%y',mktime(12,0,0,$moant,$tag,$jahr)).".xls");

    }

    if ($rechner == 'HP14603320956') {
        header('location:export_etiketten/Eti_'.$speise_bezeichnung.'_'.  strftime('%d_%m_%y',mktime(12,0,0,$moant,$tag,$jahr)).".xls");
    } else {
        header('location:http://www.s-bar.net/verwaltung/export_etiketten/Eti_'.$speise_bezeichnung.'_'.  strftime('%d_%m_%y',mktime(12,0,0,$moant,$tag,$jahr)).".xls");

    }

}



function erzeugeEinrichtungsliste($tag, $monat, $jahr, $starttag, $startmonat, $startjahr, $speise_id, $bestellung, $bestellung_has_speiseVerwaltung, $speiseVerwaltung,$menge_pro_portionVerwaltung,$standardportionenVerwaltung,$portionenaenderungVerwaltung, $kunden, $indifaktorVerwaltung, $bemerkungzutagVerwaltung, $bemerkungzuspeiseVerwaltung) {
    require_once('PHPExcel-1.7.5/PHPExcel.php');
    require_once('PHPExcel-1.7.5/PHPExcel/IOFactory.php');

    $ts = mktime(12,0,0,$monat,$tag,$jahr);
    // neue instanz erstellen
    $xls=new PHPExcel();
    // dokumenteigenschaften schreiben
    $xls->getProperties()->setCreator("S-Bar")
        ->setLastModifiedBy("S-Bar")
        ->setTitle("Tagesaufstellung ". strftime('%d.%.m.%y',$ts))
        ->setSubject("Tagesaufstellung ". strftime('%d.%m.%y',$ts));
    // das erste worksheet anwaehlen
    $sheet=$xls->setActiveSheetIndex(0);

    //STYLES START
    //$sheet->getStyle('A1')->getFont()->setBold(true);
    // $sheet->getStyle('A2:D2')->getFont()->setBold(true);
    $sheet->getColumnDimension('A')->setWidth(30);
    $sheet->getColumnDimension('B')->setWidth(15);
    $sheet->getColumnDimension('C')->setWidth(15);
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

    $x = $i-1;
    // den namen vom Worksheet 1 definieren
    $xls->getActiveSheet()->setTitle("Einrichtungsliste ". strftime('%d.%m.%y',$ts));
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
    foreach($kunden as $kunde) {

    /*
if ($i == 35 || $i == 70 || $i == 105 || $i == 140 || $i == 175 || $i == 210) {
            $sheet->setCellValue("A$i",$speise->getBezeichnung());
            $sheet->getStyle("A$i:E$i")->applyFromArray( $style_header_1 );
            $i++;
        }*/
        if ($i %2) {
            $sheet->getStyle("A$i")->applyFromArray( $style_col1 );
            $sheet->getStyle("B$i")->applyFromArray( $style_row );
            $sheet->getStyle("C$i")->applyFromArray( $style_row );
            $sheet->getStyle("D$i")->applyFromArray( $style_row );
            $sheet->getStyle("E$i")->applyFromArray( $style_row );
        } else {
            $sheet->getStyle("A$i")->applyFromArray( $style_col1_2 );
            $sheet->getStyle("B$i")->applyFromArray( $style_row_2 );
            $sheet->getStyle("C$i")->applyFromArray( $style_row_2 );
            $sheet->getStyle("D$i")->applyFromArray( $style_row_2 );
            $sheet->getStyle("E$i")->applyFromArray( $style_row_2 );
        }

        /// $sheet->getStyle("A$i:E$i")->getFont()->setSize(15);
        //  $sheet->getStyle("C$i")->getFont()->setSize(8);
        //  $sheet->getStyle("B$i")->getFont()->setSize(8);
        //  $sheet->getStyle("E$i")->getFont()->setSize(12);
        // $sheet->getStyle("E$i")->applyFromArray( $style_row );
        //$sheet->getStyle("F$i")->applyFromArray( $style_row );
        $speise_id = $speise->getId();
        $indi_faktor = $indifaktorVerwaltung->findeAnhandVonSpeiseIdUndKundeId($speise_id,$kunde->getId());
        $faktor = $indi_faktor->getFaktor();
        $einrichtungskategorie_id = $kunde->getEinrichtungskategorieId();
        $menge_pro_portion = $menge_pro_portionVerwaltung->findeAnhandVonSpeiseIdUndEinrichtungskategorieId($speise_id,$einrichtungskategorie_id);

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
        $gesamtmenge_kunde = $portionen * ($menge_pro_portion->getMenge()*$faktor);

        if ($portionen == 0) {
            if ($i %2) {
                $sheet->getStyle("A$i")->applyFromArray( $style_row_durchgestr );
                $sheet->getStyle("B$i")->applyFromArray( $style_row_durchgestr );
                $sheet->getStyle("C$i")->applyFromArray( $style_row_durchgestr );
                $sheet->getStyle("D$i")->applyFromArray( $style_row_durchgestr );
                $sheet->getStyle("E$i")->applyFromArray( $style_row_durchgestr );
            } else {
                $sheet->getStyle("A$i")->applyFromArray( $style_row_durchgestr_2 );
                $sheet->getStyle("B$i")->applyFromArray( $style_row_durchgestr_2 );
                $sheet->getStyle("C$i")->applyFromArray( $style_row_durchgestr_2 );
                $sheet->getStyle("D$i")->applyFromArray( $style_row_durchgestr_2 );
                $sheet->getStyle("E$i")->applyFromArray( $style_row_durchgestr_2 );
            }
        }


        if ($kunde->getEinrichtungskategorieId() == 5) {
            /* $sheet->setCellValue("A$i",$kunde->getName());
             $sheet->getStyle("A$i:D$i")->getFill()->applyFromArray(
                array(
                'type'       => PHPExcel_Style_Fill::FILL_SOLID,
                'startcolor' => array('rgb' => 'cccccc'),
                'endcolor' => array('rgb' => 'cccccc')
                )
            );*/

        } else {




            $sheet->setCellValue("A$i",$kunde->getName());
            //  $sheet->setCellValue("B$i",$portionen);
            $bemerkungen_str = '';
            //  $sheet->setCellValue("C$i",$menge_pro_portion->getMenge()*$faktor. ' '. $menge_pro_portion->getEinheit());
            //   $sheet->setCellValue("D$i",$gesamtmenge_kunde /*.' '.$menge_pro_portion->getEinheit()*/);
            $bemerkung_zu_tag = $bemerkungzutagVerwaltung->findeAnhandVonKundeIdUndDatumUndSpeiseId($kunde->getId(), $tag, $monat, $jahr, $speise->getId());
            $bemerkung_zu_speise = $bemerkungzuspeiseVerwaltung->findeAnhandVonKundeIdUndSpeiseId($kunde->getId(),$speise->getId());




            if($bemerkung_zu_speise->getBemerkung() != '') {
                $bemerkungen_str .= $bemerkung_zu_speise->getBemerkung().'; ';
            }
            if($bemerkung_zu_tag->getBemerkung() != '') {
                $bemerkungen_str .= $bemerkung_zu_tag->getBemerkung().'; ';
            }
            if($kunde->getBemerkung() != '') {
            // $bemerkungen_str .= $kunde->getBemerkung().'; ';
            }

        //  $sheet->setCellValue("E$i",$bemerkungen_str);
        //  $sheet->setCellValue("F$i",$speise->getBezeichnung());
        // $sheet->setCellValue("G$i", strftime('%d.%m.%y',$tag));

        }


        $sheet->getStyle("E$i")->getAlignment()->setWrapText(true);
        //  $sheet->setCellValue("E$i",$menge_pro_portion->getMenge() . ' ' . $menge_pro_portion->getEinheit() . ' /P');
        //  $sheet->setCellValue("F$i",$faktor);
        if ($kunde->getEinrichtungskategorieId() != 5) {
            $i++;
        }
        $gesamtmenge_kunde = $portionen * ($menge_pro_portion->getMenge()*$faktor);
        $gesamtmenge_tag_speise += $gesamtmenge_kunde;

    }
    $sheet->getStyle("C4")->getFont()->setSize(12);
    $sheet->getStyle("B4")->getFont()->setSize(12);



    //$sheet->setCellValue("A$i",$speise->getBezeichnung());
    $sheet->getStyle("A$i:E$i")->applyFromArray( $style_header_1 );
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
    }*/

    // $sheet->setCellValue("A1",strftime('%d.%m.%Y', $tag));
    // $sheet->getStyle("A1:E1")->getFont()->setSize(14);
    // $sheet->setCellValue("A2","Insgesamt", $format_bold);
    // $sheet->setCellValue("B2",$gesamtmenge_tag_portionen);
    // $sheet->setCellValue("C2","");
    // $sheet->setCellValue("D2",$gesamtmenge_tag_speise .' '.$menge_pro_portion->getEinheit());

    $writer=PHPExcel_IOFactory::createWriter($xls,"Excel5");
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

    if ($rechner == 'HP14603320956') {
        $writer->save('export_einrichtungslisten/Einrichtungen_'. strftime('%d_%m_%y',$ts).".xls");
    } else {
        $writer->save('/is/htdocs/wp1055935_1F55KTNHIC/www/sbar/verwaltung/export_einrichtungslisten/Einrichtungen_'. strftime('%d_%m_%y',$ts).".xls");

    }

    if ($rechner == 'HP14603320956') {
        header('location:export_einrichtungslisten/Einrichtungen_'.  strftime('%d_%m_%y',$ts).".xls");
    } else {
        header('location:http://www.s-bar.net/verwaltung/export_einrichtungslisten/Einrichtungen_'.  strftime('%d_%m_%y',$ts).".xls");

    }

}


function aktualisiereDatenMitTagMonatJahr($abrechnungstagVerwaltung, $bestellungVerwaltung, $portionenaenderungVerwaltung, $bemerkungzutagVerwaltung) {
  /*$abrechnungstage = $abrechnungstagVerwaltung->findeAlle();
    $c = 0;
    foreach ($abrechnungstage as $abrechnungstag) {
        if ($abrechnungstag->getTag2() == NULL && $abrechnungstag->getMonat() == NULL) {
           $abrechnungstag->setTag2(date('d',$abrechnungstag->getTag()+3600));
            $abrechnungstag->setMonat(date('m',$abrechnungstag->getTag()+3600));
            $abrechnungstag->setJahr(date('Y',$abrechnungstag->getTag()+3600));
            $abrechnungstagVerwaltung->speichere($abrechnungstag);
        }
        $c++;
        
    }*/
    $bestellungen = $bestellungVerwaltung->findeAlle();
    $c = 0;
    foreach ($bestellungen as $bestellung) {
        if ($bestellung->getTag2() == NULL && $bestellung->getMonat() == NULL) {
            $bestellung->setTag2(date('d',$bestellung->getTag()+3600));
            $bestellung->setMonat(date('m',$bestellung->getTag()+3600));
            $bestellung->setJahr(date('Y',$bestellung->getTag()+3600));
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
    }*/
}

function erzeugeWochenmengenUebersichtExcel($mengen_woche, $starttag, $startmonat, $startjahr) {
    require_once('PHPExcel-1.7.5/PHPExcel.php');
    require_once('PHPExcel-1.7.5/PHPExcel/IOFactory.php');

    // neue instanz erstellen
    $xls=new PHPExcel();
    // dokumenteigenschaften schreiben
    $xls->getProperties()->setCreator("S-Bar")
        ->setLastModifiedBy("S-Bar")
        ->setTitle("Tagesmengen ". strftime('%d.%.m.%y',mktime(12,0,0,$startmonat,$starttag,$startjahr)))
        ->setSubject("Tagesmengen ". strftime('%d.%.m.%y',mktime(12,0,0,$startmonat,$starttag,$startjahr)));
    // das erste worksheet anwaehlen
    $sheet=$xls->setActiveSheetIndex(0);

    //STYLES START
    $sheet->getStyle('A1')->getFont()->setBold(true);
    $sheet->getStyle('A2:D2')->getFont()->setBold(true);
    $sheet->getColumnDimension('A')->setWidth(30);
    $sheet->getColumnDimension('B')->setWidth(15);
    $sheet->getColumnDimension('C')->setWidth(15);
    $sheet->getColumnDimension('D')->setWidth(15);
    $sheet->getColumnDimension('A')->setAutoSize(false);

    $i = 1;

    foreach($mengen_woche as $menge_tag) {
        var_dump($menge_tag[0][0][0]);
        $sheet->setCellValue("A$i",$menge_tag[0][0]['Datum']);
        $i++;
        // den wert test in das Feld A1 schreiben
        $sheet->setCellValue("A$i","Speise", $format_bold);
        $sheet->setCellValue("B$i","Gesamt");
        $sheet->setCellValue("C$i","umgerechnet");
        $i++;
        foreach($menge_tag as $speise) {
            $sheet->setCellValue("A$i",$speise['Speise'], $format_bold);
            $sheet->setCellValue("B$i",$speise['Menge']);
            $sheet->setCellValue("C$i",$speise['Umgerechnet']);
            $i++;
        }
            $i++;
    }

    $heute = strftime('%d_%m_%Y', time());
    $rechner = php_uname('n');

    $writer=PHPExcel_IOFactory::createWriter($xls,"Excel5");

    if ($rechner == 'HP14603320956') {
        $writer->save('export_wochenmengen/Mengen_'. strftime('%d_%m_%y',mktime(12,0,0,$startmonat,$starttag,$startjahr)).".xls");
    } else {
        $writer->save('/is/htdocs/wp1055935_1F55KTNHIC/www/sbar/verwaltung/export_wochenmengen/Mengen_'. strftime('%d_%m_%y',mktime(12,0,0,$startmonat,$starttag,$startjahr)).".xls");

    }

    if ($rechner == 'HP14603320956') {
        header('location:export_wochenmengen/Mengen_'.  strftime('%d_%m_%y',mktime(12,0,0,$startmonat,$starttag,$startjahr)).".xls");
    } else {
        header('location:http://www.s-bar.net/verwaltung/export_wochenmengen/Mengen_'.  strftime('%d_%m_%y',mktime(12,0,0,$startmonat,$starttag,$startjahr)).".xls");

    }
}
?>