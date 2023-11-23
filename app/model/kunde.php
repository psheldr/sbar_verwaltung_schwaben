<?php

class Kunde {

    private $id = 0;
    private $einrichtungskategorie_id = 0;
    private $kundennummer = 0;
    private $name = '';
    private $asp = '';
    private $strasse = '';
    private $plz = '';
    private $ort = '';
    private $telefon = '';
    private $telefon_2 = '';
    private $fax = '';
    private $email = '';
    private $lieferreihenfolge = 0;
    private $essenszeit = 0;
    private $lexware = '';
    private $bemerkung = '';
    private $aktiv = 0;
    private $startdatum = 0;
    private $preis = 0;
    private $artikelbezeichnung = '';
    private $kundeninfo = '';
    private $anzahl_speisen = 1;
    private $anzahl_boxen = 1;
    private $kitafino_gruppen = '';
    private $tour_id = 0;
    private $besteck = 0;
    private $bemerkung_kunde = '';
    private $staedtischer_kunde = 0;
    private $bio_kunde = 0;
    private $einrichtungsart = '';
    private $fahrerinfo = '';
    private $produktionsreihenfolge = 0;
    private $master = 0;
    private $masternummer = '';
    private $traeger_id = 0;
    private $errors = array();

    function __construct($daten = array()) {
        if (isset($daten['id'])) {
            $this->setId($daten['id']);
        }
        if (isset($daten['einrichtungskategorie_id'])) {
            $this->setEinrichtungskategorieId($daten['einrichtungskategorie_id']);
        }
        if (isset($daten['kundennummer'])) {
            $this->setKundennummer($daten['kundennummer']);
        }
        if (isset($daten['name'])) {
            $this->setName($daten['name']);
        }
        if (isset($daten['asp'])) {
            $this->setAsp($daten['asp']);
        }
        if (isset($daten['strasse'])) {
            $this->setStrasse($daten['strasse']);
        }
        if (isset($daten['plz'])) {
            $this->setPlz($daten['plz']);
        }
        if (isset($daten['ort'])) {
            $this->setOrt($daten['ort']);
        }
        if (isset($daten['telefon'])) {
            $this->setTelefon($daten['telefon']);
        }
        if (isset($daten['telefon_2'])) {
            $this->setTelefon2($daten['telefon_2']);
        }
        if (isset($daten['fax'])) {
            $this->setFax($daten['fax']);
        }
        if (isset($daten['email'])) {
            $this->setEmail($daten['email']);
        }
        if (isset($daten['lieferreihenfolge'])) {
            $this->setLieferreihenfolge($daten['lieferreihenfolge']);
        }

        if (isset($daten['essenszeit_h']) && isset($daten['essenszeit_h'])) {
            if ($daten['essenszeit_h'] == '' || $daten['essenszeit_h'] == 0) {
                $daten['essenszeit_h'] = '00';
            }
            $this->setEssenszeit($daten['essenszeit_h'] . $daten['essenszeit_m']);
        }
        if (isset($daten['lexware'])) {
            $this->setLexware($daten['lexware']);
        }
        if (isset($daten['bemerkung'])) {
            $this->setBemerkung($daten['bemerkung']);
        }
        if (isset($daten['aktiv'])) {
            $this->setAktiv($daten['aktiv']);
        }
        if (isset($daten['startdatum'])) {
            $this->setStartdatum($daten['startdatum']);
        }
        if (isset($daten['preis'])) {
            $this->setPreis($daten['preis']);
        }
        if (isset($daten['artikelbezeichnung'])) {
            $this->setArtikelbezeichnung($daten['artikelbezeichnung']);
        }
        if (isset($daten['kundeninfo'])) {
            $this->setKundeninfo($daten['kundeninfo']);
        }
        if (isset($daten['anzahl_speisen'])) {
            $this->setAnzahlSpeisen($daten['anzahl_speisen']);
        }
        if (isset($daten['anzahl_boxen'])) {
            $this->setAnzahlBoxen($daten['anzahl_boxen']);
        }
        if (isset($daten['kitafino_gruppen'])) {
            $this->setKitafinoGruppen($daten['kitafino_gruppen']);
        }
        if (isset($daten['tour_id'])) {
            $this->setTourId($daten['tour_id']);
        }
        if (isset($daten['besteck'])) {
            $this->setBesteck($daten['besteck']);
        }
        if (isset($daten['bemerkung_kunde'])) {
            $this->setBemerkungKunde($daten['bemerkung_kunde']);
        }
        if (isset($daten['staedtischer_kunde'])) {
            $this->setStaedtischerKunde($daten['staedtischer_kunde']);
        }
        if (isset($daten['bio_kunde'])) {
            $this->setBioKunde($daten['bio_kunde']);
        }
        if (isset($daten['einrichtungsart'])) {
            $this->setEinrichtungsart($daten['einrichtungsart']);
        }
        if (isset($daten['fahrerinfo'])) {
            $this->setFahrerinfo($daten['fahrerinfo']);
        }
        if (isset($daten['produktionsreihenfolge'])) {
            $this->setProduktionsreihenfolge($daten['produktionsreihenfolge']);
        }
        if (isset($daten['master'])) {
            $this->setMaster($daten['master']);
        }
        if (isset($daten['masternummer'])) {
            $this->setMasternummer($daten['masternummer']);
        }
        if (isset($daten['traeger_id'])) {
            $this->setTraegerId($daten['traeger_id']);
        }
    }

