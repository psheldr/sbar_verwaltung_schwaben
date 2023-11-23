<?php
class KundeVerwaltung {
    private $db = null;
    
    function __construct($db) {
        $this->db = $db;
    }
    
    function wandleArrayZuObjekt($kundeArray) {
        $kunde = New Kunde() ;
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

    function findeAlle($sort = '') {
        if ($sort == '') {
            $sort = 'lieferreihenfolge';
        }
        $sql = "SELECT * FROM kunde WHERE einrichtungskategorie_id != 5 ORDER BY $sort ASC";
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
        $sql = "SELECT * FROM kunde WHERE (aktiv=1 OR (aktiv=0 AND startdatum > 0)) ORDER BY $sort ASC";
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
        $sql = "SELECT * FROM kunde WHERE einrichtungskategorie_id=$katid";
        return $this->wandleSqlZuObjekten($sql);
    }
function findeAlleInaktivenMitStartdatum() {
    $heute = time();
        $sql = "SELECT * FROM kunde WHERE aktiv=0 AND startdatum <= $heute";
        return $this->wandleSqlZuObjekten($sql);
    }
    function findeAnhandVonId($id) {
        $sql = "SELECT * FROM kunde WHERE id=?";
        $abfrage = $this->db->prepare($sql);
        $abfrage->execute(array($id));
        $kundeArray = $abfrage->fetch();
        return $this->wandleArrayZuObjekt($kundeArray);
    }

    function fuegeKundeHinzu(Kunde $kunde) {
        $sql = "INSERT INTO kunde (einrichtungskategorie_id,kundennummer, name, asp, strasse, plz, ort, telefon, telefon_2, fax, email, lieferreihenfolge, essenszeit,lexware, bemerkung, aktiv, startdatum, preis, artikelbezeichnung, kundeninfo) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
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
                $kunde->getKundeninfo()
            ));
        $kunde->setId($this->db->lastInsertId());
    }

    function aendereKunde(Kunde $kunde) {
        $sql = "UPDATE kunde SET einrichtungskategorie_id=?, kundennummer=?, name=?, asp=?, strasse=?, plz=?, ort=?, telefon=?, telefon_2=?, fax=?, email=?, lieferreihenfolge=?, essenszeit=?, lexware=?, bemerkung=?, aktiv=?, startdatum=?, preis=?, artikelbezeichnung=?, kundeninfo=? WHERE id=?";
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
