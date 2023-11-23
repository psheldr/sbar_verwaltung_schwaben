<?php

class KundeVerwaltung {

    private $db = null;

    function __construct($db) {
        $this->db = $db;
    }

    function wandleArrayZuObjekt($kundeArray) {
        $kunde = New Kunde();
        $kunde->setId($kundeArray['id']);
        $kunde->setEinrichtungskategorieId($kundeArray['einrichtungskategorie_id']);
        $kunde->setKundennummer($kundeArray['kundennummer']);
        $kunde->setName($kundeArray['name']);
        $kunde->setAsp($kundeArray['asp']);
        $kunde->setStrasse($kundeArray['strasse']);
        $kunde->setPlz($kundeArray['plz']);
        $kunde->setOrt($kundeArray['ort']);
        $kunde->setTelefon($kundeArray['telefon']);
        $kunde->setTelefon2($kundeArray['telefon_2']);
        $kunde->setFax($kundeArray['fax']);
        $kunde->setEmail($kundeArray['email']);
        $kunde->setLieferreihenfolge($kundeArray['lieferreihenfolge']);
        $kunde->setEssenszeit($kundeArray['essenszeit']);
        $kunde->setLexware($kundeArray['lexware']);
        $kunde->setBemerkung($kundeArray['bemerkung']);
        $kunde->setAktiv($kundeArray['aktiv']);
        $kunde->setStartdatum($kundeArray['startdatum']);
        $kunde->setPreis($kundeArray['preis']);
        $kunde->setArtikelbezeichnung($kundeArray['artikelbezeichnung']);
        $kunde->setKundeninfo($kundeArray['kundeninfo']);
        $kunde->setAnzahlSpeisen($kundeArray['anzahl_speisen']);
        $kunde->setAnzahlBoxen($kundeArray['anzahl_boxen']);
        $kunde->setKitafinoGruppen($kundeArray['kitafino_gruppen']);
        $kunde->setTourId($kundeArray['tour_id']);
        $kunde->setBesteck($kundeArray['besteck']);
        $kunde->setBemerkungKunde($kundeArray['bemerkung_kunde']);
        $kunde->setStaedtischerKunde($kundeArray['staedtischer_kunde']);
        $kunde->setBioKunde($kundeArray['bio_kunde']);
        $kunde->setEinrichtungsart($kundeArray['einrichtungsart']);
        $kunde->setFahrerinfo($kundeArray['fahrerinfo']);
        $kunde->setProduktionsreihenfolge($kundeArray['produktionsreihenfolge']);
        $kunde->setMaster($kundeArray['master']);
        $kunde->setMasternummer($kundeArray['masternummer']);
        $kunde->setTraegerId($kundeArray['traeger_id']);
        return $kunde;
    }

    function wandleSqlZuObjekten($sql, $bedingungen = array()) {
        $abfrage = $this->db->prepare($sql);
        $abfrage->execute($bedingungen);
        $kundenArray = $abfrage->fetchAll();
        $kundenObjekte = array();
        foreach ($kundenArray as $kundeArray) {
            $kundenObjekte[] = $this->wandleArrayZuObjekt($kundeArray);
        }
        return $kundenObjekte;
    }

    function findeAlleNotgruppenAlt() {
        $sql = "SELECT * FROM kunde WHERE name LIKE '%not%' ORDER BY id";
        return $this->wandleSqlZuObjekten($sql);
    }
    function findeAlleNotgruppen() {
        $sql = "SELECT * FROM kunde WHERE name LIKE '# %' OR name LIKE '#%' ORDER BY id";
        return $this->wandleSqlZuObjekten($sql);
    }
    function findeAlleNichtNotgruppen() {
        $sql = "SELECT * FROM kunde WHERE name NOT LIKE '# %' AND name NOT LIKE '#%' ORDER BY id";
        return $this->wandleSqlZuObjekten($sql);
    }

    function findeAlleOhneStaedtische() {
        $sql = "SELECT * FROM kunde WHERE einrichtungskategorie_id != 5 AND einrichtungskategorie_id != 6 AND staedtischer_kunde != 1  ORDER BY name";
        return $this->wandleSqlZuObjekten($sql);
    }

