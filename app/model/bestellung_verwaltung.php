<?php

class BestellungVerwaltung {
    private $db = null;

    function __construct($db) {
        $this->db = $db;
    }

    function wandleArrayZuObjekt($bestellungArray) {
        $bestellung = new Bestellung();
        $bestellung->setId($bestellungArray['id']);
        $bestellung->setKundeId($bestellungArray['kunde_id']);
        $bestellung->setTag($bestellungArray['tag']);
        $bestellung->setTag2($bestellungArray['tag2']);
        $bestellung->setMonat($bestellungArray['monat']);
        $bestellung->setJahr($bestellungArray['jahr']);
        return $bestellung;
    }

    function wandleSqlZuObjekten($sql, $bedingungen = array()) {
        $abfrage = $this->db->prepare($sql);
        $abfrage->execute($bedingungen);
        $bestellungenArray = $abfrage->fetchAll();
        $bestellungenObjekte = array();
        foreach ($bestellungenArray as $bestellungArray) {
            $bestellungenObjekte[] = $this->wandleArrayZuObjekt($bestellungArray);
        }
        return $bestellungenObjekte;
    }

    function findeAlle() {
        $sql = "SELECT * FROM bestellung";
        return $this->wandleSqlZuObjekten($sql);
    }

    function findeAlleZuTag($ts) {
        $sql = "SELECT * FROM bestellung WHERE tag=$ts";
        return $this->wandleSqlZuObjekten($sql);
    }

    function findeAlleZuDatum($tag,$monat,$jahr) {
        $tag = $tag*1;
        $monat = $monat*1;
        $sql = "SELECT * FROM bestellung WHERE tag2=$tag AND monat=$monat AND jahr=$jahr";
        return $this->wandleSqlZuObjekten($sql);
    }

     function findeAlleZuKundenId($kunde_id) {
        $sql = "SELECT * FROM bestellung WHERE kunde_id=$kunde_id";
        return $this->wandleSqlZuObjekten($sql);
    }

    function findeAlleZuZeitraum($von, $bis) {
        $sql = "SELECT * FROM bestellung WHERE tag>=$von AND tag <=$bis";
        return $this->wandleSqlZuObjekten($sql);
    }

    function findeAlleZuMonat($monat, $jahr) {
        $sql = "SELECT * FROM bestellung WHERE monat = $monat AND jahr = $jahr";
        return $this->wandleSqlZuObjekten($sql);
    }
 

     function findeAnhandVonTag($ts) {
        $sql = "SELECT * FROM bestellung WHERE tag=$ts";
        $abfrage = $this->db->prepare($sql);
        $abfrage->execute(array($ts));
        $bestellungArray = $abfrage->fetch();
        return $this->wandleArrayZuObjekt($bestellungArray);
    }
     function findeAnhandVonTagMonatJahr($tag, $monat, $jahr) {
        $sql = "SELECT * FROM bestellung WHERE tag2 = ? AND monat = ? AND jahr = ? ORDER BY id DESC";
        $abfrage = $this->db->prepare($sql);
        $abfrage->execute(array($tag, $monat, $jahr));
        $bestellungArray = $abfrage->fetch();
        return $this->wandleArrayZuObjekt($bestellungArray);
    }

    function findeAnhandVonId($id) {
        $sql = "SELECT * FROM bestellung WHERE id=?";
        $abfrage = $this->db->prepare($sql);
        $abfrage->execute(array($id));
        $bestellungArray = $abfrage->fetch();
        return $this->wandleArrayZuObjekt($bestellungArray);
    }

    function fuegeBestellungHinzu(Bestellung $bestellung) {
        $sql = "INSERT INTO bestellung (kunde_id, tag, tag2, monat, jahr) VALUES (?,?,?,?,?)";
        $abfrage = $this->db->prepare($sql);
        $abfrage->execute(array(
                $bestellung->getKundeId(),
                $bestellung->getTag(),
                $bestellung->getTag2(),
                $bestellung->getMonat(),
                $bestellung->getJahr()
            ));
        $bestellung->setId($this->db->lastInsertId());
    }

    function aendereBestellung(Bestellung $bestellung) {
        $sql = "UPDATE bestellung SET kunde_id=?, tag=?, tag2=?, monat=?, jahr=? WHERE id=?";
        $abfrage = $this->db->prepare($sql);
        $daten = array(
            $bestellung->getKundeId(),
            $bestellung->getTag(),
            $bestellung->getTag2(),
            $bestellung->getMonat(),
            $bestellung->getJahr(),
            $bestellung->getId()
        );
        $abfrage->execute($daten);
        return true;
    }

    function speichere(Bestellung $bestellung) {
        if (!$bestellung->istValide()) {
            return false;
        }
        if ($bestellung->getId()) {
            $this->aendereBestellung($bestellung);
        } else {
            $this->fuegeBestellungHinzu($bestellung);
        }
        return true;
    }

     function loesche(Bestellung $bestellung) {
        $sql = "DELETE FROM bestellung WHERE id=?";
        $abfrage = $this->db->prepare($sql);
        $abfrage->execute(array($bestellung->getId()));
    }
}

?>