    function setId($id) {
        $this->id = $id;
    }

    function setEinrichtungskategorieId($einrichtungskategorie_id) {
        if (empty($einrichtungskategorie_id)) {
            $this->errors[] = 'Es wurde keine Mengenkategorie ausgewählt!';
        }
        $this->einrichtungskategorie_id = $einrichtungskategorie_id;
    }

    function setKundennummer($kundennummer) {
        $this->kundennummer = $kundennummer;
    }

    function setName($name) {
        if (empty($name)) {
            $this->errors[] = 'Es wurde kein Einrichtungsname angegeben!';
        }
        $this->name = $name;
    }

    function setAsp($asp) {
        $this->asp = $asp;
    }

    function setStrasse($strasse) {
        $this->strasse = $strasse;
    }

    function setPlz($plz) {
        $this->plz = $plz;
    }

    function setOrt($ort) {
        $this->ort = $ort;
    }

    function setTelefon($telefon) {
        $this->telefon = $telefon;
    }

    function setTelefon2($telefon_2) {
        $this->telefon_2 = $telefon_2;
    }

    function setFax($fax) {
        $this->fax = $fax;
    }

    function setEmail($email) {
        $this->email = $email;
    }

    function setLieferreihenfolge($lieferreihenfolge) {
        $this->lieferreihenfolge = $lieferreihenfolge;
    }

    function setEssenszeit($essenszeit) {

        if (empty($essenszeit)) {
            $this->errors[] = 'Es wurde keine Essenszeit angegeben!';
        }
        $this->essenszeit = $essenszeit;
    }

    function setLexware($lexware) {
        $this->lexware = $lexware;
    }

    function setBemerkung($bemerkung) {
        $this->bemerkung = $bemerkung;
    }

    function setAktiv($aktiv) {
        $this->aktiv = $aktiv;
    }

    function setStartdatum($startdatum) {
        $this->startdatum = $startdatum;
    }

    function setPreis($preis) {

        $preis = str_replace(',', '.', $preis);
        $this->preis = $preis;
    }

    function setArtikelbezeichnung($artikelbezeichnung) {
        $this->artikelbezeichnung = $artikelbezeichnung;
    }

    function setKundeninfo($kundeninfo) {
        $this->kundeninfo = $kundeninfo;
    }

    function setAnzahlSpeisen($anzahl_speisen) {
        $this->anzahl_speisen = $anzahl_speisen;
    }