    function findeAlleKitafinoIds() {
        $sql = "SELECT id FROM kunde WHERE einrichtungskategorie_id != 5 AND einrichtungskategorie_id != 6 AND staedtischer_kunde != 1 AND bio_kunde != 1 AND kundennummer != 0 ORDER BY aktiv DESC, name ASC";
        return $this->wandleSqlZuObjekten($sql);
    }
    function findeAlleStandardIds() {
        $sql = "SELECT id FROM kunde WHERE einrichtungskategorie_id != 5 AND einrichtungskategorie_id != 6 AND staedtischer_kunde != 1 AND bio_kunde != 1 AND kundennummer = 0 ORDER BY aktiv DESC, name ASC";
        return $this->wandleSqlZuObjekten($sql);
    }
    function findeAlleStaedtischenIds() {
        $sql = "SELECT id FROM kunde WHERE einrichtungskategorie_id != 5 AND einrichtungskategorie_id != 6 AND staedtischer_kunde = 1 ORDER BY aktiv DESC, name ASC";
        return $this->wandleSqlZuObjekten($sql);
    }
    function findeAlleBioIds() {
        $sql = "SELECT id FROM kunde WHERE einrichtungskategorie_id != 5 AND einrichtungskategorie_id != 6 AND bio_kunde = 1 ORDER BY aktiv DESC, name ASC";
        return $this->wandleSqlZuObjekten($sql);
    }

    function findeAlleReal($sort = '') {
        if ($sort == '') {
            $sort = 'lieferreihenfolge';
        }
        $sql = "SELECT * FROM kunde ORDER BY $sort ASC";
        return $this->wandleSqlZuObjekten($sql);
    }
    function findeAlle($sort = '') {
        if ($sort == '') {
            $sort = 'lieferreihenfolge';
        }
        $sql = "SELECT * FROM kunde WHERE einrichtungskategorie_id != 5 ORDER BY $sort ASC";
        return $this->wandleSqlZuObjekten($sql);
    }

    function findeAlleZuKidArray($kunden_ids_arr, $sort = '') {
        $kunden_ids = implode(',',$kunden_ids_arr);
        if ($sort == '') {
            $sort = 'name';
        }
        $sql = "SELECT * FROM kunde WHERE id IN ($kunden_ids) ORDER BY aktiv DESC, $sort ASC";
        return $this->wandleSqlZuObjekten($sql);
    }

    function findeAlleStandard($sort = '') {
        if ($sort == '') {
            $sort = 'lieferreihenfolge';
        }
        $sql = "SELECT * FROM kunde WHERE einrichtungskategorie_id = 6 OR (staedtischer_kunde = 0 AND bio_kunde = 0) ORDER BY $sort ASC";
        return $this->wandleSqlZuObjekten($sql);
    }

    function findeAlleStandardMitSpeisenzahl($sort = '', $anzahl_speisen = 2) {
        if ($sort == '') {
            $sort = 'lieferreihenfolge';
        }
        $sql = "SELECT * FROM kunde WHERE einrichtungskategorie_id = 6 OR (anzahl_speisen = $anzahl_speisen AND staedtischer_kunde = 0 AND bio_kunde = 0) ORDER BY $sort ASC";
        return $this->wandleSqlZuObjekten($sql);
    }

    function findeAlleStaedtischenUndBio($sort = '') {
        if ($sort == '') {
            $sort = 'lieferreihenfolge';
        }
        $sql = "SELECT * FROM kunde WHERE einrichtungskategorie_id = 6 OR staedtischer_kunde = 1 OR bio_kunde = 1 ORDER BY $sort ASC";
        return $this->wandleSqlZuObjekten($sql);
    }

    function findeAlleStaedtischenUndBioSpeise4($sort = '') {
        if ($sort == '') {
            $sort = 'lieferreihenfolge';
        }
        $sql = "SELECT * FROM kunde WHERE einrichtungskategorie_id = 6 OR staedtischer_kunde = 1 OR (bio_kunde = 1 AND anzahl_speisen = 2) ORDER BY $sort ASC";
        return $this->wandleSqlZuObjekten($sql);
    }

    function findeAlleZuTourId($tour_id, $sort = '') {
        if ($sort == '') {
            $sort = 'lieferreihenfolge';
        }
        $sql = "SELECT * FROM kunde WHERE tour_id = $tour_id AND aktiv = 1 ORDER BY $sort ASC";
        return $this->wandleSqlZuObjekten($sql);
    }

