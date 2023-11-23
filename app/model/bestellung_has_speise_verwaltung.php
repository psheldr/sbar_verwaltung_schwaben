<?php

class BestellungHasSpeiseVerwaltung {
    private $db = null;

    function __construct($db) {
        $this->db = $db;
    }

    function wandleArrayZuObjekt($bestellung_has_speise_Array) {
        $bestellung_has_speise = new BestellungHasSpeise();
        $bestellung_has_speise->setBestellungId($bestellung_has_speise_Array['bestellung_id']);
        $bestellung_has_speise->setSpeiseId($bestellung_has_speise_Array['speise_id']);
        $bestellung_has_speise->setSpeiseNr($bestellung_has_speise_Array['speise_nr']);
        $bestellung_has_speise->setKitafinoSpeiseNr($bestellung_has_speise_Array['kitafino_speise_nr']);
        return $bestellung_has_speise;
    }

    function wandleSqlZuObjekten($sql, $bedingungen = array()) {
        $abfrage = $this->db->prepare($sql);
        $abfrage->execute($bedingungen);
        $bestellung_has_speisen_Array = $abfrage->fetchAll();
        $bestellung_has_speiseObjekte = array();
        foreach ($bestellung_has_speisen_Array as $bestellung_has_speise_Array) {
            $bestellung_has_speiseObjekte[] = $this->wandleArrayZuObjekt($bestellung_has_speise_Array);
        }
        return $bestellung_has_speiseObjekte;
    }

    function findeAlle() {
        $sql = "SELECT * FROM bestellung_has_speise";
        return $this->wandleSqlZuObjekten($sql);
    }

    function findeAnhandVonBestellungId($id) {
        $sql = "SELECT * FROM bestellung_has_speise WHERE bestellung_id=$id ORDER by speise_nr,bestellung_id";
        return $this->wandleSqlZuObjekten($sql);
    }
    function findeAnhandVonBestellungIdUndSpeiseNr($id, $speise_nr = 1) {
        $sql = "SELECT * FROM bestellung_has_speise WHERE bestellung_id=$id AND speise_nr=$speise_nr ORDER by speise_nr,bestellung_id";
        return $this->wandleSqlZuObjekten($sql);
    }

    function findeAnhandVonBestellungIdUndSpeiseIdUndSpeiseNr($bestellung_id, $speise_id, $speise_nr) {
        $sql = "SELECT * FROM bestellung_has_speise WHERE bestellung_id=? AND speise_id=? AND speise_nr = ?";
        $abfrage = $this->db->prepare($sql);
        $abfrage->execute(array($bestellung_id, $speise_id, $speise_nr));
        $bestellung_has_speiseArray = $abfrage->fetch();
        return $this->wandleArrayZuObjekt($bestellung_has_speiseArray);
    }
    function findeAnhandVonBestellungIdUndSpeiseId($bestellung_id, $speise_id) {
        $sql = "SELECT * FROM bestellung_has_speise WHERE bestellung_id=? AND speise_id=?";
        $abfrage = $this->db->prepare($sql);
        $abfrage->execute(array($bestellung_id, $speise_id));
        $bestellung_has_speiseArray = $abfrage->fetch();
        return $this->wandleArrayZuObjekt($bestellung_has_speiseArray);
    }

    function fuegeBestellungHasSpeiseHinzu(BestellungHasSpeise $bestellung_has_speise) {
        $sql = "INSERT INTO bestellung_has_speise (bestellung_id, speise_id, speise_nr, kitafino_speise_nr) VALUES (?,?,?,?)";
        $abfrage = $this->db->prepare($sql);
        $abfrage->execute(array(
            $bestellung_has_speise->getBestellungId(),
            $bestellung_has_speise->getSpeiseId(),
            $bestellung_has_speise->getSpeiseNr(),
            $bestellung_has_speise->getKitafinoSpeiseNr()
        ));
    }

    function aendereBestellungHasSpeise(BestellungHasSpeise $bestellung_has_speise) {
        $sql = "UPDATE bestellung_has_speise SET bestellung_id=?, speise_id=?, speise_nr=?, kitafino_speise_nr=? WHERE id=?";
        $abfrage = $this->db->prepare($sql);
        $daten = array(
            $bestellung_has_speise->getBestellungId(),
            $bestellung_has_speise->getSpeiseId(),
            $bestellung_has_speise->getSpeiseNr(),
            $bestellung_has_speise->getKitafinoSpeiseNr()
        );
        $abfrage->execute($daten);
        return true;
    }

    function speichere(BestellungHasSpeise $bestellung_has_speise) {
        if (!$bestellung_has_speise->istValide()) {
            return false;
        }
        if ($bestellung_has_speise->getId()) {
            $this->aendereBestellungHasSpeise($bestellung_has_speise);
        } else {
            $this->fuegeBestellungHasSpeiseHinzu($bestellung_has_speise);
        }
        return true;
    }

    function loescheAlleZuBestellung($bestellung_id) {
        $sql = "DELETE FROM bestellung_has_speise WHERE bestellung_id=?";
        $abfrage = $this->db->prepare($sql);
        $abfrage->execute(array($bestellung_id));
    }
}
?>