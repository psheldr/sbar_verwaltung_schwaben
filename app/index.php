<?php
require_once 'Dotenv.php';

session_start();
error_reporting(E_ERROR | E_WARNING | E_PARSE);
error_reporting(0);

ini_set("display_errors", 1);
    error_reporting(E_ALL & ~E_NOTICE & ~E_WARNING);

require_once './model/bestellung.php';
require_once './model/bestellung_verwaltung.php';
require_once './model/einrichtungskategorie.php';
require_once './model/einrichtungskategorie_verwaltung.php';
require_once './model/kunde.php';
require_once './model/kunde_verwaltung.php';
require_once './model/menge_pro_portion.php';
require_once './model/menge_pro_portion_verwaltung.php';
require_once './model/speise.php';
require_once './model/speise_verwaltung.php';
require_once './model/standardportionen.php';
require_once './model/standardportionen_verwaltung.php';
require_once './model/portionenaenderung.php';
require_once './model/portionenaenderung_verwaltung.php';
require_once './model/bestellung_has_speise.php';
require_once './model/bestellung_has_speise_verwaltung.php';
require_once './model/abrechnungstag.php';
require_once './model/abrechnungstag_verwaltung.php';
require_once './model/user.php';
require_once './model/user_verwaltung.php';
require_once './model/indi_faktor.php';
require_once './model/indi_faktor_verwaltung.php';
require_once './model/bemerkung_zu_tag.php';
require_once './model/bemerkung_zu_tag_verwaltung.php';
require_once './model/bemerkung_zu_speise.php';
require_once './model/bemerkung_zu_speise_verwaltung.php';
require_once './model/rechnungsadresse.php';
require_once './model/rechnungsadresse_verwaltung.php';
require_once './model/infoticker.php';
require_once './model/infoticker_verwaltung.php';
require_once './model/fahrer_codes.php';
require_once './model/fahrer_codes_verwaltung.php';
require_once './model/menunamen.php';
require_once './model/menunamen_verwaltung.php';
require_once './model/traeger.php';
require_once './model/traeger_verwaltung.php';
require_once './functions/functions.php';

/*
require_once '../sbarscan/model/sc_besteck.php';
require_once '../sbarscan/model/sc_besteck_verwaltung.php';
require_once '../sbarscan/model/sc_spuelbox.php';
require_once '../sbarscan/model/sc_spuelbox_verwaltung.php';
require_once '../sbarscan/model/sc_polierbox.php';
require_once '../sbarscan/model/sc_polierbox_verwaltung.php';
*/

setlocale(LC_TIME, 'de_DE@euro', 'de_DE', 'de', 'ge');
ini_set('display_errors', 'on');


$rechner = php_uname('n');
$optionen = array(
    PDO::ATTR_PERSISTENT => true,
    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION
);
$_SESSION['is_local_server'] = false;
$dev_ordner = false;

$db = new PDO("mysql:host=".$_ENV['DB_HOST'].";dbname=".$_ENV['DB_NAME'].";", $_ENV['DB_USER'], $_ENV['DB_PASS'], $optionen);


$pids_sbar_speisewahl = array(
		);

$db->query('SET NAMES utf8');

$bestellungVerwaltung = new BestellungVerwaltung($db);
$einrichtungskategorieVerwaltung = new EinrichtungskategorieVerwaltung($db);
$kundeVerwaltung = new KundeVerwaltung($db);
$menge_pro_portionVerwaltung = new MengeProPortionVerwaltung($db);
$speiseVerwaltung = new SpeiseVerwaltung($db);
$standardportionenVerwaltung = new StandardportionenVerwaltung($db);
$portionenaenderungVerwaltung = new PortionenaenderungVerwaltung($db);
$bestellung_has_speiseVerwaltung = new BestellungHasSpeiseVerwaltung($db);
$abrechnungstagVerwaltung = new AbrechnungstagVerwaltung($db);
$indifaktorVerwaltung = new IndifaktorVerwaltung($db);
$userVerwaltung = new UserVerwaltung($db);
$bemerkungzutagVerwaltung = new BemerkungZuTagVerwaltung($db);
$bemerkungzuspeiseVerwaltung = new BemerkungZuSpeiseVerwaltung($db);
$rechnungsadresseVerwaltung = new RechnungsadresseVerwaltung($db);
$infotickerVerwaltung = new InfotickerVerwaltung($db);
/*$besteckVerwaltung = new BesteckVerwaltung($db);
$spuelboxVerwaltung = new SpuelboxVerwaltung($db);
$polierboxVerwaltung = new PolierboxVerwaltung($db);*/
$fahrerCodesVerwaltung = new FahrerCodeVerwaltung($db);
$menunamenVerwaltung = new MenunamenVerwaltung($db);
$traegerVerwaltung = $sozialamtVerwaltung = new TraegerVerwaltung($db);

if (!isset($_REQUEST['action'])) {
    $_REQUEST['action'] = '';
}
$action = $_REQUEST['action'] ? $_REQUEST['action'] : 'cockpit';

$color_speisen = array(
    1 => '#ECAC8F',
    2 => '#B9DCFF',
    3 => '#89E18D',
    4 => '#89E18D',
    5 => '#EC5006',
    6 => '#69B4FF',
    7 => '#36B93D',
    8 => '#36B93D'
);

$ipadresse = $_SERVER["REMOTE_ADDR"];
if (($action == 'erzeuge_lieferscheine_direkt' || $action == 'erzeuge_lieferscheine_direkt_do' || $action == 'erzeuge_fahrerliste_direkt' || $action == 'erzeuge_fahrerliste_direkt_do') && ($ipadresse == '84.176.122.136' || $ipadresse == '84.176.124.53' || $ipadresse == '212.114.85.154' || $ipadresse == '212.114.85.155' || $ipadresse == '212.114.85.156' || $ipadresse == '212.114.85.157' || $ipadresse == '212.114.85.158')) {

} else {

    if ((!$_SESSION['logged_in_user'] && $action != 'login')) {

        if ($action != 'do_login' && $action != 'infoticker_anzeige' && $action != 'infoticker_done_anz') {
            header('location:index.php?action=login');
        } elseif (($action == 'infoticker_anzeige' || $action == 'infoticker_done_anz') && $_REQUEST['itanzid'] == 'x23kaod023ji3jias90123ljksdasd') {

        } else {
            //header('location:index.php?action=login');
            header('location:http://www.s-bar.net');
        }
    }
}