    function findeAlleInklTrenner($sort = '') {
        if ($sort == '') {
            $sort = 'lieferreihenfolge';
        }
        $sql = "SELECT * FROM kunde WHERE aktiv = 1 ORDER BY $sort ASC";
        return $this->wandleSqlZuObjekten($sql);
    }

    function findeTourZuKundenReihenfolge($reihenfolge, $sort = '') {
        $sql = "SELECT * FROM kunde WHERE einrichtungskategorie_id = 5 AND lieferreihenfolge < $reihenfolge ORDER BY lieferreihenfolge DESC LIMIT 1";
        return $this->wandleSqlZuObjekten($sql);
    }

    function findeAlleZuTour($tour_reihenfolge, $ende_tour) {
        $sql = "SELECT * FROM kunde WHERE lieferreihenfolge < $ende_tour AND lieferreihenfolge > $tour_reihenfolge AND aktiv = 1 ORDER BY lieferreihenfolge ASC";

        return $this->wandleSqlZuObjekten($sql);
    }

    function findeTourEndeZuKundenReihenfolge($reihenfolge, $sort = '') {
        $sql = "SELECT * FROM kunde WHERE einrichtungskategorie_id = 5 AND lieferreihenfolge > $reihenfolge ORDER BY lieferreihenfolge ASC LIMIT 1";
        return $this->wandleSqlZuObjekten($sql);
    }

    function findeAlleZuTourStartUndTourEnde($start_reihenfolge, $ende_reihenfolge, $sort = '') {
        $sql = "SELECT * FROM kunde WHERE lieferreihenfolge > $start_reihenfolge AND lieferreihenfolge < $ende_reihenfolge ORDER BY lieferreihenfolge ASC";
        return $this->wandleSqlZuObjekten($sql);
    }

    function findeAlleStaedtischen($sort = '') {
        if ($sort == '') {
            $sort = 'lieferreihenfolge';
        }
        $sql = "SELECT * FROM kunde WHERE staedtischer_kunde = 1 ORDER BY $sort ASC";
        return $this->wandleSqlZuObjekten($sql);
    }

    function findeAlleAktivenStaedtischen($sort = '') {
        if ($sort == '') {
            $sort = 'lieferreihenfolge';
        }
        $sql = "SELECT * FROM kunde WHERE staedtischer_kunde = 1 and aktiv = 1 ORDER BY $sort ASC";
        return $this->wandleSqlZuObjekten($sql);
    }

    function findeAlleTourenden($sort = '') {
        if ($sort == '') {
            $sort = 'lieferreihenfolge';
        }
        $sql = "SELECT * FROM kunde WHERE einrichtungskategorie_id = 5 ORDER BY $sort ASC";
        return $this->wandleSqlZuObjekten($sql);
    }

    function findeAlleAktiven($sort = '') {
        if ($sort == '') {
            $sort = 'lieferreihenfolge';
        }
        $sql = "SELECT * FROM kunde WHERE (aktiv=1 OR (aktiv=0 AND startdatum < 0)) ORDER BY $sort ASC";
        return $this->wandleSqlZuObjekten($sql);
    }

    function findeAlleAktiven4($sort = '') {
        if ($sort == '') {
            $sort = 'lieferreihenfolge';
        }
        $heute = mktime(12, 0, 0, date('m'), date('d'), date('Y'));
        $heute_minus_24h = $heute - (86400 * 5);
        $morgen = $heute + 86400 * 4;
        $sql = "SELECT * FROM kunde WHERE (aktiv=1 OR (aktiv=0 AND startdatum > 0 AND startdatum > " . $heute_minus_24h . " AND startdatum <= " . $morgen . ")) ORDER BY $sort ASC";
        return $this->wandleSqlZuObjekten($sql);
    }

    function findeAlleTouren($sort = '') {
        if ($sort == '') {
            $sort = 'lieferreihenfolge';
        }
        $sql = "SELECT * FROM kunde WHERE einrichtungskategorie_id = 5 AND aktiv = 1 ORDER BY $sort ASC";
        return $this->wandleSqlZuObjekten($sql);
    }