    function setAnzahlBoxen($anzahl_boxen) {
        $this->anzahl_boxen = $anzahl_boxen;
    }

    function setKitafinoGruppen($kitafino_gruppen) {
        $this->kitafino_gruppen = $kitafino_gruppen;
    }

    function setTourId($tour_id) {
        $this->tour_id = $tour_id;
    }

    function setBesteck($besteck) {
        $this->besteck = $besteck;
    }

    function setBemerkungKunde($bemerkung_kunde) {
        $this->bemerkung_kunde = $bemerkung_kunde;
    }

    function setStaedtischerKunde($staedtischer_kunde) {
        $this->staedtischer_kunde = $staedtischer_kunde;
    }

    function setBioKunde($bio_kunde) {
        $this->bio_kunde = $bio_kunde;
    }

    function setEinrichtungsart($einrichtungsart) {
        $this->einrichtungsart = $einrichtungsart;
    }

    function setFahrerinfo($fahrerinfo) {
        $this->fahrerinfo = $fahrerinfo;
    }

    function setProduktionsreihenfolge($produktionsreihenfolge) {
        $this->produktionsreihenfolge = $produktionsreihenfolge;
    }
    function setMaster($master) {
        $this->master = $master;
    }
    function setMasternummer($masternummer) {
        $this->masternummer = $masternummer;
    }
    function setTraegerId($traeger_id) {
        $this->traeger_id = $traeger_id;
    }

    
    
    
    function getId() {
        return $this->id;
    }

    function getEinrichtungskategorieId() {
        return $this->einrichtungskategorie_id;
    }

    function getKundennummer() {
        return $this->kundennummer;
    }

    function getName() {
        return $this->name;
    }

    function getAsp() {
        return $this->asp;
    }

    function getStrasse() {
        return $this->strasse;
    }

    function getPlz() {
        return $this->plz;
    }

    function getOrt() {
        return $this->ort;
    }

    function getTelefon() {
        return $this->telefon;
    }

    function getTelefon2() {
        return $this->telefon_2;
    }

    function getFax() {
        return $this->fax;
    }

    function getEmail() {
        return $this->email;
    }

    function getLieferreihenfolge() {
        return $this->lieferreihenfolge;
    }

    function getEssenszeit() {
        return $this->essenszeit;
    }

    function getLexware() {
        return $this->lexware;
    }

    function getBemerkung() {
        return $this->bemerkung;
    }

    function getAktiv() {
        return $this->aktiv;
    }

    function getStartdatum() {
        return $this->startdatum;
    }

    function getPreis() {
        return $this->preis;
    }

    function getArtikelbezeichnung() {
        return $this->artikelbezeichnung;
    }

    function getKundeninfo() {
        return $this->kundeninfo;
    }

    function getAnzahlSpeisen() {
        return $this->anzahl_speisen;
    }

    function getAnzahlBoxen() {
        return $this->anzahl_boxen;
    }

    function getKitafinoGruppen() {
        return $this->kitafino_gruppen;
    }

    function getTourId() {
        return $this->tour_id;
    }

    function getBesteck() {
        return $this->besteck;
    }

    function getBemerkungKunde() {
        return $this->bemerkung_kunde;
    }

    function getStaedtischerKunde() {
        return $this->staedtischer_kunde;
    }

    function getBioKunde() {
        return $this->bio_kunde;
    }

    function getEinrichtungsart() {
        return $this->einrichtungsart;
    }

    function getFahrerinfo() {
        return $this->fahrerinfo;
    }

    function getProduktionsreihenfolge() {
        return $this->produktionsreihenfolge;
    }
    function getMaster() {
        return $this->master;
    }
    function getMasternummer() {
        return $this->masternummer;
    }
    function getTraegerId() {
        return $this->traeger_id;
    }


    function istValide() {
        return empty($this->errors);
    }

    function zeigeFehler() {
        return implode('<br />', $this->errors);
    }

}

?>