//aktualisiereDatenMitTagMonatJahr($abrechnungstagVerwaltung, $bestellungVerwaltung, $portionenaenderungVerwaltung, $bemerkungzutagVerwaltung);
if ($action != 'pruefe_portionen') {
    $_SESSION['edited_portaends'] = array();
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
switch ($action) {

     case 'delete_sozialamt':
        $sozialamt_to_delete = $sozialamtVerwaltung->findeAnhandVonId($_REQUEST['aid']);

        $sozialamtVerwaltung->loesche($sozialamt_to_delete);
        header('location:index.php?action=traeger');
        exit;
        break;
    case 'save_sozialamt':
        $_REQUEST['notiz'] = nl2br($_REQUEST['notiz']);
        $neues_amt = new Traeger($_REQUEST);

     /*   echo '<pre>';
          var_dump($neues_amt);
          echo '</pre>';
          exit; */
        if ($neues_amt->istValide()) {

            $sozialamtVerwaltung->speichere($neues_amt);

            unset($_SESSION['neues_amt_request']);
        header('location:index.php?action=traeger&amtid=' . $neues_amt->getId());
            exit;
        } else {
            $_SESSION['neues_amt_request'] = $_REQUEST;
        header('location:index.php?action=traeger&do=new&amtid=' . $neues_amt->getId());
            exit;
        }

        break;

    case 'erstelle_speisenuebersicht':
        $kategorien = $einrichtungskategorieVerwaltung->findeAlleSort();
        $xls_data = array();
        $xls_spalten_assoc = array(
            'speise' => 'Speise',
            'einheit' => 'Einheit'
        );
        foreach ($kategorien as $kat) {
            if ($kat->getId() == 5 || $kat->getId() == 6) {
                continue;
            }
            $xls_spalten_assoc[$kat->getId()] = $kat->getBezeichnung();
        }

        $speisen = $speiseVerwaltung->findeAlle();
        foreach ($speisen as $speise) {
            $mengen_pro_portion_zu_speise = $menge_pro_portionVerwaltung->findeAnhandVonSpeiseIdSort($speise->getId());

            $name = $speise->getBezeichnung();
            $data_speise = array($name);

            $c = 1;
            foreach ($mengen_pro_portion_zu_speise as $menge_pro_portion) {
                if ($menge_pro_portion->getEinrichtungskategorieId() == 5 || $menge_pro_portion->getEinrichtungskategorieId() == 6) {
                    continue;
                }
                $kategorie = $einrichtungskategorieVerwaltung->findeAnhandVonId($menge_pro_portion->getEinrichtungskategorieId());
                if (!$kategorie->getId()) {
                    continue;
                }
                if ($c == 1) {
                    $data_speise[1] = $menge_pro_portion->getEinheit();
                }

                if ($c > 1) {
                    // $name = '';
                }
                $einrichtungsart = $einrichtungskategorieVerwaltung->findeAnhandVonId($menge_pro_portion->getEinrichtungskategorieId());
                $data_speise[] = /* $einrichtungsart->getBezeichnung() . '-' . */ $menge_pro_portion->getMenge();

                $c++;
            }
            $xls_data[] = $data_speise;
        }

        $xls_spalten = array_values($xls_spalten_assoc);
        /*   echo '<pre>';
          var_dump($xls_spalten_assoc);
          var_dump($xls_spalten);
          var_dump($xls_data);
          echo '</pre>'; */
        $dateiname = 'sbar_speisen_stand_' . date('d') . date('m') . date('Y');
        erzeugeExcel($xls_spalten, $xls_data, '', $dateiname, 'none', 'none');
        break;
    case 'corona_settings':



        exit;
        $kunden_not = $kundeVerwaltung->findeAlleNotgruppen();
        $kunden_standard = $kundeVerwaltung->findeAlleNichtNotgruppen();
        $kunden_all = $kundeVerwaltung->findeAlleReal();
        echo '<pre>';
        var_dump(count($kunden_not));
        var_dump(count($kunden_standard));
        var_dump(count($kunden_all));
        echo '</pre>';
        /*  */


        foreach ($kunden_standard as $kunde) {
            if ($kunde->getAktiv() != 1) {
                continue;
            }

            echo '<pre>';
            var_dump($kunde->getId());
            var_dump($portionenaenderungen_arr);
            echo '</pre>';

            //portionenaenderungen auf null bei nicht notgruppen
            $portionenaenderungen_arr = $portionenaenderungVerwaltung->findeAlleZuKundenIdUndWochenstartdatum($kunde->getId(), 1, 6, 2020);
            foreach ($portionenaenderungen_arr as $portionenaenderung) {
                $portionenaenderung->setPortionenMo(0);
                $portionenaenderung->setPortionenDi(0);
                $portionenaenderung->setPortionenMi(0);
                $portionenaenderung->setPortionenDo(0);
                $portionenaenderung->setPortionenFr(0);
                /// $portionenaenderungVerwaltung->speichere($portionenaenderung);
            }


            //standportionen auf 0 bei nicht notgruppen
            /*   $standardportionen_arr = $standardportionenVerwaltung->findeAlleZuKundenId($kunde->getId());

              foreach ($standardportionen_arr as $standardportionen) {
              $standardportionen->setPortionenMo(0);
              $standardportionen->setPortionenDi(0);
              $standardportionen->setPortionenMi(0);
              $standardportionen->setPortionenDo(0);
              $standardportionen->setPortionenFr(0);
              $standardportionenVerwaltung->speichere($standardportionen);
              }
             */
        }

        foreach ($kunden_not as $kunde) {
            $name_alt = $kunde->getName();
            $name_neu = trim($name_alt);
            /*   $name_neu = str_replace('Notgruppe','#',$name_neu);
              $name_neu = str_replace('Notfallgruppe','#',$name_neu);
              $name_neu = str_replace('Notgurppe','#',$name_neu);
              $name_neu = str_replace('Notgrppe','#',$name_neu);
              $name_neu = str_replace('  ',' ',$name_neu);
              $name_neu = str_replace('# ','#',$name_neu);
              $kunde->setName($name_neu);
              $kundeVerwaltung->speichere($kunde); */
        }
        break;

    case 'erzeuge_sonderwunschliste':

        $tag = $_REQUEST['tag'];
        $monat = $_REQUEST['monat'];
        $jahr = $_REQUEST['jahr'];
        $kunden = $kundeVerwaltung->findeAlleAktiven('name');
        $bestellung = $bestellungVerwaltung->findeAlleZuDatum($tag, $monat, $jahr);
        $bestellung = $bestellung[0];
        $bests_has_speisen = $bestellung_has_speiseVerwaltung->findeAnhandVonBestellungId($bestellung->getId());
        $speisen_ids_zu_speise_nr = array(1 => array(), 2 => array(), 3 => array(), 4 => array());
        $speisen_bezeichnungen = array();
        foreach ($bests_has_speisen as $best_has_speise) {
            $speise_id = $best_has_speise->getSpeiseId();
            $speise_nr = $best_has_speise->getSpeiseNr();
            $speise = $speiseVerwaltung->findeAnhandVonId($speise_id);
            $speisen_ids_zu_speise_nr[$speise_nr][] = $speise_id;
            $speisen_bezeichnungen[$speise_id] = $speise->getBezeichnung();
        }
        $sonderinfos = array();
        foreach ($kunden as $kunde) {
            $bestellinfos = ermittleBestellinfosZuTag($kunde, $tag, $monat, $jahr, $kundeVerwaltung, $standardportionenVerwaltung, $portionenaenderungVerwaltung, $menunamenVerwaltung);

            $kunde_id = $kunde->getId();
            $kunde_key = $kunde_id . '|' . $kunde->getName();
            $bemerkung_kunde = $kunde->getBemerkung();
            foreach ($bestellinfos as $speise_nr_string => $bestellinfo) {
                if ($bestellinfo['anzahl'] == 0) {
                    //continue;
                }
                $speise_nr = substr($speise_nr_string, -1);
                if (trim($bemerkung_kunde) != '') {
                    $sonderinfos[$kunde_key][$speise_nr]['bemerkung_kunde'] = $bemerkung_kunde;
                }
                $sonderinfos[$kunde_key][$speise_nr]['bemerkung_kunde_zu_speisen'] = array();
                $sonderinfos[$kunde_key][$speise_nr]['portionen'] = $bestellinfo['anzahl'];
                $speise_ids_zu_snr = $speisen_ids_zu_speise_nr[$speise_nr];

                foreach ($speise_ids_zu_snr as $speise_id) {
                    $kundenbemerkung_zu_speise = $bemerkungzuspeiseVerwaltung->findeAnhandVonKundeIdUndSpeiseId($kunde_id, $speise_id);
                    $kunden_bemerkung_zu_tag = $bemerkungzutagVerwaltung->findeAnhandVonKundeIdUndDatumUndSpeiseId($kunde_id, $tag, $monat, $jahr, $speise_id);

                    if ($kundenbemerkung_zu_speise->getBemerkung()) {
                        $sonderinfos[$kunde_key][$speise_nr]['bemerkung_kunde_zu_speisen'][$speisen_bezeichnungen[$speise_id]] = $kundenbemerkung_zu_speise->getBemerkung();
                    }
                    if ($kunden_bemerkung_zu_tag->getBemerkung()) {
                        $sonderinfos[$kunde_key][$speise_nr]['bemerkung_kunde_zu_tag'][$speisen_bezeichnungen[$speise_id]] = $kunden_bemerkung_zu_tag->getBemerkung();
                    }
                }
            }


            //kundenbemerkung_zu_speise
            //
        }

        $xls_spalten = array('Kunde', 'allg. Bemerkung Kunde', 'Speise Nr.', 'Portionen', 'Bemerkung zu Speisen');
        $xls_daten = array();
        //  $xls_daten[] = array($tag . '.' . $monat . '.' . $jahr, '', '', '');
        foreach ($sonderinfos as $kunde_str => $bemerkungen_array_snrs) {
            $kunde_str_arr = explode('|', $kunde_str);
            $kunde_name = $kunde_str_arr[1];

            foreach ($bemerkungen_array_snrs as $speise_nr => $bemerkungen_array) {
                $bem_speise_str = '';
                $portionen = $bemerkungen_array['portionen'];
                foreach ($bemerkungen_array['bemerkung_kunde_zu_speisen'] as $speise_str => $speise_bemerkung) {
                    $bem_speise_str .= $speise_str . ":\n   " . $speise_bemerkung . "\n";
                }
                $data = array(
                    trim($kunde_name),
                    trim($bemerkungen_array['bemerkung_kunde']),
                    $speise_nr,
                    $portionen,
                    trim($bem_speise_str)
                );
                if (trim($bemerkungen_array['bemerkung_kunde']) || trim($bem_speise_str)) {
                    $xls_daten[] = $data;
                }
            }
        }
        $dateiname = 'Sonderwuensche_' . $tag . '_' . $monat . '_' . $jahr;
        erzeugeExcel($xls_spalten, $xls_daten, $speicherort, $dateiname, 'none', 'none');

        /* echo '<pre>';
          var_dump($sonderinfos);
          echo '</pre>';exit; */
        break;


    case 'erzeuge_fahrerlisten':
    case 'erzeuge_fahrerliste_direkt_do':
        if (isset($_REQUEST['tour_id']) && $_REQUEST['tour_id']) {

            $kunden_tour = $kundeVerwaltung->findeAnhandVonId($_REQUEST['tour_id']);
            $kunden_touren = array($kunden_tour);
        } else {
            $kunden_touren = $kundeVerwaltung->findeAlleTouren();
        }
        $tag = $_REQUEST['tag'];
        $monat = $_REQUEST['monat'];
        $jahr = $_REQUEST['jahr'];
        $datekey = $jahr . '-' . sprintf('%02d', $monat) . '-' . sprintf('%02d', $tag);

        $pdf_data = array();
        foreach ($kunden_touren as $tour) {
            $tourkey = $tour->getName() /* . '[' . $tour->getId() . ']' */;
            $kunden_zu_tour = $kundeVerwaltung->findeAlleZuTourId($tour->getId());
            $menge_diese_tour = 0;
            if (count($kunden_zu_tour) && substr($tour->getName(), 0, 17) != 'BEGINN PRODUKTION') {
                $pdf_data[$datekey][$tourkey] = array();
            } else {
                continue;
            }
            foreach ($kunden_zu_tour as $kunde) {
                $kunde_key = $kunde->getName().' ['.$kunde->getId().']';
                if ($kunde->getEinrichtungskategorieId() == 5 || $kunde->getEinrichtungskategorieId() == 6) {
                    continue;
                }
                $bestellinfos = ermittleBestellinfosZuTag($kunde, $tag, $monat, $jahr, $kundeVerwaltung, $standardportionenVerwaltung, $portionenaenderungVerwaltung, $menunamenVerwaltung);
                $pdf_data[$datekey][$tourkey][$kunde_key]['bestellungen'] = $bestellinfos;
                // if (count($bestellinfos) == 1) {
                //     $pdf_data[$datekey][$tourkey][$kunde->getName()]['bestellungen'][] = array();
                /*   echo '<pre>';
                  var_dump($pdf_data[$datekey][$tourkey][$kunde->getName()]['bestellungen']);
                  echo '</pre>'; */
                //}
  $std = substr($kunde->getEssenszeit(), 0, 2);
            $min = substr($kunde->getEssenszeit(), 2, 2);

                $pdf_data[$datekey][$tourkey][$kunde_key]['anschrift'] = $kunde->getStrasse() . "\n" . $kunde->getPlz() . ' ' . $kunde->getOrt();
                if (trim($kunde->getTelefon())) {
                    $pdf_data[$datekey][$tourkey][$kunde_key]['anschrift'] .=  "\nTel.: " . $kunde->getTelefon();
                }
                if (trim($kunde->getTelefon2())) {
                    $pdf_data[$datekey][$tourkey][$kunde_key]['anschrift'] .=  "\nTel2.: " . $kunde->getTelefon2();
                }

                $pdf_data[$datekey][$tourkey][$kunde_key]['fahrer_info'] =  "";
                if ($std && $std != '00' ) {

                $pdf_data[$datekey][$tourkey][$kunde_key]['fahrer_info'] =  "Zeit: " . $std . ':' . $min.' Uhr'."\n";
                }

                $pdf_data[$datekey][$tourkey][$kunde_key]['fahrer_info'] .= $kunde->getFahrerinfo();
                if (trim($pdf_data[$datekey][$tourkey][$kunde_key]['anschrift']) == '\n') {
                    $pdf_data[$datekey][$tourkey][$kunde_key]['anschrift'] = "---\n---";
                }
            }
        }

        if ($_SESSION['logged_in_user_id'] == 5) {
            erzeugeFahrerlistenPdf2($pdf_data);
        } else {
            erzeugeFahrerlistenPdf2($pdf_data);
        }
        echo '<pre>';
        var_dump($pdf_data);
        echo '</pre>';
        break;

    case 'speichere_fahrer':
        foreach ($_REQUEST['fahrer_codes'] as $code => $name) {
            $fahrer_code = $fahrerCodesVerwaltung->findeAnhandVonCode($code);
            $fahrer_code->setName($name);
            $fahrerCodesVerwaltung->speichere($fahrer_code);
        }
        header('location:index.php?action=fahrer_codes');
        exit;
        exit;
        break;
    case 'kundenliste_xls':
        $kunden = $kundeVerwaltung->findeAlleInklTrenner();
        erzeugeKundenliste($kunden);
        break;
    case 'login':
        break;
    case 'do_login':
        $username = $_REQUEST['username'];
        $passwort = $_REQUEST['passwort'];
        $loggin_user = $userVerwaltung->findeAnhandVonUsername($username);
        $salt_db = $loggin_user->getSalt();
        $pw_db = $loggin_user->getPasswort();
        $passworthash_input = md5($salt_db . md5($passwort));
        if ($passworthash_input == $pw_db) {
            $_SESSION['logged_in_user'] = $loggin_user;
            $_SESSION['logged_in_user_id'] = $loggin_user->getId();
            $_SESSION['logged_in_recht'] = $loggin_user->getRecht();
            $loggin_user->setLetzterLogin($loggin_user->getAktuellerLogin());
            $loggin_user->setAktuellerLogin(time());
            $user = $loggin_user;
            $userVerwaltung->speichere($user);
            header('location:index.php');
        } else {
            $_SESSION['fehler_hinweis'] = 'Ihre Logindaten sind nicht korrekt. Bitte prüfen Sie Ihre Eingabe und versuchen Sie es erneut!';
            header('location:index.php?action=login');
        }
        break;
    case 'log_out':
        unset($_SESSION['logged_in_user']);
        header('location:index.php');
        break;
    case 'do_change_pw':
        $user = $userVerwaltung->findeAnhandVonId($_SESSION['logged_in_user_id']);
        $altesPW = $_REQUEST['old_pw'];
        $neuesPW = $_REQUEST['new_pw'];
        $passworthash_input = md5($user->getSalt() . md5($altesPW));
        if (strlen($_REQUEST['new_pw']) >= 8 && $passworthash_input == $user->getPasswort() && $_REQUEST['new_pw'] == $_REQUEST['new_pw_check'] && $_REQUEST['new_pw'] != '') {
            $passwort = $neuesPW;
            $key = register_generate_salt();
            $pwhash = md5($key . md5($passwort));
            $user->setSalt($key);
            $user->setPasswort($pwhash);
            $userVerwaltung->speichere($user);

            $_SESSION['fehler'] .= '<span style="color:green;">Ihr Passwort wurde geändert.</span>';
            header('location:index.php?action=change_pw');
        } else {
            if ($passworthash_input != $user->getPasswort()) {
                $_SESSION['fehler'] .= 'Ihre Eingabe bei "Aktuelles Passwort" ist nicht korrekt.<br />';
            }
            if ($_REQUEST['new_pw'] != $_REQUEST['new_pw_check']) {
                $_SESSION['fehler'] .= 'Das neue Passwort und dessen Bestätigung stimmen nicht überein.<br />';
            }
            if (strlen($_REQUEST['new_pw']) < 8) {
                $_SESSION['fehler'] .= 'Das neue Passwort muss mindestens 8 Zeichen lang sein.<br />';
            }
            header('location:index.php?action=change_pw');
        }
        break;
    case 'uebersicht':
        break;
    case 'neuer_kunde':
        $einrichtungskategorien = $einrichtungskategorieVerwaltung->findeAlle();

        if ($_REQUEST['master'] == 0 && !$_REQUEST['masternummer_unterkunde']) {
            $_REQUEST['masternummer'] = NULL;
        }
        if ($_REQUEST['master'] == 0 && $_REQUEST['masternummer_unterkunde']) {
            $_REQUEST['masternummer'] = $_REQUEST['masternummer_unterkunde'];
        }

        if ($_REQUEST['tbxDatum'] != '') {
            $tag = substr($_REQUEST['tbxDatum'], 0, 2);
            $monat = substr($_REQUEST['tbxDatum'], 3, 2);
            $jahr = substr($_REQUEST['tbxDatum'], 6, 4);
            $_REQUEST['startdatum'] = mktime(12, 0, 0, $monat, $tag, $jahr);
        } else {
            //$_REQUEST['startdatum'] = mktime(0,0,0,date('m'), date('d'), date('Y'));
        }
        $now = mktime(0, 0, 0, date('m'), date('d'), date('Y'));
        /*   if (date('I', $_REQUEST['startdatum'])) {
          $_REQUEST['startdatum'] += 3600;
          $now += 3600;
          } */
        switch ($_REQUEST['kundentyp']) {
            case 1:
                $_REQUEST['staedtischer_kunde'] = 0;
                $_REQUEST['bio_kunde'] = 0;
                break;
            case 2:
                $_REQUEST['staedtischer_kunde'] = 1;
                break;
            case 3:
                $_REQUEST['bio_kunde'] = 1;
                break;
        }

        $daten_rechad = array();
        $daten_rechad['id'] = $_REQUEST['rechad_id'];
        $daten_rechad['kunde_id'] = $_REQUEST['rechad_kunde_id'];
        $daten_rechad['firma'] = $_REQUEST['rechad_firma'];
        $daten_rechad['vorname'] = $_REQUEST['rechad_vorname'];
        $daten_rechad['nachname'] = $_REQUEST['rechad_nachname'];
        $daten_rechad['strasse'] = $_REQUEST['rechad_strasse'];
        $daten_rechad['plz'] = (int)$_REQUEST['rechad_plz'];
        $daten_rechad['ort'] = $_REQUEST['rechad_ort'];
        $daten_rechad['email'] = $_REQUEST['rechad_email'];
        $daten_rechad['traeger_id'] = $_REQUEST['traeger_id'];
        $daten_rechad['aktiv'] = $_REQUEST['rechad_aktiv'];

        if ($_REQUEST['kid']) {
            $kunde = $kundeVerwaltung->findeAnhandVonId($_REQUEST['kid']);
            $rechnungsadresse = $rechnungsadresseVerwaltung->findeAnhandVonKundeId($_REQUEST['kid']);

            if (!$rechnungsadresse) {
                $rechnungsadresse = new Rechnungsadresse($daten_rechad);
            }
        } else {
            $kunde = new Kunde($_REQUEST);
            $rechnungsadresse = new Rechnungsadresse($daten_rechad);
        }
        if ($_REQUEST['what'] == 'edit') {
            $_REQUEST['aktiv'] = $kunde->getAktiv();
            $_REQUEST['anzahl_boxen'] = $kunde->getAnzahlBoxen();
        }
        if ($_REQUEST['startdatum'] <= $now && $_REQUEST['startdatum'] > 0) {
            $_REQUEST['aktiv'] = 1;
        } elseif ($_REQUEST['startdatum'] >= $now && $_REQUEST['startdatum'] > 0) {
            // $_REQUEST['aktiv'] = 0;
        }
        if ($_REQUEST['step'] == 2 && $_REQUEST['what'] == 'edit') {
            if (isset($_REQUEST['kitafino_gruppen'])) {
                $_REQUEST['kitafino_gruppen'] = implode(',', $_REQUEST['kitafino_gruppen']);
            }

            $kunde = new Kunde($_REQUEST);
            $kunde_pre_edit = $kundeVerwaltung->findeAnhandVonId($kunde->getId());
            if ($kunde_pre_edit->getStaedtischerKunde() || $kunde_pre_edit->getBioKunde()) {
                $kunde_vorher = 'bio_stadt';
            } else {
                $kunde_vorher = 'standard';
            }
            $go_check_portionen = false;
            if ($kunde_pre_edit->getKundennummer() && !$kunde->getKundennummer()) {
                $go_check_portionen = true;
            }

            $rechnungsadresse = new Rechnungsadresse($daten_rechad);
            if ($kundeVerwaltung->speichere($kunde)) {
                $rechnungsadresseVerwaltung->speichere($rechnungsadresse);
                $kid = $kunde->getId();
                //checke ob portionenaenderungen vorhanden
                $portionenaenderung_check = $portionenaenderungVerwaltung->findeAnhandVonKundenId($kid);
                if (count($portionenaenderung_check)) {
                    foreach ($portionenaenderung_check as $port_aend) {
                        if ($kunde->getStaedtischerKunde() || $kunde->getBioKunde()) {
                            switch ($port_aend->getSpeiseNr()) {
                                case 1:
                                    $port_aend->setSpeiseNr(3);
                                    break;
                                case 2:
                                    $port_aend->setSpeiseNr(4);
                                    break;
                            }
                        } else {
                            switch ($port_aend->getSpeiseNr()) {
                                case 3:
                                    $port_aend->setSpeiseNr(1);
                                    break;
                                case 4:
                                    $port_aend->setSpeiseNr(2);
                                    break;
                            }
                        }
                        $portionenaenderungVerwaltung->speichere($port_aend);
                    }
                }
                //checke ob standardportionen vorhanden

                $standardportionen_check = $standardportionenVerwaltung->findeAlleZuKundenId($kid);
                if (count($standardportionen_check)) {
                    foreach ($standardportionen_check as $standpors) {
                        if (($kunde->getStaedtischerKunde() || $kunde->getBioKunde()) && $kunde_vorher == 'standard') {
                            switch ($standpors->getSpeiseNr()) {
                                case 1:
                                    $standpors->setSpeiseNr(3);
                                    break;
                                case 2:
                                    $standpors->setSpeiseNr(4);
                                    break;
                                case 3:
                                    $standpors->setSpeiseNr(1);
                                    break;
                                case 4:
                                    $standpors->setSpeiseNr(2);
                                    break;
                            }
                        } elseif (!$kunde->getStaedtischerKunde() && !$kunde->getBioKunde() && $kunde_vorher == 'bio_stadt') {
                            switch ($standpors->getSpeiseNr()) {
                                case 1:
                                    $standpors->setSpeiseNr(3);
                                    break;
                                case 2:
                                    $standpors->setSpeiseNr(4);
                                    break;
                                case 3:
                                    $standpors->setSpeiseNr(1);
                                    break;
                                case 4:
                                    $standpors->setSpeiseNr(2);
                                    break;
                            }
                        }
                        $standardportionenVerwaltung->speichere($standpors);
                    }
                }

                if ($go_check_portionen) {
                    header("location:index.php?action=pruefe_portionen&kunde_id=" . $kunde->getId() . '&info=kitafino_del');
                } else {
                    header("location:index.php?action=neuer_kunde&kid=" . $kunde->getId() . "&what=edit");
                }
                // header("location:index.php?action=kundenverwaltung");
            }
        } elseif ($_REQUEST['step'] == 2 && !isset($_REQUEST['what'])) {

            $kunde = new Kunde($_REQUEST);
            $rechnungsadresse = new Rechnungsadresse($daten_rechad);
            if ($kundeVerwaltung->speichere($kunde)) {
                $kid = $kunde->getId();
                $rechnungsadresse->setKundeId($kid);
                $rechnungsadresseVerwaltung->speichere($rechnungsadresse);

                if ($kunde->getStaedtischerKunde()) {
                    $standardportionen = new Standardportionen();
                    $standardportionen2 = new Standardportionen();
                    $standardportionen3 = new Standardportionen();
                    $standardportionen4 = new Standardportionen();

                    $standardportionen->setKundeId($kid);
                    $standardportionen->setPortionenMo(0);
                    $standardportionen->setPortionenDi(0);
                    $standardportionen->setPortionenMi(0);
                    $standardportionen->setPortionenDo(0);
                    $standardportionen->setPortionenFr(0);
                    $standardportionen->setSpeiseNr(1);


                    $standardportionen2->setKundeId($kid);
                    $standardportionen2->setPortionenMo(0);
                    $standardportionen2->setPortionenDi(0);
                    $standardportionen2->setPortionenMi(0);
                    $standardportionen2->setPortionenDo(0);
                    $standardportionen2->setPortionenFr(0);
                    $standardportionen2->setSpeiseNr(2);

                    $standardportionen3->setKundeId($kid);
                    $standardportionen3->setPortionenMo(0);
                    $standardportionen3->setPortionenDi(0);
                    $standardportionen3->setPortionenMi(0);
                    $standardportionen3->setPortionenDo(0);
                    $standardportionen3->setPortionenFr(0);
                    $standardportionen3->setSpeiseNr(3);

                    $standardportionen4->setKundeId($kid);
                    $standardportionen4->setPortionenMo(0);
                    $standardportionen4->setPortionenDi(0);
                    $standardportionen4->setPortionenMi(0);
                    $standardportionen4->setPortionenDo(0);
                    $standardportionen4->setPortionenFr(0);
                    $standardportionen4->setSpeiseNr(4);

                    $standardportionenVerwaltung->speichere($standardportionen);
                    $standardportionenVerwaltung->speichere($standardportionen2);
                    $standardportionenVerwaltung->speichere($standardportionen3);
                    $standardportionenVerwaltung->speichere($standardportionen4);
                }
                if ($kunde->getEinrichtungskategorieId() == 6) {

                    $standardportionen = new Standardportionen();
                    $standardportionen->setKundeId($kid);
                    $standardportionen->setPortionenMo(1);
                    $standardportionen->setPortionenDi(1);
                    $standardportionen->setPortionenMi(1);
                    $standardportionen->setPortionenDo(1);
                    $standardportionen->setPortionenFr(1);
                    $standardportionen->setSpeiseNr(1);
                    $standardportionenVerwaltung->speichere($standardportionen);
                }


                header("location:index.php?action=neuer_kunde_step2&kid=$kid");
                exit;
            }
        }
        break;
    case 'neuer_kunde_step2':
        $kid = $_REQUEST['kid'];
        $eingetragene_port_aenderungen = $portionenaenderungVerwaltung->findeAnhandVonKundenIdUndAbWochenstarttagts($kid, time());

        $kunde = $kundeVerwaltung->findeAnhandVonId($_REQUEST['kid']);
        if ($standardportionenVerwaltung->findeAnhandVonKundenId($_REQUEST['kid'])) {
            $standardportionen = $standardportionenVerwaltung->findeAnhandVonKundenIdUndSpeiseNr($_REQUEST['kid']);
            $standardportionen2 = $standardportionenVerwaltung->findeAnhandVonKundenIdUndSpeiseNr($_REQUEST['kid'], 2);
            $standardportionen3 = $standardportionenVerwaltung->findeAnhandVonKundenIdUndSpeiseNr($_REQUEST['kid'], 3);
            $standardportionen4 = $standardportionenVerwaltung->findeAnhandVonKundenIdUndSpeiseNr($_REQUEST['kid'], 4);
        } else {
            $standardportionen = new Standardportionen($_REQUEST);
            $standardportionen2 = new Standardportionen($_REQUEST);
            $standardportionen3 = new Standardportionen($_REQUEST);
            $standardportionen4 = new Standardportionen($_REQUEST);
        }
        if ($_REQUEST['step'] == 2) {
            $standardportionen = new Standardportionen($_REQUEST);
            $standardportionen2 = new Standardportionen();
            $standardportionen3 = new Standardportionen();
            $standardportionen4 = new Standardportionen();

            $standardportionen2->setId($_REQUEST['id2']);
            $standardportionen2->setKundeId($kid);
            $standardportionen2->setPortionenMo((int)$_REQUEST['portionen2_mo']);
            $standardportionen2->setPortionenDi((int)$_REQUEST['portionen2_di']);
            $standardportionen2->setPortionenMi((int)$_REQUEST['portionen2_mi']);
            $standardportionen2->setPortionenDo((int)$_REQUEST['portionen2_do']);
            $standardportionen2->setPortionenFr((int)$_REQUEST['portionen2_fr']);
            $standardportionen2->setSpeiseNr(2);

            $standardportionen3->setId($_REQUEST['id3']);
            $standardportionen3->setKundeId($kid);
            $standardportionen3->setPortionenMo((int)$_REQUEST['portionen3_mo']);
            $standardportionen3->setPortionenDi((int)$_REQUEST['portionen3_di']);
            $standardportionen3->setPortionenMi((int)$_REQUEST['portionen3_mi']);
            $standardportionen3->setPortionenDo((int)$_REQUEST['portionen3_do']);
            $standardportionen3->setPortionenFr((int)$_REQUEST['portionen3_fr']);
            $standardportionen3->setSpeiseNr(3);

            $standardportionen4->setId($_REQUEST['id4']);
            $standardportionen4->setKundeId($kid);
            $standardportionen4->setPortionenMo((int)$_REQUEST['portionen4_mo']);
            $standardportionen4->setPortionenDi((int)$_REQUEST['portionen4_di']);
            $standardportionen4->setPortionenMi((int)$_REQUEST['portionen4_mi']);
            $standardportionen4->setPortionenDo((int)$_REQUEST['portionen4_do']);
            $standardportionen4->setPortionenFr((int)$_REQUEST['portionen4_fr']);
            $standardportionen4->setSpeiseNr(4);
            /*   echo '<pre>';
              var_dump($eingetragene_port_aenderungen);
              echo '</pre>';exit;* */

            // if ($standardportionenVerwaltung->speichere($standardportionen)) {
            $standardportionenVerwaltung->speichere($standardportionen);
            $standardportionenVerwaltung->speichere($standardportionen2);
            $standardportionenVerwaltung->speichere($standardportionen3);
            $standardportionenVerwaltung->speichere($standardportionen4);
            if (count($eingetragene_port_aenderungen) == 0) {
                header("location:index.php?action=kundenverwaltung");
            } else {
                header("location:index.php?action=pruefe_portionen&kunde_id=$kid");
            }
            //}
        }
        break;
    case 'kunde_set_besteck':
        $kid = $_REQUEST['kid'];
        $kunde = $kundeVerwaltung->findeAnhandVonId($kid);
        $kunde->setBesteck($_REQUEST['set'] * 1);


        $kundeVerwaltung->speichere($kunde);
        header("location:index.php?action=kundenverwaltung");
        break;
    case 'erzeuge_besteck_codes':
        $besteck_kunden = $kundeVerwaltung->findeAlleAktivenMitBesteck();
        $codes_daten = array();
        foreach ($besteck_kunden as $besteck_kunde) {
            $code = 'B-T-' . $besteck_kunde->getId();

            $codes_daten[$besteck_kunde->getTourId()][$besteck_kunde->getId()]['name'] = $besteck_kunde->getName();
            $codes_daten[$besteck_kunde->getTourId()][$besteck_kunde->getId()]['code'] = $code;
            $tour = $kundeVerwaltung->findeAnhandVonId($besteck_kunde->getTourId());
            $codes_daten[$besteck_kunde->getTourId()][$besteck_kunde->getId()]['tourname'] = $tour->getName();
            /*    */
        }
        erzeugeBesteckExcel($codes_daten);
        break;

    case 'erzeuge_etiketten_pdf':

        switch ($_REQUEST['typ']) {
            case 'polier':
                $durchgaenge = 48;
                break;
            case 'spuel':
                $durchgaenge = 12;
                break;
        }
        $boxen_daten = array();
        for ($c = 1; $c <= $durchgaenge; $c++) {
            switch ($_REQUEST['typ']) {
                case 'polier':
                    $name_str = 'Polierbox';
                    $neu_sc_polierbox = new Polierbox();
                    $polierboxVerwaltung->speichere($neu_sc_polierbox);
                    $neu_sc_polierbox->setCode('B-P-' . str_pad($neu_sc_polierbox->getId(), 7, 0, STR_PAD_LEFT));
                    $polierboxVerwaltung->speichere($neu_sc_polierbox);
                    $boxen_daten[$c]['code'] = $neu_sc_polierbox->getCode();
                    $boxen_daten[$c]['name'] = $name_str . ' ' . $neu_sc_polierbox->getId();
                    $faktor = 2;
                    break;
                case 'spuel':
                    $name_str = 'Spülbox';
                    $neu_sc_spuelbox = new Spuelbox();
                    $spuelboxVerwaltung->speichere($neu_sc_spuelbox);
                    $neu_sc_spuelbox->setCode('B-S-' . str_pad($neu_sc_spuelbox->getId(), 7, 0, STR_PAD_LEFT));
                    $spuelboxVerwaltung->speichere($neu_sc_spuelbox);
                    $boxen_daten[$c]['code'] = $neu_sc_spuelbox->getCode();
                    $boxen_daten[$c]['name'] = $name_str . ' ' . $neu_sc_spuelbox->getId();
                    $faktor = 4;
                    break;
            }
        }
        include 'functions/pdf_etikettenbogen.php';
        break;
    case 'erzeuge_inventaretiketten':
        erzeugeInventaretiketten();
        break;

    case 'erzeuge_einrichtungsliste_pdf_etiketten':
        $tag = $_REQUEST['tag'];
        $monat = $_REQUEST['monat'];
        $jahr = $_REQUEST['jahr'];

        $starttag = $_REQUEST['starttag'];
        $startmonat = $_REQUEST['startmonat'];
        $startjahr = $_REQUEST['startjahr'];

        $speise_nr = 1;
        if (isset($_REQUEST['speise_nr'])) {
            $speise_nr = $_REQUEST['speise_nr'];
        }

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

        $bestellungen = $bestellungVerwaltung->findeAlleZuDatum($tag, $monat, $jahr);
        $speise_id = $_REQUEST['speise_id'];

        //erzeugeEinrichtungslisteZuSpeiseNr($kundeVerwaltung, $tag, $monat, $jahr, $starttag, $startmonat, $startjahr, $speise_id, $bestellung, $bestellung_has_speiseVerwaltung, $speiseVerwaltung, $menge_pro_portionVerwaltung, $standardportionenVerwaltung, $portionenaenderungVerwaltung, $kunden, $indifaktorVerwaltung, $bemerkungzutagVerwaltung, $bemerkungzuspeiseVerwaltung, $speise_nr);

        foreach ($bestellungen as $bestellung) {
            $bestellung_has_speise = $bestellung_has_speiseVerwaltung->findeAnhandVonBestellungIdUndSpeiseId($bestellung->getId(), $speise_id);

            // if ($bestellung_has_speise->getSpeiseId() == $speise_id) {
            erzeugeEinrichtungslisteZuSpeiseNrPdfEtiketten($kundeVerwaltung, $tag, $monat, $jahr, $starttag, $startmonat, $startjahr, $speise_id, $bestellung, $bestellung_has_speiseVerwaltung, $speiseVerwaltung, $menge_pro_portionVerwaltung, $standardportionenVerwaltung, $portionenaenderungVerwaltung, $kunden, $indifaktorVerwaltung, $bemerkungzutagVerwaltung, $bemerkungzuspeiseVerwaltung, $speise_nr);
            //}
        }
        break;

    case 'erzeuge_polier_codes':
        $codes_daten = array();
        for ($i = 1; $i <= 20; $i++) {
            $neu_sc_polierbox = new Polierbox();
            $polierboxVerwaltung->speichere($neu_sc_polierbox);
            $neu_sc_polierbox->setCode('B-P-' . str_pad($neu_sc_polierbox->getId(), 7, 0, STR_PAD_LEFT));
            $polierboxVerwaltung->speichere($neu_sc_polierbox);
            $codes_daten[$neu_sc_polierbox->getId()] = $neu_sc_polierbox->getCode();
        }
        echo '<pre>';
        var_dump($codes_daten);
        echo '</pre>';
        break;
    case 'erzeuge_spuel_codes':
        $codes_daten = array();
        for ($i = 1; $i <= 20; $i++) {
            $neu_sc_spuelbox = new Spuelbox();
            $spuelboxVerwaltung->speichere($neu_sc_spuelbox);
            $neu_sc_spuelbox->setCode('B-S-' . str_pad($neu_sc_spuelbox->getId(), 7, 0, STR_PAD_LEFT));
            $spuelboxVerwaltung->speichere($neu_sc_spuelbox);
            $codes_daten[$neu_sc_spuelbox->getId()] = $neu_sc_spuelbox->getCode();
        }
        echo '<pre>';
        var_dump($codes_daten);
        echo '</pre>';
        break;
    case 'save_speisenzahl':
        $kid = $_REQUEST['kunde_id'];
        $kunde = $kundeVerwaltung->findeAnhandVonId($kid);
        $kunde->setAnzahlSpeisen($_REQUEST['anzahl_speisen']);
        $kundeVerwaltung->speichere($kunde);
        header("location:index.php?action=neuer_kunde_step2&kid=$kid&what=edit");
        exit;
        break;
    case 'save_boxenzahl':
        $kid = $_REQUEST['kunde_id'];
        $kunde = $kundeVerwaltung->findeAnhandVonId($kid);
        $kunde->setAnzahlBoxen($_REQUEST['anzahl_boxen']);
        $kundeVerwaltung->speichere($kunde);
        header("location:index.php?action=neuer_kunde_step2&kid=$kid&what=edit");
        exit;
        break;
    case 'pruefe_portionen':
        $kid = $_REQUEST['kunde_id'];
        $kunde = $kundeVerwaltung->findeAnhandVonId($kid);
        $tag_in_angezeigter_woche_ts = mktime(12, 0, 0, date('m'), date('d'), date('Y'));
        $wochentag_string = strftime('%a', $tag_in_angezeigter_woche_ts);

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


        $eingetragene_port_aenderungen = $portionenaenderungVerwaltung->findeAnhandVonKundenIdUndAbWochenstarttagDatum($kid, $start_tag_woche, $start_monat_woche, $start_jahr_woche, 1);
        $eingetragene_port_aenderungen2 = $portionenaenderungVerwaltung->findeAnhandVonKundenIdUndAbWochenstarttagDatum($kid, $start_tag_woche, $start_monat_woche, $start_jahr_woche, 2);
        $eingetragene_port_aenderungen3 = $portionenaenderungVerwaltung->findeAnhandVonKundenIdUndAbWochenstarttagDatum($kid, $start_tag_woche, $start_monat_woche, $start_jahr_woche, 3);
        $eingetragene_port_aenderungen4 = $portionenaenderungVerwaltung->findeAnhandVonKundenIdUndAbWochenstarttagDatum($kid, $start_tag_woche, $start_monat_woche, $start_jahr_woche, 4);

        $kunde = $kundeVerwaltung->findeAnhandVonId($kid);
        $standardportionen = $standardportionenVerwaltung->findeAnhandVonKundenIdUndSpeiseNr($kid, 1);
        $standardportionen2 = $standardportionenVerwaltung->findeAnhandVonKundenIdUndSpeiseNr($kid, 2);
        $standardportionen3 = $standardportionenVerwaltung->findeAnhandVonKundenIdUndSpeiseNr($kid, 3);
        $standardportionen4 = $standardportionenVerwaltung->findeAnhandVonKundenIdUndSpeiseNr($kid, 4);


        $portionenaenderung = new Portionenaenderung($_REQUEST);
        if ($_REQUEST['do'] == 'save' && isset($_REQUEST['save_without'])) {
            $portionenaenderung = new Portionenaenderung($_REQUEST);

            /* $portionenaenderung2 = new Portionenaenderung($_REQUEST);
              $portionenaenderung2->setId($_REQUEST['id2']); */
            $portionenaenderung->setSpeiseNr($_REQUEST['snr']);
            //$portionenaenderungVerwaltung->speichere($portionenaenderung2);

            $portionenaenderungVerwaltung->speichere($portionenaenderung);
            $_SESSION['edited_portaends'][$kid][] = $portionenaenderung->getId();
            $_SESSION['edited_portaends'][$kid] = array_unique($_SESSION['edited_portaends'][$kid]);
            header("location:index.php?action=pruefe_portionen&kunde_id=$kid" . '#' . $portionenaenderung->getWochenstarttagts());
            exit;
        }

        if ($_REQUEST['do'] == 'save' && isset($_REQUEST['save_with'])) {


            $portionenaenderung = new Portionenaenderung($_REQUEST);
            $portionenaenderung->setPortionenMo($standardportionen->getPortionenMo());
            $portionenaenderung->setPortionenDi($standardportionen->getPortionenDi());
            $portionenaenderung->setPortionenMi($standardportionen->getPortionenMi());
            $portionenaenderung->setPortionenDo($standardportionen->getPortionenDo());
            $portionenaenderung->setPortionenFr($standardportionen->getPortionenFr());
            if ($_REQUEST['snr'] == 2) {
                $portionenaenderung->setSpeiseNr(2);
                $portionenaenderung->setPortionenMo($standardportionen2->getPortionenMo());
                $portionenaenderung->setPortionenDi($standardportionen2->getPortionenDi());
                $portionenaenderung->setPortionenMi($standardportionen2->getPortionenMi());
                $portionenaenderung->setPortionenDo($standardportionen2->getPortionenDo());
                $portionenaenderung->setPortionenFr($standardportionen2->getPortionenFr());
            }
            if ($_REQUEST['snr'] == 3) {
                $portionenaenderung->setSpeiseNr(3);
                $portionenaenderung->setPortionenMo($standardportionen3->getPortionenMo());
                $portionenaenderung->setPortionenDi($standardportionen3->getPortionenDi());
                $portionenaenderung->setPortionenMi($standardportionen3->getPortionenMi());
                $portionenaenderung->setPortionenDo($standardportionen3->getPortionenDo());
                $portionenaenderung->setPortionenFr($standardportionen3->getPortionenFr());
            }
            if ($_REQUEST['snr'] == 4) {
                $portionenaenderung->setSpeiseNr(4);
                $portionenaenderung->setPortionenMo($standardportionen4->getPortionenMo());
                $portionenaenderung->setPortionenDi($standardportionen4->getPortionenDi());
                $portionenaenderung->setPortionenMi($standardportionen4->getPortionenMi());
                $portionenaenderung->setPortionenDo($standardportionen4->getPortionenDo());
                $portionenaenderung->setPortionenFr($standardportionen4->getPortionenFr());
            }

            $portionenaenderungVerwaltung->speichere($portionenaenderung);

            $_SESSION['edited_portaends'][$kid][] = $portionenaenderung->getId();

            $_SESSION['edited_portaends'][$kid] = array_unique($_SESSION['edited_portaends'][$kid]);
            /* $portionenaenderung2 = new Portionenaenderung($_REQUEST);
              $portionenaenderung2->setId($_REQUEST['id2']);
              $portionenaenderung2->setPortionenMo($standardportionen2->getPortionenMo());
              $portionenaenderung2->setPortionenDi($standardportionen2->getPortionenDi());
              $portionenaenderung2->setPortionenMi($standardportionen2->getPortionenMi());
              $portionenaenderung2->setPortionenDo($standardportionen2->getPortionenDo());
              $portionenaenderung2->setPortionenFr($standardportionen2->getPortionenFr());
              $portionenaenderung2->setSpeiseNr(2);
              $portionenaenderungVerwaltung->speichere($portionenaenderung2); */
            header("location:index.php?action=pruefe_portionen&kunde_id=$kid" . '#' . $portionenaenderung->getWochenstarttagts());
        }

        break;
    case 'kunden_aktivieren':
        $kunde = $kundeVerwaltung->findeAnhandVonId($_REQUEST['id']);
        $kunde->setAktiv(1);
        //var_dump($kunde);
        $kundeVerwaltung->speichere($kunde);
        header('location:index.php?action=kundenverwaltung');
        break;
    case 'kunden_deaktivieren':
        $endtag = $_REQUEST['endtag'];
        $endmonat = $_REQUEST['endmonat'];
        $endjahr = $_REQUEST['endjahr'];
        $kunde = $kundeVerwaltung->findeAnhandVonId($_REQUEST['id']);
        $kunde->setAktiv(0);
        $kunde->setStartdatum(0);
        $standardportionen_to_change = $standardportionenVerwaltung->findeAnhandVonKundenId($kunde->getId());

        if ($standardportionen_to_change->getId()) {
            $standardportionen_to_change->setPortionenMo(0);
            $standardportionen_to_change->setPortionenDi(0);
            $standardportionen_to_change->setPortionenMi(0);
            $standardportionen_to_change->setPortionenDo(0);
            $standardportionen_to_change->setPortionenFr(0);
            $standardportionenVerwaltung->speichere($standardportionen_to_change);
        }



        $abrechnungstage_to_del_aus_restmonat = $abrechnungstagVerwaltung->findeAlleZuMonatAbTagImJahr($endtag, $endmonat, $endjahr, $kunde->getId());
        foreach ($abrechnungstage_to_del_aus_restmonat as $delete) {
            $abrechnungstagVerwaltung->loesche($delete);
        }
        $abrechnungstage_to_delete = $abrechnungstagVerwaltung->findeAlleNachMonatJahrZuKunde($endmonat, $endjahr, $kunde->getId());
        foreach ($abrechnungstage_to_delete as $delete) {
            $abrechnungstagVerwaltung->loesche($delete);
        }
        //echo '<pre>';
        //var_dump($abrechnungstage_to_delete);
        //echo '</pre>';

        $kundeVerwaltung->speichere($kunde);

        header('location:index.php?action=kundenverwaltung');

        break;
    case 'real_delete':
        switch ($_REQUEST['what']) {
            case 'kunde':
                $to_delete_obj = $kundeVerwaltung->findeAnhandVonId($_REQUEST['id']);
                $to_delete_string = 'Kunde ' . $to_delete_obj->getName();

                $abort_action = 'kundenverwaltung';
                break;
            case 'einrichtungskategorie':
                $to_delete_obj = $einrichtungskategorieVerwaltung->findeAnhandVonId($_REQUEST['id']);
                $kunden_mit_kat = $kundeVerwaltung->findeAlleMitKategorieId($to_delete_obj->getId());
                if (count($kunden_mit_kat) > 0) {
                    $hinweis = '<p style="color: red;font-weight: bold;">Diese Kategorie kann nicht gelöscht werden, solange dieser noch Kunden zugeordnet sind.<br />Diese müssen zunächst gelöscht werden.</p>';
                }
                $to_delete_string = 'Einrichtungsart ' . $to_delete_obj->getBezeichnung();
                $abort_action = 'einrichtungsverwaltung';
                break;
            case 'speise':
                $to_delete_obj = $speiseVerwaltung->findeAnhandVonId($_REQUEST['id']);
                $to_delete_string = 'Speise ' . $to_delete_obj->getBezeichnung();
                $abort_action = 'speisenverwaltung';
                break;
        }
        $what = $_REQUEST['what'];
        break;
    case 'do_delete':
        switch ($_REQUEST['what']) {
            case 'kunde':
                $to_delete_kunde = $kundeVerwaltung->findeAnhandVonId($_REQUEST['id']);
                $kunde_id = $to_delete_kunde->getId();
                $to_delete_standardportionen = $standardportionenVerwaltung->findeAnhandVonKundenId($kunde_id);
                $to_delete_portionenaenderungen = $portionenaenderungVerwaltung->findeAnhandVonKundenId($kunde_id);
                $to_delete_abrechnungstage = $abrechnungstagVerwaltung->findeAlleZuKundenId($kunde_id);
                $to_delete_indi_faktoren = $indifaktorVerwaltung->findeAlleZuKundenId($kunde_id);
                $to_delete_bestellungen = $bestellungVerwaltung->findeAlleZuKundenId($kunde_id);
                $to_delete_bemerkungen_zu_tag = $bemerkungzutagVerwaltung->findeAlleZuKundenId($kunde_id);
                foreach ($to_delete_bemerkungen_zu_tag as $bemerkung_zu_tag) {
                    $bemerkungzutagVerwaltung->loesche($bemerkung_zu_tag);
                }
                foreach ($to_delete_abrechnungstage as $abrechnungstag) {
                    $abrechnungstagVerwaltung->loesche($abrechnungstag);
                }
                foreach ($to_delete_portionenaenderungen as $delete) {
                    $portionenaenderungVerwaltung->loesche($delete);
                }
                foreach ($to_delete_bestellungen as $bestellung) {
                    $to_delete_bestellung_has_speise = $bestellung_has_speiseVerwaltung->findeAnhandVonBestellungId($id);
                    $bestellung_has_speiseVerwaltung->loescheAlleZuBestellung($bestellung->getId());
                    $bestellungVerwaltung->loesche($bestellung);
                }
                foreach ($to_delete_indi_faktoren as $indi_faktor) {
                    $indifaktorVerwaltung->loesche($indi_faktor);
                }
                $kundeVerwaltung->loesche($to_delete_kunde);
                $standardportionenVerwaltung->loesche($to_delete_standardportionen);
                header('location:index.php?action=kundenverwaltung');
                break;
            case 'einrichtungskategorie':
                $to_delete_obj = $einrichtungskategorieVerwaltung->findeAnhandVonId($_REQUEST['id']);
                $to_delete_objs_2 = $menge_pro_portionVerwaltung->findeAnhandVonEinrichtungskategorieId($to_delete_obj->getId());
                foreach ($to_delete_objs_2 as $to_delete_obj_2) {
                    $menge_pro_portionVerwaltung->loesche($to_delete_obj_2);
                }
                $einrichtungskategorieVerwaltung->loesche($to_delete_obj);
                header('location:index.php?action=einrichtungsverwaltung');
                break;
            case 'speise':
                $to_delete_obj = $speiseVerwaltung->findeAnhandVonId($_REQUEST['id']);
                $speise_id = $to_delete_obj->getId();
                $to_delete_menge_pro_portion = $menge_pro_portionVerwaltung->findeAnhandVonSpeiseId($speise_id);
                foreach ($to_delete_menge_pro_portion as $menge_pro_portion) {
                    $menge_pro_portionVerwaltung->loesche($menge_pro_portion);
                }
                $to_delete_bemerkung_zu_speise = $bemerkungzuspeiseVerwaltung->findeAlleZuSpeiseId($speise_id);
                foreach ($to_delete_bemerkung_zu_speise as $bemerkung_zu_speise) {
                    $bemerkungzuspeiseVerwaltung->loesche($bemerkung_zu_speise);
                }
                $to_delete_indi_faktoren = $indifaktorVerwaltung->findeAlleZuSpeiseId($speise_id);
                foreach ($to_delete_indi_faktoren as $indi_faktor) {
                    $indifaktorVerwaltung->loesche($indi_faktor);
                }
                $speiseVerwaltung->loesche($to_delete_obj);

                header('location:index.php?action=speisenverwaltung');
                break;
        }
        break;
    case 'kundenverwaltung':
        $sort = $_REQUEST['sort'];
        $kunden = $kundeVerwaltung->findeAlle($sort);
        $tourenden = $kundeVerwaltung->findeAlleTourenden($sort);
        break;
    case 'speisenverwaltung':
        $sort = $_REQUEST['sort'];
        $speisen = $speiseVerwaltung->findeAlle($sort);
        $fehler_in = pruefeAufFehlendeMengen($speisen, $speiseVerwaltung, $einrichtungskategorieVerwaltung, $menge_pro_portionVerwaltung);
        /* echo '<pre>';
          var_dump($fehler_in);
          echo '</pre>'; */
        break;

    case 'save_speisen_settings':
        $sort = $_REQUEST['sort'];
        $speisen = $speiseVerwaltung->findeAlle($sort);
        $zu_speichernde_speisen_ids = $_REQUEST['kalt_verpackt_speisen_ids'];
        $speisen_kalt_verpackt_vorhanden = $speiseVerwaltung->findeAlleKaltVerpackten();
        foreach ($speisen_kalt_verpackt_vorhanden as $speise_kverp) {
            if (array_search($speise_kverp->getId(), $zu_speichernde_speisen_ids) === false) {
                $speise_kverp->setKaltVerpackt(0);
                $speiseVerwaltung->speichere($speise_kverp);
            }
        }
        foreach ($zu_speichernde_speisen_ids as $speise_id) {
            $speise = $speiseVerwaltung->findeAnhandVonId($speise_id);
            $speise->setKaltVerpackt(1);
            $speiseVerwaltung->speichere($speise);
        }
        header('location:index.php?action=speisenverwaltung&sort=' . $sort);
        exit;
        break;
    case 'einrichtungskategorie_duplizieren':
        if ($_REQUEST['bezeichnung'] == '') {
            $_SESSION['fehler'] = 'Es wurde keine Bezeichnung angegeben.';
            header('location:index.php?action=einrichtungsverwaltung');
            exit;
        }
        $quell_kategorie = $einrichtungskategorieVerwaltung->findeAnhandVonId($_REQUEST['id']);
        $quell_mengen = $menge_pro_portionVerwaltung->findeAnhandVonEinrichtungskategorieId($quell_kategorie->getId());
        $neue_kategorie = new Einrichtungskategorie();
        $neue_kategorie->setBezeichnung($_REQUEST['bezeichnung']);
        $einrichtungskategorieVerwaltung->speichere($neue_kategorie);
        $neue_id = $neue_kategorie->getId();

        foreach ($quell_mengen as $quell_menge) {
            $neue_menge_pro_portion = new MengeProPortion();
            $neue_menge_pro_portion->setSpeiseId($quell_menge->getSpeiseId());
            $neue_menge_pro_portion->setEinrichtungskategorieId($neue_id);
            $neue_menge_pro_portion->setMenge($quell_menge->getMenge());
            $neue_menge_pro_portion->setEinheit($quell_menge->getEinheit());
            /* echo '<pre>';
              var_dump($neue_menge_pro_portion);
              echo '</pre>'; */
            $menge_pro_portionVerwaltung->speichere($neue_menge_pro_portion);
        }
        $_SESSION['fehler'] = 'Kategorie wurde dupliziert.';
        header('location:index.php?action=einrichtungsverwaltung');

        exit;
        break;
    case 'kopiere_speise':
        //BEMERKUNG ZU SPEISE DUPLIZIEREN
        $speise = $speiseVerwaltung->findeAnhandVonId($_REQUEST['speise_id']);
        $daten_neue_speise = array();
        $daten_neue_speise['bezeichnung'] = '_' . $speise->getBezeichnung();
        $daten_neue_speise['rezept'] = $speise->getRezept();
        $daten_neue_speise['kalt_verpackt'] = $speise->getKaltVerpackt();
        $neue_speise = new Speise($daten_neue_speise);

        $speiseVerwaltung->speichere($neue_speise);

        $vorhandene_bemerkungen = $bemerkungzuspeiseVerwaltung->findeAlleZuSpeiseId($speise->getId());
        foreach ($vorhandene_bemerkungen as $vorhandene_bemerkung) {
            $neue_bemerkung_zu_speise = new BemerkungZuSpeise();
            $neue_bemerkung_zu_speise->setKundeId($vorhandene_bemerkung->getKundeId());
            $neue_bemerkung_zu_speise->setSpeiseId($neue_speise->getId());
            $neue_bemerkung_zu_speise->setBemerkung($vorhandene_bemerkung->getBemerkung());
            $bemerkungzuspeiseVerwaltung->speichere($neue_bemerkung_zu_speise);
        }

        //  var_dump($daten_neue_speise, $neue_speise);
        /// var_dump($neue_speise);


        $menge_pro_portion_array = $menge_pro_portionVerwaltung->findeAnhandVonSpeiseId($speise->getId());
        foreach ($menge_pro_portion_array as $menge_pro_sgl) {
            $daten_neue_menge = array();
            $daten_neue_menge['speise_id'] = $neue_speise->getId();
            $daten_neue_menge['einrichtungskategorie_id'] = $menge_pro_sgl->getEinrichtungskategorieId();
            $daten_neue_menge['menge'] = $menge_pro_sgl->getMenge();
            $daten_neue_menge['einheit'] = $menge_pro_sgl->getEinheit();
            $neue_menge_pro_portion = new MengeProPortion($daten_neue_menge);
            $menge_pro_portionVerwaltung->speichere($neue_menge_pro_portion);
        }
        header('location:index.php?action=speisenverwaltung');
        //var_dump($menge_pro_portion);
        break;
    case 'speise_menge_festlegen':
        $speise = $speiseVerwaltung->findeAnhandVonId($_REQUEST['speise_id']);
        $kategorien = $einrichtungskategorieVerwaltung->findeAlle();
        break;
    case 'neue_speise_step2':
        $speise = new Speise($_REQUEST);
        $speiseVerwaltung->speichere($speise);
        $einrichtungskategorien = $einrichtungskategorieVerwaltung->findeAlle();
        break;
    case 'neue_speise_step3':
        $daten['speise_id'] = $_REQUEST['speise_id'];
        $daten['einheit'] = $_REQUEST['einheit'];
        $menge_pro_portion_array = array();

        for ($i = 0; $i < count($_REQUEST['menge']); $i++) {
            $daten['menge'] = $_REQUEST['menge'][$i];
            $daten['einrichtungskategorie_id'] = $_REQUEST['einrichtungskategorie_id'][$i];
            $menge_pro_portion_array[] = new MengeProPortion($daten);
        }

        foreach ($menge_pro_portion_array as $menge_pro_portion) {
            $menge_pro_portionVerwaltung->speichere($menge_pro_portion);
        }
        header('location:index.php?action=speisenverwaltung');
        //$menge_pro_portion = new MengeProPortion();
        break;
    case 'rezept':
        $id = $_REQUEST['speise_id'];
        $speise = $speiseVerwaltung->findeAnhandVonId($id);
        break;
    case 'rezept_speichern':
        $id = $_REQUEST['id'];
        $speise = $speiseVerwaltung->findeAnhandVonId($id);
        $speise->setRezept($_REQUEST['rezept']);
        $speiseVerwaltung->speichere($speise);
        header('location:index.php?action=speisenverwaltung');
        break;
    case 'lieferorganisation':
        if ($_REQUEST['do'] == 'save_edit_essenszeit') {
            $kunde = $kundeVerwaltung->findeAnhandVonId($_REQUEST['id']);
            $neue_essenszeit = $_REQUEST['essenszeit_h'] . $_REQUEST['essenszeit_m'];
            $kunde->setEssenszeit($neue_essenszeit);
            $kundeVerwaltung->speichere($kunde);
        }
        break;
    case 'einrichtungsverwaltung':
        $einrichtungskategorien = $einrichtungskategorieVerwaltung->findeAlle();
        $speisen = $speiseVerwaltung->findeAlle();
        $fehler = pruefeAufFehlendeMengen($speisen, $speiseVerwaltung, $einrichtungskategorieVerwaltung, $menge_pro_portionVerwaltung);
        break;
    case 'neue_einrichtung':
        $go = $_REQUEST['go'];
        $einrichtungskategorie = new Einrichtungskategorie($_REQUEST);
        $einrichtungskategorieVerwaltung->speichere($einrichtungskategorie);
        $speisen = $speiseVerwaltung->findeAlle();
        $kategorien = $einrichtungskategorieVerwaltung->findeAlle();
        if (count($speisen) == 0 && $_REQUEST['go'] == 'neuer_kunde') {
            header('location:index.php?action=neuer_kunde');
        } elseif (count($speisen) == 0) {
            header('location:index.php?action=einrichtungsverwaltung');
        }
        break;
    case 'neue_einrichtung_mengen':
        //$menge_pro_portion = new MengeProPortion();
        $anzahl = count($_REQUEST['menge']);
        for ($i = 0; $i < $anzahl; $i++) {
            $daten['speise_id'] = $_REQUEST['speise_id'][$i];
            $daten['einrichtungskategorie_id'] = $_REQUEST['einrichtungskategorie_id'][$i];
            $daten['menge'] = $_REQUEST['menge'][$i];
            $daten['einheit'] = $_REQUEST['einheit'][$i];
            $menge_pro_portion = new MengeProPortion($daten);
            $menge_pro_portionVerwaltung->speichere($menge_pro_portion);
        }
        if ($_REQUEST['go'] == 'neuer_kunde') {
            header('location:index.php?action=neuer_kunde');
        } else {
            header('location:index.php?action=einrichtungsverwaltung');
        }
        break;

    case 'cockpit':
        $kunden = array();
                    $tag_anzeigen = date('d');
                    $monat_anzeigen = date('m');
                    $jahr_anzeigen = date('Y');
        if ($_REQUEST['kids']) {
            pruefeAufStartdatumUndAktiviere($kundeVerwaltung);
            $_REQUEST['dev'] = 1;
            if (isset($_REQUEST['monat_anzeigen']) && isset($_REQUEST['jahr_anzeigen'])) {
                $tag_anzeigen = 1;
                $monat_anzeigen = $_REQUEST['monat_anzeigen'];
                $jahr_anzeigen = $_REQUEST['jahr_anzeigen'];
            } else {
                if (isset($_REQUEST['starttag']) && isset($_REQUEST['startmonat']) && isset($_REQUEST['startjahr'])) {
                    $tag_anzeigen = $_REQUEST['starttag'];
                    $monat_anzeigen = $_REQUEST['startmonat'];
                    $jahr_anzeigen = $_REQUEST['startjahr'];
                } else {
                    $tag_anzeigen = date('d');
                    $monat_anzeigen = date('m');
                    $jahr_anzeigen = date('Y');
                }
            }
            sprintf('%02d', $tag_anzeigen);
            sprintf('%02d', $monat_anzeigen);
            if (!isset($_REQUEST['woche_mit_start_anzeigen'])) {
                $_REQUEST['woche_mit_start_anzeigen'] = '';
            }
            $woche_anzeigen = $_REQUEST['woche_mit_start_anzeigen'];

            if ($_REQUEST['kids']) {
                $kunden_ids_arr = explode('-', $_REQUEST['kids']);
                $kunden = $kundeVerwaltung->findeAlleZuKidArray($kunden_ids_arr);
            }
        }
        break;

    case 'speisenwoche':


        $_REQUEST['dev'] = 1;



        if (isset($_REQUEST['monat_anzeigen']) && isset($_REQUEST['jahr_anzeigen'])) {
            $tag_anzeigen = 1;
            $monat_anzeigen = $_REQUEST['monat_anzeigen'];
            $jahr_anzeigen = $_REQUEST['jahr_anzeigen'];
        } else {
            if (isset($_REQUEST['starttag']) && isset($_REQUEST['startmonat']) && isset($_REQUEST['startjahr'])) {
                $tag_anzeigen = $_REQUEST['starttag'];
                $monat_anzeigen = $_REQUEST['startmonat'];
                $jahr_anzeigen = $_REQUEST['startjahr'];
            } else {
                $tag_anzeigen = date('d');
                $monat_anzeigen = date('m');
                $jahr_anzeigen = date('Y');
            }
        }


        sprintf('%02d', $tag_anzeigen);
        sprintf('%02d', $monat_anzeigen);

        if (!isset($_REQUEST['woche_mit_start_anzeigen'])) {
            $_REQUEST['woche_mit_start_anzeigen'] = '';
        }
        $woche_anzeigen = $_REQUEST['woche_mit_start_anzeigen'];
        // var_dump(strftime('%d.%m.%Y - %H:%M', $heute));
        //  $kunden = $kundeVerwaltung->findeAlleAktiven3();
        //  $kunden = $kundeVerwaltung->findeAlleAktivenLimit(20);
        break;

    case 'speiseplaene':

        pruefeAufStartdatumUndAktiviere($kundeVerwaltung);

        $_REQUEST['dev'] = 1;



        if (isset($_REQUEST['monat_anzeigen']) && isset($_REQUEST['jahr_anzeigen'])) {
            $tag_anzeigen = 1;
            $monat_anzeigen = $_REQUEST['monat_anzeigen'];
            $jahr_anzeigen = $_REQUEST['jahr_anzeigen'];
        } else {
            if (isset($_REQUEST['starttag']) && isset($_REQUEST['startmonat']) && isset($_REQUEST['startjahr'])) {
                $tag_anzeigen = $_REQUEST['starttag'];
                $monat_anzeigen = $_REQUEST['startmonat'];
                $jahr_anzeigen = $_REQUEST['startjahr'];
            } else {
                $tag_anzeigen = date('d');
                $monat_anzeigen = date('m');
                $jahr_anzeigen = date('Y');
            }
        }


        sprintf('%02d', $tag_anzeigen);
        sprintf('%02d', $monat_anzeigen);
        /*
          if (isset($_REQUEST['monat_anzeigen']) && ($_REQUEST['monat_anzeigen'] != (int)date('m'))) {
          $anzeige_tag = 1;
          } else {
          $anzeige_tag = date('d');
          }

          if (isset($_REQUEST['monat_anzeigen'])) {
          $anzeige_monat = $_REQUEST['monat_anzeigen'];
          } else {
          $anzeige_monat = date('m');
          }

          if (isset($_REQUEST['jahr_anzeigen'])) {
          $anzeige_jahr = $_REQUEST['jahr_anzeigen'];
          } else {
          $anzeige_jahr = date('Y');
          }

          if (isset($_REQUEST['jahr_anzeigen']) && isset($_REQUEST['monat_anzeigen'])) {
          $heute = mktime(0,0,0,$_REQUEST['monat_anzeigen'], 1, $_REQUEST['jahr_anzeigen']);
          } else {
          $heute = mktime(0,0,0,date('m'),date('d'),date('Y'));
          }
         */
//var_dump(date('I', $_REQUEST['woche_mit_start_anzeigen']));
        if (!isset($_REQUEST['woche_mit_start_anzeigen'])) {
            $_REQUEST['woche_mit_start_anzeigen'] = '';
        }
        $woche_anzeigen = $_REQUEST['woche_mit_start_anzeigen'];
        // var_dump(strftime('%d.%m.%Y - %H:%M', $heute));

        $kunden = $kundeVerwaltung->findeAlleAktiven3();
        //  $kunden = $kundeVerwaltung->findeAlleAktivenLimit(20);
        break;
    case 'bearbeite_tag':
        $ts = $_REQUEST['ts'];
        $tag = $_REQUEST['tag'];
        $monat = $_REQUEST['monat'];
        $jahr = $_REQUEST['jahr'];

        $starttag = $_REQUEST['starttag'];
        $startmonat = $_REQUEST['startmonat'];
        $startjahr = $_REQUEST['startjahr'];

        $speisen = $speiseVerwaltung->findeAlle();
        break;
    case 'bestellung_anlegen':
        $bestellung = new Bestellung($_REQUEST);
        $bestellungVerwaltung->speichere($bestellung);
        $bestellung_has_speiseVerwaltung->loescheAlleZuBestellung($bestellung->getId());
        $bestellungid = $bestellung->getId();
        $starttag = $_REQUEST['starttag'];

        $menunamen = $_REQUEST['menunamen'];
        $menunamen_intern = $_REQUEST['menunamen_intern'];
        $fahrer_extras = $_REQUEST['fahrer_extra'];
        foreach ($menunamen as $speise_nr => $menuname) {
            $menuname_intern = $menunamen_intern[$speise_nr];
            $fahrer_extra = $fahrer_extras[$speise_nr];
            if (!$menuname && !$menuname_intern && !$fahrer_extra) {
                continue;
            }
            $menuname_obj = $menunamenVerwaltung->findeAnhandVonTagMonatJahrSpeiseNr($_REQUEST['tag2'], $_REQUEST['monat'], $_REQUEST['jahr'], $speise_nr);
            $menuname_obj->setTag($_REQUEST['tag2']);
            $menuname_obj->setMonat($_REQUEST['monat']);
            $menuname_obj->setJahr($_REQUEST['jahr']);
            $menuname_obj->setBezeichnung($menuname);
            $menuname_obj->setBezeichnungIntern($menuname_intern);
            $menuname_obj->setSpeiseNr($speise_nr);
            $menuname_obj->setFahrerExtra($fahrer_extra);
            $menunamenVerwaltung->speichere($menuname_obj);
        }

        foreach ($_REQUEST['speise_ids'] as $speise_id) {
            if ($speise_id != 0) {
                $daten['bestellung_id'] = $bestellungid;
                $daten['speise_id'] = $speise_id;
                $daten['speise_nr'] = 1;

                //kitafino Speise 1 entspricht:
                switch ($_REQUEST['fleischgericht']) {
                    case 1:
                        $daten['kitafino_speise_nr'] = 1;
                        break;
                    case 2:
                        $daten['kitafino_speise_nr'] = 2;
                        break;
                }


                $bestellung_has_speise = new BestellungHasSpeise($daten);
                $bestellung_has_speiseVerwaltung->fuegeBestellungHasSpeiseHinzu($bestellung_has_speise);
            }
        }
        foreach ($_REQUEST['speise2_ids'] as $speise2_id) {
            if ($speise2_id != 0) {
                $daten['bestellung_id'] = $bestellungid;
                $daten['speise_id'] = $speise2_id;
                $daten['speise_nr'] = 2;

                //kitafino Speise 1 entspricht:
                switch ($_REQUEST['fleischgericht']) {
                    case 1:
                        $daten['kitafino_speise_nr'] = 2;
                        break;
                    case 2:
                        $daten['kitafino_speise_nr'] = 1;
                        break;
                }


                $bestellung_has_speise = new BestellungHasSpeise($daten);
                $bestellung_has_speiseVerwaltung->fuegeBestellungHasSpeiseHinzu($bestellung_has_speise);
            }
        }
        foreach ($_REQUEST['speise3_ids'] as $speise3_id) {
            if ($speise3_id != 0) {
                $daten['bestellung_id'] = $bestellungid;
                $daten['speise_id'] = $speise3_id;
                $daten['speise_nr'] = 3;
                $daten['kitafino_speise_nr'] = 3;



                $bestellung_has_speise = new BestellungHasSpeise($daten);
                $bestellung_has_speiseVerwaltung->fuegeBestellungHasSpeiseHinzu($bestellung_has_speise);
            }
        }

        foreach ($_REQUEST['speise4_ids'] as $speise4_id) {
            if ($speise4_id != 0) {
                $daten['bestellung_id'] = $bestellungid;
                $daten['speise_id'] = $speise4_id;
                $daten['speise_nr'] = 4;
                $daten['kitafino_speise_nr'] = 4;



                $bestellung_has_speise = new BestellungHasSpeise($daten);
                $bestellung_has_speiseVerwaltung->fuegeBestellungHasSpeiseHinzu($bestellung_has_speise);
            }
        }

        $ts = $_REQUEST['tag'];
        $kunden = $kundeVerwaltung->findeAlle();
        foreach ($kunden as $kunde) {
            $abrechnungstag_vorhanden = $abrechnungstagVerwaltung->findeAnhandVonKundeIdUndTagMonatJahr($kunde->getId(), $_REQUEST['tag2'], $_REQUEST['monat'], $_REQUEST['jahr'], 1);
            $abrechnungstag_vorhanden2 = $abrechnungstagVerwaltung->findeAnhandVonKundeIdUndTagMonatJahr($kunde->getId(), $_REQUEST['tag2'], $_REQUEST['monat'], $_REQUEST['jahr'], 2);
            $abrechnungstag_vorhanden3 = $abrechnungstagVerwaltung->findeAnhandVonKundeIdUndTagMonatJahr($kunde->getId(), $_REQUEST['tag2'], $_REQUEST['monat'], $_REQUEST['jahr'], 3);
            $abrechnungstag_vorhanden4 = $abrechnungstagVerwaltung->findeAnhandVonKundeIdUndTagMonatJahr($kunde->getId(), $_REQUEST['tag2'], $_REQUEST['monat'], $_REQUEST['jahr'], 4);

            if ($abrechnungstag_vorhanden->getId()) {
                $daten_abrng['id'] = $abrechnungstag_vorhanden->getId();
            } else {

            }
            if ($abrechnungstag_vorhanden2->getId()) {
                $speise2_abrechnung_id = $abrechnungstag_vorhanden2->getId();
            } else {
                $speise2_abrechnung_id = null;
            }

            if ($abrechnungstag_vorhanden3->getId()) {
                $speise3_abrechnung_id = $abrechnungstag_vorhanden3->getId();
            } else {
                $speise3_abrechnung_id = null;
            }


            if ($abrechnungstag_vorhanden4->getId()) {
                $speise4_abrechnung_id = $abrechnungstag_vorhanden4->getId();
            } else {
                $speise4_abrechnung_id = null;
            }


            $daten_abrng['kunde_id'] = $kunde->getId();
            $daten_abrng['tag'] = $ts;
            $tag2 = $daten_abrng['tag2'] = $_REQUEST['tag2'];

            $monat = $daten_abrng['monat'] = $_REQUEST['monat'];
            $jahr = $daten_abrng['jahr'] = $_REQUEST['jahr'];
            $daten_abrng['portionen'] = findePortionenZuDatumUndKunde($kunde->getId(), $tag2, $monat, $jahr, $kundeVerwaltung, $standardportionenVerwaltung, $portionenaenderungVerwaltung);

            if ($kunde->getAnzahlSpeisen() > 1 && $kunde->getBioKunde() == 0) {
                $speise2_portionen = findePortionenZuDatumUndKundeSpeise2($kunde->getId(), $tag2, $monat, $jahr, $kundeVerwaltung, $standardportionenVerwaltung, $portionenaenderungVerwaltung, 2);
            }
            if ($kunde->getBioKunde() || $kunde->getStaedtischerKunde()) {
                $speise3_portionen = findePortionenZuDatumUndKundeSpeise2($kunde->getId(), $tag2, $monat, $jahr, $kundeVerwaltung, $standardportionenVerwaltung, $portionenaenderungVerwaltung, 3);
            }
            if (($kunde->getBioKunde() && $kunde->getAnzahlSpeisen() > 1) || $kunde->getStaedtischerKunde()) {
                $speise4_portionen = findePortionenZuDatumUndKundeSpeise2($kunde->getId(), $tag2, $monat, $jahr, $kundeVerwaltung, $standardportionenVerwaltung, $portionenaenderungVerwaltung, 4);
            }
            $daten_abrng['speise_nr'] = 1;

            $_REQUEST['speise_ids'] = array_filter($_REQUEST['speise_ids']);
            $daten_abrng['speisen_ids'] = implode(', ', $_REQUEST['speise_ids']);

            $_REQUEST['speise2_ids'] = array_filter($_REQUEST['speise2_ids']);
            $daten_abrng['speisen2_ids'] = implode(', ', $_REQUEST['speise2_ids']);

            $_REQUEST['speise3_ids'] = array_filter($_REQUEST['speise3_ids']);
            $daten_abrng['speisen3_ids'] = implode(', ', $_REQUEST['speise3_ids']);

            $_REQUEST['speise4_ids'] = array_filter($_REQUEST['speise4_ids']);
            $daten_abrng['speisen4_ids'] = implode(', ', $_REQUEST['speise4_ids']);
            ///// ab hier speise 3 und 4 dev stop
            if ($kunde->getAktiv()) {
                $abrechnungstag = new Abrechnungstag($daten_abrng);
                if ($kunde->getAnzahlSpeisen() > 1 && $kunde->getBioKunde() == 0) {
                    $abrechnungstag2 = new Abrechnungstag();
                    $abrechnungstag2->setId($speise2_abrechnung_id);
                    $abrechnungstag2->setKundeId($kunde->getId());
                    $abrechnungstag2->setPortionen($speise2_portionen);
                    $abrechnungstag2->setSpeisenIds($daten_abrng['speisen2_ids']);
                    $abrechnungstag2->setTag2($_REQUEST['tag2']);
                    $abrechnungstag2->setMonat($_REQUEST['monat']);
                    $abrechnungstag2->setJahr($_REQUEST['jahr']);
                    $abrechnungstag2->setSpeiseNr(2);
                    $abrechnungstagVerwaltung->speichere($abrechnungstag2);
                }
                if ($kunde->getBioKunde() || $kunde->getStaedtischerKunde()) {
                    $abrechnungstag3 = new Abrechnungstag();
                    $abrechnungstag3->setId($speise3_abrechnung_id);
                    $abrechnungstag3->setKundeId($kunde->getId());
                    $abrechnungstag3->setPortionen($speise3_portionen);
                    $abrechnungstag3->setSpeisenIds($daten_abrng['speisen3_ids']);
                    $abrechnungstag3->setTag2($_REQUEST['tag2']);
                    $abrechnungstag3->setMonat($_REQUEST['monat']);
                    $abrechnungstag3->setJahr($_REQUEST['jahr']);
                    $abrechnungstag3->setSpeiseNr(3);
                    $abrechnungstagVerwaltung->speichere($abrechnungstag3);
                }
                if (($kunde->getBioKunde() && $kunde->getAnzahlSpeisen() > 1) || $kunde->getStaedtischerKunde()) {
                    $abrechnungstag4 = new Abrechnungstag();
                    $abrechnungstag4->setId($speise4_abrechnung_id);
                    $abrechnungstag4->setKundeId($kunde->getId());
                    $abrechnungstag4->setPortionen($speise4_portionen);
                    $abrechnungstag4->setSpeisenIds($daten_abrng['speisen4_ids']);
                    $abrechnungstag4->setTag2($_REQUEST['tag2']);
                    $abrechnungstag4->setMonat($_REQUEST['monat']);
                    $abrechnungstag4->setJahr($_REQUEST['jahr']);
                    $abrechnungstag4->setSpeiseNr(4);
                    $abrechnungstagVerwaltung->speichere($abrechnungstag4);
                }
                $abrechnungstagVerwaltung->speichere($abrechnungstag);
            }
        }

        $starttag = $_REQUEST['starttag'];
        $startmonat = $_REQUEST['startmonat'];
        $startjahr = $_REQUEST['startjahr'];
        header("location:index.php?action=uebersicht_tag&tag2=$tag2&monat=$monat&jahr=$jahr&ts=$ts&starttag=$starttag&startmonat=$startmonat&startjahr=$startjahr");
        break;
    case 'uebersicht_tag':

        $ts = $_REQUEST['ts'];
        $ts_search = $ts;

        $tag = $_REQUEST['tag2'];
        $monat = $_REQUEST['monat'];
        $jahr = $_REQUEST['jahr'];

        $starttag = $_REQUEST['starttag'];
        $startmonat = $_REQUEST['startmonat'];
        $startjahr = $_REQUEST['startjahr'];

        $wochenstarttag = $_REQUEST['starttag'];
        $speisen = $speiseVerwaltung->findeAlle();
        $kunden = $kundeVerwaltung->findeAlleAktiven();
        $bestellungen = $bestellungVerwaltung->findeAlleZuDatum($tag, $monat, $jahr);
        break;
    case 'speichere_portionenaenderung':

        echo '<pre>';
        var_dump($_REQUEST);
        echo '</pre>';
        exit;



        ///NEU ENDE
        exit;
        ///ALT
        $c = 0;
        ini_set('max_input_vars', 3000);
        $test = $_REQUEST['portionenaenderung_id'];
        foreach ($_REQUEST['portionenaenderung_id'] as $id) {
            $daten['id'] = $id;
            //echo $_REQUEST['kunde_id'][$c].'<br />';
            $daten4['kunde_id'] = $daten3['kunde_id'] = $daten2['kunde_id'] = $daten['kunde_id'] = $_REQUEST['kunde_id'][$c];
            $kunde_id = $daten['kunde_id'];
            $kunde = $kundeVerwaltung->findeAnhandVonId($kunde_id);

            if (!$kunde->getBioKunde() && !$kunde->getStaedtischerKunde()) {
                $daten['portionen_mo'] = $_REQUEST['portionen_mo'][$c];
                $daten['portionen_di'] = $_REQUEST['portionen_di'][$c];
                $daten['portionen_mi'] = $_REQUEST['portionen_mi'][$c];
                $daten['portionen_do'] = $_REQUEST['portionen_do'][$c];
                $daten['portionen_fr'] = $_REQUEST['portionen_fr'][$c];
            }

            if ($kunde->getAnzahlSpeisen() > 1 && $kunde->getBioKunde() == 0) {
                $daten2['portionen_mo'] = $_REQUEST['portionen2_mo'][$c];
                $daten2['portionen_di'] = $_REQUEST['portionen2_di'][$c];
                $daten2['portionen_mi'] = $_REQUEST['portionen2_mi'][$c];
                $daten2['portionen_do'] = $_REQUEST['portionen2_do'][$c];
                $daten2['portionen_fr'] = $_REQUEST['portionen2_fr'][$c];
            }
            if ($kunde->getBioKunde() || $kunde->getStaedtischerKunde()) {
                $daten3['portionen_mo'] = $_REQUEST['portionen3_mo'][$c];
                $daten3['portionen_di'] = $_REQUEST['portionen3_di'][$c];
                $daten3['portionen_mi'] = $_REQUEST['portionen3_mi'][$c];
                $daten3['portionen_do'] = $_REQUEST['portionen3_do'][$c];
                $daten3['portionen_fr'] = $_REQUEST['portionen3_fr'][$c];
            }
            if (($kunde->getBioKunde() && $kunde->getAnzahlSpeisen() > 1) || $kunde->getStaedtischerKunde()) {
                $daten4['portionen_mo'] = $_REQUEST['portionen4_mo'][$c];
                $daten4['portionen_di'] = $_REQUEST['portionen4_di'][$c];
                $daten4['portionen_mi'] = $_REQUEST['portionen4_mi'][$c];
                $daten4['portionen_do'] = $_REQUEST['portionen4_do'][$c];
                $daten4['portionen_fr'] = $_REQUEST['portionen4_fr'][$c];
            }


            $daten4['starttag'] = $daten3['starttag'] = $daten2['starttag'] = $daten['starttag'] = $_REQUEST['starttag'][$c];
            $daten4['startmonat'] = $daten3['startmonat'] = $daten2['startmonat'] = $daten['startmonat'] = $_REQUEST['startmonat'][$c];
            $daten4['startjahr'] = $daten3['startjahr'] = $daten2['startjahr'] = $daten['startjahr'] = $_REQUEST['startjahr'][$c];
            $daten2['speise_nr'] = 2;
            $daten3['speise_nr'] = 3;
            $daten4['speise_nr'] = 4;

            $daten2['id'] = $_REQUEST['portionenaenderung2_id'][$c];
            $daten3['id'] = $_REQUEST['portionenaenderung3_id'][$c];
            $daten4['id'] = $_REQUEST['portionenaenderung4_id'][$c];

            $daten4['wochenstarttagts'] = $daten3['wochenstarttagts'] = $daten2['wochenstarttagts'] = $daten['wochenstarttagts'] = mktime(12, 0, 0, $_REQUEST['startmonat'][$c], $_REQUEST['starttag'][$c], $_REQUEST['startjahr'][$c]);
            $wochenstarttagts = $daten['wochenstarttagts'];
            $portionenaenderung = new Portionenaenderung($daten);
            $portionenaenderung2 = new Portionenaenderung($daten2);
            $portionenaenderung3 = new Portionenaenderung($daten3);
            $portionenaenderung4 = new Portionenaenderung($daten4);


//            var_dump($portionenaenderung);

            $tage = array();
            /*
              $tage[] = $wochenstarttagts;
              $tage[] = $wochenstarttagts+86400;
              $tage[] = $wochenstarttagts+86400*2;
              $tage[] = $wochenstarttagts+86400*3;
              $tage[] = $wochenstarttagts+86400*4; */

            $tage[] = array($daten['starttag'], $daten['startmonat'], $daten['startjahr']);
            $tage[] = array(date('d', $wochenstarttagts + 86400), date('m', $wochenstarttagts + 86400), date('Y', $wochenstarttagts + 86400));
            $tage[] = array(date('d', $wochenstarttagts + 86400 * 2), date('m', $wochenstarttagts + 86400 * 2), date('Y', $wochenstarttagts + 86400 * 2));
            $tage[] = array(date('d', $wochenstarttagts + 86400 * 3), date('m', $wochenstarttagts + 86400 * 3), date('Y', $wochenstarttagts + 86400 * 3));
            $tage[] = array(date('d', $wochenstarttagts + 86400 * 4), date('m', $wochenstarttagts + 86400 * 4), date('Y', $wochenstarttagts + 86400 * 4));
//SPEISE 3 und 4 bis HIER DEV
            $i = 1;
            foreach ($tage as $tag) {
                $abrechnungstag_vorhanden2 = new Abrechnungstag();
                $abrechnungstag_vorhanden = $abrechnungstagVerwaltung->findeAnhandVonKundeIdUndTagMonatJahr($kunde_id, $tag[0], $tag[1], $tag[2]);

                if ($kunde->getAnzahlSpeisen() > 1) {
                    $abrechnungstag_vorhanden2 = $abrechnungstagVerwaltung->findeAnhandVonKundeIdUndTagMonatJahr($kunde_id, $tag[0], $tag[1], $tag[2], 2);
                }
                switch ($i) {
                    case 1:
                        $abrechnungstag_vorhanden->setPortionen($portionenaenderung->getPortionenMo());

                        if ($kunde->getAnzahlSpeisen() > 1 && $kunde->getBioKunde() == 0) {
                            $abrechnungstag_vorhanden2->setPortionen($portionenaenderung2->getPortionenMo());
                        }
                        if ($kunde->getBioKunde() || $kunde->getStaedtischerKunde()) {
                            $abrechnungstag_vorhanden3->setPortionen($portionenaenderung3->getPortionenMo());
                        }
                        if (($kunde->getBioKunde() && $kunde->getAnzahlSpeisen() > 1) || $kunde->getStaedtischerKunde()) {
                            $abrechnungstag_vorhanden4->setPortionen($portionenaenderung4->getPortionenMo());
                        }

                        break;
                    case 2:
                        $abrechnungstag_vorhanden->setPortionen($portionenaenderung->getPortionenDi());
                        if ($kunde->getAnzahlSpeisen() > 1 && $kunde->getBioKunde() == 0) {
                            $abrechnungstag_vorhanden2->setPortionen($portionenaenderung2->getPortionenDi());
                        }
                        if ($kunde->getBioKunde() || $kunde->getStaedtischerKunde()) {
                            $abrechnungstag_vorhanden3->setPortionen($portionenaenderung3->getPortionenDi());
                        }
                        if (($kunde->getBioKunde() && $kunde->getAnzahlSpeisen() > 1) || $kunde->getStaedtischerKunde()) {
                            $abrechnungstag_vorhanden4->setPortionen($portionenaenderung4->getPortionenDi());
                        }
                        break;
                    case 3:
                        $abrechnungstag_vorhanden->setPortionen($portionenaenderung->getPortionenMi());
                        if ($kunde->getAnzahlSpeisen() > 1 && $kunde->getBioKunde() == 0) {
                            $abrechnungstag_vorhanden2->setPortionen($portionenaenderung2->getPortionenMi());
                        }
                        if ($kunde->getBioKunde() || $kunde->getStaedtischerKunde()) {
                            $abrechnungstag_vorhanden3->setPortionen($portionenaenderung3->getPortionenMi());
                        }
                        if (($kunde->getBioKunde() && $kunde->getAnzahlSpeisen() > 1) || $kunde->getStaedtischerKunde()) {
                            $abrechnungstag_vorhanden4->setPortionen($portionenaenderung4->getPortionenMi());
                        }
                        break;
                    case 4:
                        $abrechnungstag_vorhanden->setPortionen($portionenaenderung->getPortionenDo());
                        if ($kunde->getAnzahlSpeisen() > 1 && $kunde->getBioKunde() == 0) {
                            $abrechnungstag_vorhanden2->setPortionen($portionenaenderung2->getPortionenDo());
                        }
                        if ($kunde->getBioKunde() || $kunde->getStaedtischerKunde()) {
                            $abrechnungstag_vorhanden3->setPortionen($portionenaenderung3->getPortionenDo());
                        }
                        if (($kunde->getBioKunde() && $kunde->getAnzahlSpeisen() > 1) || $kunde->getStaedtischerKunde()) {
                            $abrechnungstag_vorhanden4->setPortionen($portionenaenderung4->getPortionenDo());
                        }
                        break;
                    case 5:
                        $abrechnungstag_vorhanden->setPortionen($portionenaenderung->getPortionenFr());
                        if ($kunde->getAnzahlSpeisen() > 1 && $kunde->getBioKunde() == 0) {
                            $abrechnungstag_vorhanden2->setPortionen($portionenaenderung2->getPortionenFr());
                        }
                        if ($kunde->getBioKunde() || $kunde->getStaedtischerKunde()) {
                            $abrechnungstag_vorhanden3->setPortionen($portionenaenderung3->getPortionenFr());
                        }
                        if (($kunde->getBioKunde() && $kunde->getAnzahlSpeisen() > 1) || $kunde->getStaedtischerKunde()) {
                            $abrechnungstag_vorhanden4->setPortionen($portionenaenderung4->getPortionenFr());
                        }
                        break;
                }
                $i++;
                if ($abrechnungstag_vorhanden->getId()) {
                    $abrechnungstagVerwaltung->speichere($abrechnungstag_vorhanden);
                    if ($kunde->getAnzahlSpeisen() > 1) {
                        //var_dump($kunde_id, $tag[0], $tag[1], $tag[2]);
                        if ($abrechnungstag_vorhanden2->getId()) {
                            $abrechnungstagVerwaltung->aendereAbrechnungstag($abrechnungstag_vorhanden2);
                        } else {
                            $abrechnungstag_vorhanden2->setKundeId($kunde_id);
                            $abrechnungstag_vorhanden2->setTag(mktime(12, 0, 0, $tag[1], $tag[0], $tag[2]));
                            $abrechnungstag_vorhanden2->setSpeisenIds($kunde_id);
                            $abrechnungstag_vorhanden2->setTag2($tag[0]);
                            $abrechnungstag_vorhanden2->setMonat($tag[1]);
                            $abrechnungstag_vorhanden2->setJahr($tag[2]);
                            $abrechnungstag_vorhanden2->setSpeiseNr(2);
                            $abrechnungstagVerwaltung->fuegeAbrechnungstagHinzu($abrechnungstag_vorhanden2);
                        }
                    }
                }
            }

            $portionenaenderungVerwaltung->speichere($portionenaenderung);
            $portionenaenderungVerwaltung->speichere($portionenaenderung2);
            $portionenaenderungVerwaltung->speichere($portionenaenderung3);
            $portionenaenderungVerwaltung->speichere($portionenaenderung4);

            $c++;
        }

        header('location:index.php?action=speiseplaene&starttag=' . $daten['starttag'] . '&startmonat=' . $daten['startmonat'] . '&startjahr=' . $daten['startjahr']);
        exit;
        break;
    case 'menge_pro_portion_fehler':
        $speisen = $speiseVerwaltung->findeAlle();
        break;case 'erzeuge_tagesaufstellung_xls_test':
        $tag = $_REQUEST['tag'];
        $monat = $_REQUEST['monat'];
        $jahr = $_REQUEST['jahr'];

        $starttag = $_REQUEST['starttag'];
        $startmonat = $_REQUEST['startmonat'];
        $startjahr = $_REQUEST['startjahr'];


        $bestellungen = $bestellungVerwaltung->findeAlleZuDatum($tag, $monat, $jahr);
        $speise_id = $_REQUEST['speise_id'];
        $s_nr = $_REQUEST['speise_nr'];
        $kunden = $kundeVerwaltung->findeAlleAktivenZuSpeiseNr($s_nr);

        if (($s_nr == 3 || $s_nr == 4) && $_REQUEST['test_prodsort']) {
            //kunden mit produktionsreihenfolge erstellen
            $kunden_touren = $kundeVerwaltung->findeAlleTouren('produktionsreihenfolge');
            $kunden = array();
            foreach ($kunden_touren as $k_tour) {
                $kunden_zu_tour = array();
                $kunden_zu_tour = $kundeVerwaltung->findeAlleAktivenZuSpeiseNrUndTourIdProduktion($s_nr, $k_tour->getId());
                if (count($kunden_zu_tour)) {
                    $kunden[] = $k_tour;
                    $kunden = array_merge($kunden, $kunden_zu_tour);
                }
            }
            /* echo '<pre>';
              var_dump($kunden);
              echo '</pre>'; */
        }
        /* echo '<pre>';
          var_dump($kunden);
          echo '</pre>'; */
        foreach ($bestellungen as $bestellung) {
            $bestellung_has_speise = $bestellung_has_speiseVerwaltung->findeAnhandVonBestellungIdUndSpeiseId($bestellung->getId(), $speise_id);
            if ($bestellung_has_speise->getSpeiseId() == $speise_id) {

                erzeugeTagesaufstellungExcelMitDeckblatt($tag, $monat, $jahr, $starttag, $startmonat, $startjahr, $speise_id, $bestellung, $bestellung_has_speiseVerwaltung, $speiseVerwaltung, $menge_pro_portionVerwaltung, $standardportionenVerwaltung, $portionenaenderungVerwaltung, $kunden, $indifaktorVerwaltung, $bemerkungzutagVerwaltung, $bemerkungzuspeiseVerwaltung, $_REQUEST['speise_nr'], $kundeVerwaltung, $color_speisen);
            }
        }
        break;
    case 'erzeuge_tagesaufstellung_xls_test_v2':
        $tag = $_REQUEST['tag'];
        $monat = $_REQUEST['monat'];
        $jahr = $_REQUEST['jahr'];

        $starttag = $_REQUEST['starttag'];
        $startmonat = $_REQUEST['startmonat'];
        $startjahr = $_REQUEST['startjahr'];


        $bestellungen = $bestellungVerwaltung->findeAlleZuDatum($tag, $monat, $jahr);
        $speise_id = $_REQUEST['speise_id'];
        $s_nr = $_REQUEST['speise_nr'];
        $kunden = $kundeVerwaltung->findeAlleAktivenZuSpeiseNr($s_nr);

        if (($s_nr == 3 || $s_nr == 4) && $_REQUEST['test_prodsort']) {
            //kunden mit produktionsreihenfolge erstellen
            $kunden_touren = $kundeVerwaltung->findeAlleTouren('produktionsreihenfolge');
            $kunden = array();
            foreach ($kunden_touren as $k_tour) {
                $kunden_zu_tour = array();
                $kunden_zu_tour = $kundeVerwaltung->findeAlleAktivenZuSpeiseNrUndTourIdProduktion($s_nr, $k_tour->getId());
                if (count($kunden_zu_tour)) {
                    $kunden[] = $k_tour;
                    $kunden = array_merge($kunden, $kunden_zu_tour);
                }
            }
            /* echo '<pre>';
              var_dump($kunden);
              echo '</pre>'; */
        }
        /* echo '<pre>';
          var_dump($kunden);
          echo '</pre>'; */
        foreach ($bestellungen as $bestellung) {
            $bestellung_has_speise = $bestellung_has_speiseVerwaltung->findeAnhandVonBestellungIdUndSpeiseId($bestellung->getId(), $speise_id);
            if ($bestellung_has_speise->getSpeiseId() == $speise_id) {

                erzeugeTagesaufstellungExcelMitDeckblatt($tag, $monat, $jahr, $starttag, $startmonat, $startjahr, $speise_id, $bestellung, $bestellung_has_speiseVerwaltung, $speiseVerwaltung, $menge_pro_portionVerwaltung, $standardportionenVerwaltung, $portionenaenderungVerwaltung, $kunden, $indifaktorVerwaltung, $bemerkungzutagVerwaltung, $bemerkungzuspeiseVerwaltung, $_REQUEST['speise_nr'], $kundeVerwaltung, $color_speisen);
            }
        }
        break;
    case 'erzeuge_tagesaufstellung_xls':
        $tag = $_REQUEST['tag'];
        $monat = $_REQUEST['monat'];
        $jahr = $_REQUEST['jahr'];

        $starttag = $_REQUEST['starttag'];
        $startmonat = $_REQUEST['startmonat'];
        $startjahr = $_REQUEST['startjahr'];

        $bestellungen = $bestellungVerwaltung->findeAlleZuDatum($tag, $monat, $jahr);
        $speise_id = $_REQUEST['speise_id'];
        $s_nr = $_REQUEST['speise_nr'];
        $kunden = $kundeVerwaltung->findeAlleAktivenZuSpeiseNr($s_nr);

        if (($s_nr == 3 || $s_nr == 4) && $_REQUEST['test_prodsort']) {
            //kunden mit produktionsreihenfolge erstellen
            $kunden_touren = $kundeVerwaltung->findeAlleTouren('produktionsreihenfolge');
            $kunden = array();
            foreach ($kunden_touren as $k_tour) {
                $kunden_zu_tour = array();
                $kunden_zu_tour = $kundeVerwaltung->findeAlleAktivenZuSpeiseNrUndTourIdProduktion($s_nr, $k_tour->getId());
                if (count($kunden_zu_tour)) {
                    $kunden[] = $k_tour;
                    $kunden = array_merge($kunden, $kunden_zu_tour);
                }
            }
            /* echo '<pre>';
              var_dump($kunden);
              echo '</pre>'; */
        }
        /* echo '<pre>';
          var_dump($kunden);
          echo '</pre>'; */
        foreach ($bestellungen as $bestellung) {
            $bestellung_has_speise = $bestellung_has_speiseVerwaltung->findeAnhandVonBestellungIdUndSpeiseId($bestellung->getId(), $speise_id);
            if ($bestellung_has_speise->getSpeiseId() == $speise_id) {

                erzeugeTagesaufstellungExcel($tag, $monat, $jahr, $starttag, $startmonat, $startjahr, $speise_id, $bestellung, $bestellung_has_speiseVerwaltung, $speiseVerwaltung, $menge_pro_portionVerwaltung, $standardportionenVerwaltung, $portionenaenderungVerwaltung, $kunden, $indifaktorVerwaltung, $bemerkungzutagVerwaltung, $bemerkungzuspeiseVerwaltung, $_REQUEST['speise_nr'], $kundeVerwaltung, $color_speisen);
            }
        }
        break;

    case 'erzeuge_tagesaufstellung_pdf_etiketten':
        //PDF ETIKETTEN
        $tag = $_REQUEST['tag'];
        $monat = $_REQUEST['monat'];
        $jahr = $_REQUEST['jahr'];

        $starttag = $_REQUEST['starttag'];
        $startmonat = $_REQUEST['startmonat'];
        $startjahr = $_REQUEST['startjahr'];

        $bestellungen = $bestellungVerwaltung->findeAlleZuDatum($tag, $monat, $jahr);

        $speise_id = $_REQUEST['speise_id'];
        // $kunden = $kundeVerwaltung->findeAlleAktiven4();
        $speise_nr_request = $_REQUEST['spnr'];
        $kunden = $kundeVerwaltung->findeAlleAktivenZuSpeiseNr($speise_nr_request);

        if (($speise_nr_request == 3 || $speise_nr_request == 4) && $_REQUEST['test_prodsort']) {
            //kunden mit produktionsreihenfolge erstellen
            $kunden_touren = $kundeVerwaltung->findeAlleTouren('produktionsreihenfolge');
            $kunden = array();
            foreach ($kunden_touren as $k_tour) {
                $kunden_zu_tour = array();
                $kunden_zu_tour = $kundeVerwaltung->findeAlleAktivenZuSpeiseNrUndTourIdProduktion($s_nr, $k_tour->getId());
                if (count($kunden_zu_tour)) {
                    $kunden[] = $k_tour;
                    $kunden = array_merge($kunden, $kunden_zu_tour);
                }
            }
        }

        foreach ($bestellungen as $bestellung) {
            //    $bestellung_has_speise = $bestellung_has_speiseVerwaltung->findeAnhandVonBestellungIdUndSpeiseId($bestellung->getId(), $speise_id);
            $bestellung_has_speise = $bestellung_has_speiseVerwaltung->findeAnhandVonBestellungIdUndSpeiseIdUndSpeiseNr($bestellung->getId(), $speise_id, $speise_nr_request);

            if ($bestellung_has_speise->getSpeiseId() == $speise_id) {
                erzeugeTagesaufstellungPdfEtikettendruck($tag, $monat, $jahr, $starttag, $startmonat, $startjahr, $speise_id, $bestellung, $bestellung_has_speiseVerwaltung, $speiseVerwaltung, $menge_pro_portionVerwaltung, $standardportionenVerwaltung, $portionenaenderungVerwaltung, $kunden, $indifaktorVerwaltung, $bemerkungzutagVerwaltung, $bemerkungzuspeiseVerwaltung, $bestellung_has_speise->getSpeiseNr(), $kundeVerwaltung);
            }
        }
        //PDF ETIKETTEN
        break;

    case 'erzeuge_tagesaufstellung_xls_etikettendruck':
        $tag = $_REQUEST['tag'];
        $monat = $_REQUEST['monat'];
        $jahr = $_REQUEST['jahr'];

        $starttag = $_REQUEST['starttag'];
        $startmonat = $_REQUEST['startmonat'];
        $startjahr = $_REQUEST['startjahr'];

        $bestellungen = $bestellungVerwaltung->findeAlleZuDatum($tag, $monat, $jahr);

        $speise_id = $_REQUEST['speise_id'];
        // $kunden = $kundeVerwaltung->findeAlleAktiven4();
        $speise_nr_request = $_REQUEST['spnr'];
        $kunden = $kundeVerwaltung->findeAlleAktivenZuSpeiseNr($speise_nr_request);

        if (($speise_nr_request == 3 || $speise_nr_request == 4) && $_REQUEST['test_prodsort']) {
            //kunden mit produktionsreihenfolge erstellen
            $kunden_touren = $kundeVerwaltung->findeAlleTouren('produktionsreihenfolge');
            $kunden = array();
            foreach ($kunden_touren as $k_tour) {
                $kunden_zu_tour = array();
                $kunden_zu_tour = $kundeVerwaltung->findeAlleAktivenZuSpeiseNrUndTourIdProduktion($s_nr, $k_tour->getId());
                if (count($kunden_zu_tour)) {
                    $kunden[] = $k_tour;
                    $kunden = array_merge($kunden, $kunden_zu_tour);
                }
            }
        }

        $kunden = array_reverse($kunden);
        foreach ($bestellungen as $bestellung) {
            //    $bestellung_has_speise = $bestellung_has_speiseVerwaltung->findeAnhandVonBestellungIdUndSpeiseId($bestellung->getId(), $speise_id);
            $bestellung_has_speise = $bestellung_has_speiseVerwaltung->findeAnhandVonBestellungIdUndSpeiseIdUndSpeiseNr($bestellung->getId(), $speise_id, $speise_nr_request);

            if ($bestellung_has_speise->getSpeiseId() == $speise_id) {
                erzeugeTagesaufstellungExcelEtikettendruck($tag, $monat, $jahr, $starttag, $startmonat, $startjahr, $speise_id, $bestellung, $bestellung_has_speiseVerwaltung, $speiseVerwaltung, $menge_pro_portionVerwaltung, $standardportionenVerwaltung, $portionenaenderungVerwaltung, $kunden, $indifaktorVerwaltung, $bemerkungzutagVerwaltung, $bemerkungzuspeiseVerwaltung, $bestellung_has_speise->getSpeiseNr(), $kundeVerwaltung);
            }
        }
        break;



    case 'erzeuge_einrichtungsliste_xls':
        $tag = $_REQUEST['tag'];
        $monat = $_REQUEST['monat'];
        $jahr = $_REQUEST['jahr'];

        $starttag = $_REQUEST['starttag'];
        $startmonat = $_REQUEST['startmonat'];
        $startjahr = $_REQUEST['startjahr'];

        $speise_nr = 1;
        if (isset($_REQUEST['speise_nr'])) {
            $speise_nr = $_REQUEST['speise_nr'];
        }
        /*  if ($speise_nr > 1) {
          $kunden = $kundeVerwaltung->findeAlleAktiven4ZuSpeiseNr('', $speise_nr);
          } else {
          $kunden = $kundeVerwaltung->findeAlleAktiven4();
          } */

        //  $kunden = $kundeVerwaltung->findeAlleAktiven4();


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

        $bestellungen = $bestellungVerwaltung->findeAlleZuDatum($tag, $monat, $jahr);
        $speise_id = $_REQUEST['speise_id'];

        //erzeugeEinrichtungslisteZuSpeiseNr($kundeVerwaltung, $tag, $monat, $jahr, $starttag, $startmonat, $startjahr, $speise_id, $bestellung, $bestellung_has_speiseVerwaltung, $speiseVerwaltung, $menge_pro_portionVerwaltung, $standardportionenVerwaltung, $portionenaenderungVerwaltung, $kunden, $indifaktorVerwaltung, $bemerkungzutagVerwaltung, $bemerkungzuspeiseVerwaltung, $speise_nr);

        foreach ($bestellungen as $bestellung) {
            $bestellung_has_speise = $bestellung_has_speiseVerwaltung->findeAnhandVonBestellungIdUndSpeiseId($bestellung->getId(), $speise_id);

            // if ($bestellung_has_speise->getSpeiseId() == $speise_id) {
            erzeugeEinrichtungslisteZuSpeiseNr($kundeVerwaltung, $tag, $monat, $jahr, $starttag, $startmonat, $startjahr, $speise_id, $bestellung, $bestellung_has_speiseVerwaltung, $speiseVerwaltung, $menge_pro_portionVerwaltung, $standardportionenVerwaltung, $portionenaenderungVerwaltung, $kunden, $indifaktorVerwaltung, $bemerkungzutagVerwaltung, $bemerkungzuspeiseVerwaltung, $speise_nr);
            //}
        }
        break;
    case 'erzeuge_statistik_excel_stadt_nbg':
        if (isset($_REQUEST['monat']) && isset($_REQUEST['jahr'])) {

        }
        $abrechnungs_daten = $daten_abrechnung = erstelleAbrechnungsDatenNbg($_REQUEST, $kundeVerwaltung, $abrechnungstagVerwaltung, $menunamenVerwaltung, $rechnungsadresseVerwaltung);
        //daten in excel form
        $excel_daten = array();
        $spalten = array(
            'Einrichtung',
            'RE-Nr.',
            //'Preis',
            'Anzahl'/* ,
                  'Betrag' */
        );

        $c = 0;
        foreach ($daten_abrechnung as $kunden_id => $daten) {
            if (!is_numeric($kunden_id)) {
                continue;
            }
            $kunden_daten = $daten['kunden_daten'];
            $monat_str = $abrechnungs_daten['leistungsmonat'];
            $re_nummer = 'N-' . str_replace('/', '', $monat_str) . '-' . $kunden_id;
            $portionen_gesamt_monat = $abrechnungs_daten[$kunden_id]['monat_gesamt_portionen'];
            $preis_pro_essen = $kunden_daten['preis'];
            $posten_string = $kunden_daten['name'] . ' (' . $kunden_daten['einrichtungsart'] . ')';
            $gesamt_kosten_monat = $portionen_gesamt_monat * $preis_pro_essen;

            $gesamt_kosten_monat_brutto = $gesamt_kosten_monat * 1.07;
            $excel_daten[$c] = array(
                $posten_string,
                $re_nummer,
                //$preis_pro_essen,
                $portionen_gesamt_monat/* ,
                      $gesamt_kosten_monat_brutto */
            );


            $c++;
        }
        /* echo '<pre>';
          var_dump($spalten, $excel_daten);
          echo '</pre>'; */

        $dateiname = 'StadtNBG_Statistik_' . str_replace('/', '_', $monat_str);

        erzeugeExcel($spalten, $excel_daten, '', $dateiname, 'none', 'none');
        break;
    case 'erzeuge_abrechnung_stadt_nbg':
        if (isset($_REQUEST['monat']) && isset($_REQUEST['jahr'])) {

        }
        $daten_abrechnung = erstelleAbrechnungsDatenNbg($_REQUEST, $kundeVerwaltung, $abrechnungstagVerwaltung, $menunamenVerwaltung, $rechnungsadresseVerwaltung);
        erzeugeAbrechnungStadt($daten_abrechnung);
        break;
    case 'erzeuge_lieferscheine_direkt_do':
        $_REQUEST['tag'] = date('d');
        $_REQUEST['monat'] = date('m');
        $_REQUEST['jahr'] = date('Y');
        $daten_abrechnung = erstelleAbrechnungsDatenNbg($_REQUEST, $kundeVerwaltung, $abrechnungstagVerwaltung, $menunamenVerwaltung, $rechnungsadresseVerwaltung);
$qr_code = false;
		if ($_REQUEST['qr'] == 1) {
			$qr_code = true;
		}
        erzeugeLieferscheine($_REQUEST, $daten_abrechnung, $kundeVerwaltung, $speiseVerwaltung, $menge_pro_portionVerwaltung, $indifaktorVerwaltung, $qr_code);
        break;
    case 'erzeuge_lieferscheine':
        $daten_abrechnung = erstelleAbrechnungsDatenNbg($_REQUEST, $kundeVerwaltung, $abrechnungstagVerwaltung, $menunamenVerwaltung, $rechnungsadresseVerwaltung);
			$qr_code = false;
		if ($_REQUEST['qr'] == 1) {
			$qr_code = true;
		}
        erzeugeLieferscheine($_REQUEST, $daten_abrechnung, $kundeVerwaltung, $speiseVerwaltung, $menge_pro_portionVerwaltung, $indifaktorVerwaltung, $qr_code);
        break;
    case 'erzeuge_abrechnung_xls':
        $monat = $_REQUEST['monat'];
        $jahr = $_REQUEST['jahr'];
        $kunde_id = $_REQUEST['kunde_id'];
        erzeugeAbrechnungExcel($monat, $jahr, $kunde_id, $kundeVerwaltung, $abrechnungstagVerwaltung, $speiseVerwaltung);
        break;

    case 'erzeuge_abrechnungs_uebersicht':
        //Abrechnungs XLs erstellen
        $monat = $_REQUEST['monat'];
        $jahr = $_REQUEST['jahr'];
        erzeugeAbrechnungUebersichtExcel($_SESSION['uebersicht_abrechnung'], $monat, $jahr);

        break;
    case 'erzeuge_tagesmengenaufstellung':
        $tag = $_REQUEST['tag'];
        $monat = $_REQUEST['monat'];
        $jahr = $_REQUEST['jahr'];

        $starttag = $_REQUEST['starttag'];
        $startmonat = $_REQUEST['startmonat'];
        $startjahr = $_REQUEST['startjahr'];
        erzeugeTagesmengenUebersichtExcel($_SESSION['gesamtmengen_array'], $tag, $monat, $jahr);
        break;
    case 'erzeuge_wochenmengenaufstellung':
        if (!$_REQUEST['starttag'] && !$_REQUEST['startmonat'] && !$_REQUEST['startjahr']) {
            $starttag = date('d');
            $startmonat = date('m');
            $startjahr = date('Y');
        } else {
            $starttag = $_REQUEST['starttag'];
            $startmonat = $_REQUEST['startmonat'];
            $startjahr = $_REQUEST['startjahr'];
        }

        $wochentag_string = strftime('%a', mktime(12, 0, 0, $startmonat, $starttag, $startjahr));


        $tag_in_angezeigter_woche_ts = mktime(12, 0, 0, $startmonat, $starttag, $startjahr);
        switch ($wochentag_string) {
            case 'Sa':
                $starttag = date('d', $tag_in_angezeigter_woche_ts + 86400 * 2);
                $startmonat = date('m', $tag_in_angezeigter_woche_ts + 86400 * 2);
                $startjahr = date('Y', $tag_in_angezeigter_woche_ts + 86400 * 2);
                break;
            case 'So':
                $starttag = date('d', $tag_in_angezeigter_woche_ts + 86400 * 1);
                $startmonat = date('m', $tag_in_angezeigter_woche_ts + 86400 * 1);
                $startjahr = date('Y', $tag_in_angezeigter_woche_ts + 86400 * 1);
                break;
            case 'Mo':
                $starttag = date('d', $tag_in_angezeigter_woche_ts + 86400 * 0);
                $startmonat = date('m', $tag_in_angezeigter_woche_ts + 86400 * 0);
                $startjahr = date('Y', $tag_in_angezeigter_woche_ts + 86400 * 0);
                break;
            case 'Di':
                $starttag = date('d', $tag_in_angezeigter_woche_ts - 86400 * 1);
                $startmonat = date('m', $tag_in_angezeigter_woche_ts - 86400 * 1);
                $startjahr = date('Y', $tag_in_angezeigter_woche_ts - 86400 * 1);
                break;
            case 'Mi':
                $starttag = date('d', $tag_in_angezeigter_woche_ts - 86400 * 2);
                $startmonat = date('m', $tag_in_angezeigter_woche_ts - 86400 * 2);
                $startjahr = date('Y', $tag_in_angezeigter_woche_ts - 86400 * 2);
                break;
            case 'Do':
                $starttag = date('d', $tag_in_angezeigter_woche_ts - 86400 * 3);
                $startmonat = date('m', $tag_in_angezeigter_woche_ts - 86400 * 3);
                $startjahr = date('Y', $tag_in_angezeigter_woche_ts - 86400 * 3);
                break;
            case 'Fr':
                $starttag = date('d', $tag_in_angezeigter_woche_ts - 86400 * 4);
                $startmonat = date('m', $tag_in_angezeigter_woche_ts - 86400 * 4);
                $startjahr = date('Y', $tag_in_angezeigter_woche_ts - 86400 * 4);
                break;
        }


        $tage_ts = array();
        for ($i = 0; $i < 5; $i++) {
            $tage_ts[$i] = mktime(12, 0, 0, $startmonat, $starttag, $startjahr) + $i * 86400;
        }

        $mengen_woche = array();
        $mengen_woche_nach_tagen = array();
        foreach ($tage_ts as $tag_ts) {


            $bestellungen_zu_tag = $bestellungVerwaltung->findeAlleZuDatum(strftime('%d', $tag_ts), strftime('%m', $tag_ts), strftime('%Y', $tag_ts));

            foreach ($bestellungen_zu_tag as $bestellung) {
                $bestellung_has_speisen_array = $bestellung_has_speiseVerwaltung->findeAnhandVonBestellungId($bestellung->getId());
                //var_dump($bestellung_has_speisen_array);
                foreach ($bestellung_has_speisen_array as $bestellung_has_speise) {
                    $speise_nr = $bestellung_has_speise->getSpeiseNr();
                    $speise = $speiseVerwaltung->findeAnhandVonId($bestellung_has_speise->getSpeiseId());
                    $speise_id = $speise->getId();
                    $kunden = $kundeVerwaltung->findeAlleAktivenZuSpeiseNr($speise_nr);
                    $gesamt_menge_speise = 0;
                    foreach ($kunden as $kunde) {

                        /* if ($speise_nr > 1 && $kunde->getAnzahlSpeisen() == 1 && !$kunde->getStaedtischerKunde()) {
                          continue;
                          } */
                        $einrichtungskategorie_id = $kunde->getEinrichtungskategorieId();
                        $menge_pro_portion = $menge_pro_portionVerwaltung->findeAnhandVonSpeiseIdUndEinrichtungskategorieId($speise_id, $einrichtungskategorie_id);

                        /*  $standardportionen = $standardportionenVerwaltung->findeAnhandVonKundenId($kunde->getId());
                          $portionenaenderung = $portionenaenderungVerwaltung->findeAnhandVonKundenIdUndWochenstartdatum($kunde->getId(), $starttag, $startmonat, $startjahr);
                         */
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
                        $indi_faktor = $indifaktorVerwaltung->findeAnhandVonSpeiseIdUndKundeId($speise_id, $kunde->getId());
                        if ($indi_faktor->getId()) {
                            $faktor = $indi_faktor->getFaktor();
                        } else {
                            $faktor = 1;
                        }


                        $wochentag_string = strftime('%a', mktime(12, 0, 0, strftime('%m', $tag_ts), strftime('%d', $tag_ts), strftime('%Y', $tag_ts)));
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
                        $gesamtmenge_kunde = $portionen * ($menge_pro_portion->getMenge() * $faktor);

                        if ($menge_pro_portion->getEinheit() === 'Flasche' || $menge_pro_portion->getEinheit() === 'Beutel') {

                            $menge_zu_tag_preround = $gesamtmenge_kunde;
                            $komma = fmod($gesamtmenge_kunde, 1);
                            if ($gesamtmenge_kunde < 1) {
                                $gesamtmenge_kunde = ceil($gesamtmenge_kunde);
                            } else {
                                if ($komma < 0.4 && $komma != 0) {
                                    //abrunden
                                    $gesamtmenge_kunde = floor($gesamtmenge_kunde);
                                }
                                if ($komma >= 0.4) {
                                    //abrunden
                                    $gesamtmenge_kunde = ceil($gesamtmenge_kunde);
                                }
                            }
                        }

                        if ($menge_pro_portion->getEinheit() === 'Blech') {
                            $gesamtmenge_kunde = ceil($gesamtmenge_kunde * 2) / 2;
                        }

                        $gesamt_menge_speise += $gesamtmenge_kunde;
                    }
                    $menge_umg = '';
                    $einheit = '';
                    if ($menge_pro_portion->getEinheit() == 'g' || $menge_pro_portion->getEinheit() == 'ml') {
                        $menge_umg = $gesamt_menge_speise / 1000;
                        switch ($menge_pro_portion->getEinheit()) {
                            case 'g':
                                $einheit = 'kg';
                                break;
                            case 'ml':
                                $einheit = 'L';
                                break;
                        }
                    }



                    $menge_umg = str_replace(".", ",", $menge_umg);

                    $mengen_woche_nach_tagen[strftime('%A %d.%m.%Y', $tag_ts)][$speise_nr][$speise->getBezeichnung()] = $mengen_woche[$tag_ts][$speise->getBezeichnung() . ' (' . $speise_nr . ')'] = array(
                        'Speise' => $speise->getBezeichnung(),
                        'Menge' => $gesamt_menge_speise . ' ' . $menge_pro_portion->getEinheit(),
                        'Datum' => strftime('%d.%m.%Y', $tag_ts),
                        'Timestamp' => $tag_ts,
                        'Einheit' => $menge_pro_portion->getEinheit(),
                        'Umgerechnet' => $menge_umg . ' ' . $einheit,
                        'Speisennr' => $speise_nr
                    );
                }
            }
        }
        erzeugeWochenmengenUebersichtExcel($mengen_woche_nach_tagen, $starttag, $startmonat, $startjahr, $color_speisen);

        break;


    case 'abrechnung':
        if ($_REQUEST['jahr']) {
            $jahr = $_REQUEST['jahr'];
        } else {
            $jahr = date('Y');
        }
        if ($_REQUEST['monat']) {
            $monat = $_REQUEST['monat'];
        } else {
            $monat = date('m');
        }
        //$kunden = $kundeVerwaltung->findeAlle();
        $kunden = $kundeVerwaltung->findeAlleOhneStaedtische();
        /*
          foreach ( $kunden as $kunde) {
          findePortionenZuTagUndKunde($kunde->getId(),time() , $kundeVerwaltung, $standardportionenVerwaltung, $portionenaenderungVerwaltung);
          } */
        break;
    case 'mengen_festlegen':
        $t = 0;
        $u = 0;
        foreach ($_REQUEST['einrichtungskategorie_ids'] as $einrichtungskategorie_id) {
            if ($menge_pro_portionVerwaltung->findeAnhandVonId($_REQUEST['ids'][$u])->getId() > 0) {
                $neue_menge_pro_portion = $menge_pro_portionVerwaltung->findeAnhandVonId($_REQUEST['ids'][$u]);
            } else {
                $neue_menge_pro_portion = new MengeProPortion();
            }
            $neue_menge_pro_portion->setSpeiseId($_REQUEST['speise_id']);
            $neue_menge_pro_portion->setEinrichtungskategorieId($einrichtungskategorie_id);
            $neue_menge_pro_portion->setMenge($_REQUEST['mengen'][$u]);

            $neue_menge_pro_portion->setEinheit($_REQUEST['einheit']);

            $menge_pro_portionVerwaltung->speichere($neue_menge_pro_portion);
            $u++;
        }
        header('location:index.php?action=speisenverwaltung');
        break;
    case 'faktoren_festlegen':
        $kunde = $kundeVerwaltung->findeAnhandVonId($_REQUEST['id']);
        $speisen = $speiseVerwaltung->findeAlle();
        $einrichtungskategorie = $einrichtungskategorieVerwaltung->findeAnhandVonId($kunde->getEinrichtungskategorieId());
        break;
    case 'do_faktoren_festlegen':
        $x = 0;
        foreach ($_REQUEST['speise_ids'] as $speise_id) {
            $indifaktor = $indifaktorVerwaltung->findeAnhandVonSpeiseIdUndKundeId($speise_id, $_REQUEST['kunde_id']);
            if ($indifaktor->getId()) {
                $daten['id'] = $indifaktor->getId();
            } else {
                $daten['id'] = 0;
            }
            $daten['speise_id'] = $speise_id;
            $daten['kunde_id'] = $_REQUEST['kunde_id'];
            $daten['faktor'] = $_REQUEST['faktoren'][$x];
            $neuer_indifaktor = new Indifaktor($daten);
            $indifaktorVerwaltung->speichere($neuer_indifaktor);

            $daten['id'] = $_REQUEST['bemerkungen_ids'][$x];
            $daten['bemerkung'] = $_REQUEST['bemerkungen'][$x];
            $neue_bemerkung_zu_speise = new BemerkungZuSpeise($daten);
            if ($neue_bemerkung_zu_speise->istValide()) {
                $bemerkungzuspeiseVerwaltung->speichere($neue_bemerkung_zu_speise);
            }
            $x++;
        }
        header('location:index.php?action=kundenverwaltung');
        break;

    case 'bemerkung_zu_tag_loeschen':
        $bemerkung_zu_tag = $bemerkungzuspeiseVerwaltung->findeAnhandVonId($_REQUEST['id']);
        //var_dump($bemerkung_zu_tag[0]);
        $bemerkungzuspeiseVerwaltung->loesche($bemerkung_zu_tag[0]);
        header('location:index.php?action=faktoren_festlegen&id=' . $_REQUEST['kid']);
        exit;
        break;
    case 'bemerkung_zu_tag_speichern':

        $bemerkung_zu_tag = new BemerkungZuTag($_REQUEST);
        $bemerkungzutagVerwaltung->speichere($bemerkung_zu_tag);
        header('location:index.php?action=uebersicht_tag&tag2=' . $bemerkung_zu_tag->getTag2() . '&monat=' . $bemerkung_zu_tag->getMonat() . '&jahr=' . $bemerkung_zu_tag->getJahr() . '&starttag=' . $_REQUEST['starttag'] . '&startmonat=' . $_REQUEST['startmonat'] . '&startjahr=' . $_REQUEST['startjahr']);
        break;
    case 'speise_bearbeiten':
        $speise_id = $_REQUEST['speise_id'];
        $speise = $speiseVerwaltung->findeAnhandVonId($speise_id);
        break;
    case 'speise_bearbeitung_speichern':
        $speise = $speiseVerwaltung->findeAnhandVonId($_REQUEST['id']);
        $speise->setBezeichnung($_REQUEST['bezeichnung']);
        $speiseVerwaltung->speichere($speise);
        header('location:index.php?action=speisenverwaltung');
        break;
    case 'infoticker':
        $infos = $infotickerVerwaltung->findeAlleUnerledigten();
        $infos = $infotickerVerwaltung->findeAlleUnerledigten2();
        break;
    case 'speichere_infoticker':


        $infoticker = new Infoticker($_REQUEST);
        $datum_ts = mktime(22, 0, 0, $infoticker->getMonat(), $infoticker->getTag(), $infoticker->getJahr());
        $infoticker->setDatumTs($datum_ts);
        $infotickerVerwaltung->speichere($infoticker);
        header('location:index.php?action=infoticker');
        break;
    case 'infoticker_done':
        $infoticker = $infotickerVerwaltung->findeAnhandVonId($_REQUEST['id']);
        $infoticker->setErledigt(1);
        $infotickerVerwaltung->speichere($infoticker);
        header('location:index.php?action=infoticker');
        break;
    case 'infoticker_done_anz':
        $infoticker = $infotickerVerwaltung->findeAnhandVonId($_REQUEST['id']);
        $infoticker->setErledigt(1);
        $infotickerVerwaltung->speichere($infoticker);
        header('location:index.php?action=infoticker_anzeige&itanzid=' . $_REQUEST['itanzid']);
        break;
    case 'infoticker_anzeige':
        $no_delete = false;
        if ($_REQUEST['nodel']) {
            $no_delete = true;
        }
        $infos = $infotickerVerwaltung->findeAlleUnerledigten3();
        break;
}

require __DIR__ . '/view/layout.tpl.php';
?>