    function findeAlleTourenTrenner($sort = '') {
        if ($sort == '') {
            $sort = 'lieferreihenfolge';
        }
        $sql = "SELECT * FROM kunde WHERE einrichtungskategorie_id = 6 AND aktiv = 1 ORDER BY $sort ASC";
        return $this->wandleSqlZuObjekten($sql);
    }

    function findeAlleAktivenZuSpeiseNrUndTourIdProduktion($speise_nr, $tour_id) {
        $add_sql = '';
        switch ($speise_nr) {
            case 1:
                $add_sql = ' AND (bio_kunde = 0 AND staedtischer_kunde = 0)';
                break;
            case 2:
                $add_sql = ' AND (bio_kunde = 0 AND staedtischer_kunde = 0) AND anzahl_speisen > 1';
                break;
            case 3:
            case 4:
                $add_sql = ' AND (bio_kunde = 1 OR staedtischer_kunde = 1)';
                break;
        }
        if ($sort == '') {
            $sort = 'lieferreihenfolge';
        }
        $heute = mktime(12, 0, 0, date('m'), date('d'), date('Y'));
        $heute_minus_24h = $heute - (86400 * 5);
        $morgen = $heute + 86400 * 4;
        $sql = "SELECT * FROM kunde WHERE tour_id = $tour_id AND einrichtungskategorie_id != 5 AND (aktiv=1 OR (aktiv=0 AND startdatum > 0 AND startdatum > " . $heute_minus_24h . " AND startdatum <= " . $morgen . ")) ";
        $sql .= $add_sql;
        $sql .= " ORDER BY $sort ASC";

        return $this->wandleSqlZuObjekten($sql);
    }

    function findeAlleAktivenZuSpeiseNr($speise_nr) {
        $add_sql = '';
        switch ($speise_nr) {
            case 1:
                $add_sql = ' AND (bio_kunde = 0 AND staedtischer_kunde = 0)';
                break;
            case 2:
                $add_sql = ' AND (bio_kunde = 0 AND staedtischer_kunde = 0) AND anzahl_speisen > 1';
                break;
            case 3:
            case 4:
                $add_sql = ' AND (bio_kunde = 1 OR staedtischer_kunde = 1)';
                break;
        }
        if ($sort == '') {
            $sort = 'lieferreihenfolge';
        }
        $heute = mktime(12, 0, 0, date('m'), date('d'), date('Y'));
        $heute_minus_24h = $heute - (86400 * 5);
        $morgen = $heute + 86400 * 4;
        $sql = "SELECT * FROM kunde WHERE (aktiv=1 OR (aktiv=0 AND startdatum > 0 AND startdatum > " . $heute_minus_24h . " AND startdatum <= " . $morgen . ")) ";
        $sql .= $add_sql;
        $sql .= " ORDER BY $sort ASC";
        return $this->wandleSqlZuObjekten($sql);
    }

    function findeAlleAktiven4ZuSpeiseNr($sort = '', $speise_nr = 1) {
        if ($sort == '') {
            $sort = 'lieferreihenfolge';
        }
        $heute = mktime(12, 0, 0, date('m'), date('d'), date('Y'));
        $heute_minus_24h = $heute - (86400 * 5);
        $morgen = $heute + 86400 * 4;
        $sql = "SELECT * FROM kunde WHERE (aktiv=1 OR (aktiv=0 AND startdatum > 0 AND startdatum > " . $heute_minus_24h . " AND startdatum <= " . $morgen . ")) AND (anzahl_speisen = $speise_nr OR einrichtungskategorie_id = 6) ORDER BY $sort ASC";
        return $this->wandleSqlZuObjekten($sql);
    }

    function findeAlleAktivenLimit($limit) {
        $sql = "SELECT * FROM kunde WHERE ((aktiv=1 AND einrichtungskategorie_id != 5) OR (aktiv=0 AND startdatum > 0 AND einrichtungskategorie_id != 5)) ORDER BY lieferreihenfolge ASC LIMIT $limit";
        return $this->wandleSqlZuObjekten($sql);
    }

    function findeAlleAktiven3($sort = '') {
        if ($sort == '') {
            $sort = 'lieferreihenfolge';
        }
        $sql = "SELECT * FROM kunde WHERE ((aktiv=1 AND einrichtungskategorie_id != 5) OR (aktiv=0 AND startdatum > 0 AND einrichtungskategorie_id != 5)) ORDER BY $sort ASC";
        return $this->wandleSqlZuObjekten($sql);
    }

    function findeAlleAktiven2($sort = '') {
        if ($sort == '') {
            $sort = 'lieferreihenfolge';
        }
        $sql = "SELECT * FROM kunde WHERE aktiv=1 AND einrichtungskategorie_id != 5 ORDER BY $sort ASC";
        return $this->wandleSqlZuObjekten($sql);
    }

    function findeAlleMitKategorieId($katid) {
        $sql = "SELECT * FROM kunde WHERE einrichtungskategorie_id=$katid ORDER BY lieferreihenfolge";
        return $this->wandleSqlZuObjekten($sql);
    }

    function findeAlleMitKundennummerKitafino() {
        $sql = "SELECT * FROM kunde WHERE kundennummer > 0 AND aktiv = 1 ORDER BY lieferreihenfolge";
        return $this->wandleSqlZuObjekten($sql);
    }
    function findeAlleAuchInaktiveMitKundennummerKitafino() {
        $sql = "SELECT * FROM kunde WHERE kundennummer > 0 ORDER BY lieferreihenfolge";
        return $this->wandleSqlZuObjekten($sql);
    }

    function findeAlleMitKundennummer($kundennummer) {
        $sql = "SELECT * FROM kunde WHERE kundennummer = '$kundennummer' ORDER BY lieferreihenfolge";
        return $this->wandleSqlZuObjekten($sql);
    }

    function findeAlleInaktivenMitStartdatum() {
        $heute = time();
        $sql = "SELECT * FROM kunde WHERE aktiv=0 AND startdatum <= $heute";
        return $this->wandleSqlZuObjekten($sql);
    }

    function findeAlleMasternummern() {
        $sql = "SELECT id, masternummer, name FROM kunde WHERE masternummer != '' AND master = 1";
        return $this->wandleSqlZuObjekten($sql);
    }

    function findeUnterkundenZuMaster($masternummer) {
        $sql = "SELECT * FROM kunde WHERE masternummer = '$masternummer' AND master = 0 ORDER BY name ASC";
        return $this->wandleSqlZuObjekten($sql);
    }
    function findeMasterkundeZuMasternummer($masternummer) {
        $sql = "SELECT * FROM kunde WHERE masternummer = '$masternummer' AND master = 1 ORDER BY name ASC";
        return $this->wandleSqlZuObjekten($sql);
    }


    function findeAlleZuTraegerId($traeger_id) {
        $sql = "SELECT * FROM kunde WHERE traeger_id = $traeger_id ORDER BY name ASC";
        return $this->wandleSqlZuObjekten($sql);
    }

    function findeAnhandVonId($id) {
        $sql = "SELECT * FROM kunde WHERE id=?";
        $abfrage = $this->db->prepare($sql);
        $abfrage->execute(array($id));
        $kundeArray = $abfrage->fetch();
        return $this->wandleArrayZuObjekt($kundeArray);
    }

    function findeAlleAktivenMitBesteck() {
        $sql = "SELECT * FROM kunde WHERE aktiv=1 AND besteck = 1 ORDER BY lieferreihenfolge ASC";
        return $this->wandleSqlZuObjekten($sql);
    }


    function findeTourtrennerZuTour($tour_id) {
        $sql = "SELECT * FROM kunde WHERE tour_id=? AND einrichtungskategorie_id = 6 and aktiv=1";
        $abfrage = $this->db->prepare($sql);
        $abfrage->execute(array($tour_id));
        $kundeArray = $abfrage->fetch();
        return $this->wandleArrayZuObjekt($kundeArray);
    }

    function fuegeKundeHinzu(Kunde $kunde) {
        $sql = "INSERT INTO kunde (einrichtungskategorie_id,kundennummer, name, asp, strasse, plz, ort, telefon, telefon_2, fax, email, lieferreihenfolge, essenszeit,lexware, bemerkung, aktiv, startdatum, preis, artikelbezeichnung, kundeninfo, anzahl_speisen, anzahl_boxen, kitafino_gruppen, tour_id, besteck, bemerkung_kunde, staedtischer_kunde, bio_kunde, einrichtungsart,fahrerinfo, produktionsreihenfolge, master,masternummer, traeger_id) VALUES (?,?,?,?,?,?,?,?,?,?,?,?,?,?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
        $abfrage = $this->db->prepare($sql);
        $abfrage->execute(array(
            $kunde->getEinrichtungskategorieId(),
            $kunde->getKundennummer(),
            $kunde->getName(),
            $kunde->getAsp(),
            $kunde->getStrasse(),
            $kunde->getPlz(),
            $kunde->getOrt(),
            $kunde->getTelefon(),
            $kunde->getTelefon2(),
            $kunde->getFax(),
            $kunde->getEmail(),
            $kunde->getLieferreihenfolge(),
            $kunde->getEssenszeit(),
            $kunde->getLexware(),
            $kunde->getBemerkung(),
            $kunde->getAktiv(),
            $kunde->getStartdatum(),
            $kunde->getPreis(),
            $kunde->getArtikelbezeichnung(),
            $kunde->getKundeninfo(),
            $kunde->getAnzahlSpeisen(),
            $kunde->getAnzahlBoxen(),
            $kunde->getKitafinoGruppen(),
            $kunde->getTourId(),
            $kunde->getBesteck(),
            $kunde->getBemerkungKunde(),
            $kunde->getStaedtischerKunde(),
            $kunde->getBioKunde(),
            $kunde->getEinrichtungsart(),
            $kunde->getFahrerinfo(),
            $kunde->getProduktionsreihenfolge(),
            $kunde->getMaster(),
            $kunde->getMasternummer(),
            $kunde->getTraegerId()
        ));
        $kunde->setId($this->db->lastInsertId());
    }

    function aendereKunde(Kunde $kunde) {
        $sql = "UPDATE kunde SET einrichtungskategorie_id=?, kundennummer=?, name=?, asp=?, strasse=?, plz=?, ort=?, telefon=?, telefon_2=?, fax=?, email=?, lieferreihenfolge=?, essenszeit=?, lexware=?, bemerkung=?, aktiv=?, startdatum=?, preis=?, artikelbezeichnung=?, kundeninfo=?, anzahl_speisen=?, anzahl_boxen=?, kitafino_gruppen=?, tour_id=?, besteck=?, bemerkung_kunde=?, staedtischer_kunde=?, bio_kunde=?, einrichtungsart=?, fahrerinfo=?, produktionsreihenfolge=?, master=?, masternummer=?, traeger_id=? WHERE id=?";
        $abfrage = $this->db->prepare($sql);
        $daten = array(
            $kunde->getEinrichtungskategorieId(),
            $kunde->getKundennummer(),
            $kunde->getName(),
            $kunde->getAsp(),
            $kunde->getStrasse(),
            $kunde->getPlz(),
            $kunde->getOrt(),
            $kunde->getTelefon(),
            $kunde->getTelefon2(),
            $kunde->getFax(),
            $kunde->getEmail(),
            $kunde->getLieferreihenfolge(),
            $kunde->getEssenszeit(),
            $kunde->getLexware(),
            $kunde->getBemerkung(),
            $kunde->getAktiv(),
            $kunde->getStartdatum(),
            $kunde->getPreis(),
            $kunde->getArtikelbezeichnung(),
            $kunde->getKundeninfo(),
            $kunde->getAnzahlSpeisen(),
            $kunde->getAnzahlBoxen(),
            $kunde->getKitafinoGruppen(),
            $kunde->getTourId(),
            $kunde->getBesteck(),
            $kunde->getBemerkungKunde(),
            $kunde->getStaedtischerKunde(),
            $kunde->getBioKunde(),
            $kunde->getEinrichtungsart(),
            $kunde->getFahrerinfo(),
            $kunde->getProduktionsreihenfolge(),
            $kunde->getMaster(),
            $kunde->getMasternummer(),
            $kunde->getTraegerId(),
            $kunde->getId()
        );
        $abfrage->execute($daten);
        return true;
    }

    function speichere(Kunde $kunde) {
        if (!$kunde->istValide()) {
            return false;
        }
        if ($kunde->getId()) {
            $this->aendereKunde($kunde);
        } else {
            $this->fuegeKundeHinzu($kunde);
        }
        return true;
    }

    function loesche(Kunde $kunde) {
        $sql = "DELETE FROM kunde WHERE id=?";
        $abfrage = $this->db->prepare($sql);
        $abfrage->execute(array($kunde->getId()));
    }

}

?>
