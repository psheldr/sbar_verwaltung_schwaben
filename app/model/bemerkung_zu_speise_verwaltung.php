<?php

class BemerkungZuSpeiseVerwaltung {
    private $db = null;

    function __construct($db) {
        $this->db = $db;
    }

    function wandleArrayZuObjekt($bemerkung_zu_speise_Array) {
        $bemerkung_zu_speise = new BemerkungZuSpeise();
        $bemerkung_zu_speise->setId($bemerkung_zu_speise_Array['id']);
        $bemerkung_zu_speise->setKundeId($bemerkung_zu_speise_Array['kunde_id']);
        $bemerkung_zu_speise->setSpeiseId($bemerkung_zu_speise_Array['speise_id']);
        $bemerkung_zu_speise->setBemerkung($bemerkung_zu_speise_Array['bemerkung']);
        return $bemerkung_zu_speise;
    }

    function wandleSqlZuObjekten($sql, $bedingungen = array()) {
        $abfrage = $this->db->prepare($sql);
        $abfrage->execute($bedingungen);
        $bemerkungen_zu_speise_Array = $abfrage->fetchAll();
        $bemerkungen_zu_speiseObjekte = array();
        foreach ($bemerkungen_zu_speise_Array as $bemerkungen_zu_speise_Array) {
            $bemerkungen_zu_speiseObjekte[] = $this->wandleArrayZuObjekt($bemerkungen_zu_speise_Array);
        }
        return $bemerkungen_zu_speiseObjekte;
    }

    function findeAlle() {
        $sql = "SELECT * FROM bemerkung_zu_speise";
        return $this->wandleSqlZuObjekten($sql);
    }
 function findeAlleZuKundenId($kunde_id) {
        $sql = "SELECT * FROM bemerkung_zu_speise WHERE kunde_id=$kunde_id";
        return $this->wandleSqlZuObjekten($sql);
    }
     function findeAlleZuSpeiseId($speise_id) {
        $sql = "SELECT * FROM bemerkung_zu_speise WHERE speise_id=$speise_id";
        return $this->wandleSqlZuObjekten($sql);
    }
    function findeAnhandVonId($id) {
        $sql = "SELECT * FROM bemerkung_zu_speise WHERE id=$id";
        return $this->wandleSqlZuObjekten($sql);
    }

    function findeAnhandVonKundeIdUndSpeiseId($kunde_id, $speise_id) {
        $sql = "SELECT * FROM bemerkung_zu_speise WHERE kunde_id=? AND speise_id=?";
        $abfrage = $this->db->prepare($sql);
        $abfrage->execute(array($kunde_id, $speise_id));
        $bemerkungen_zu_speise_Array = $abfrage->fetch();
        return $this->wandleArrayZuObjekt($bemerkungen_zu_speise_Array);
    }

    function fuegeBemerkungZuSpeiseHinzu(BemerkungZuSpeise $bemerkung_zu_speise) {
        $sql = "INSERT INTO bemerkung_zu_speise (speise_id, kunde_id, bemerkung) VALUES (?,?,?)";
        $abfrage = $this->db->prepare($sql);
        $abfrage->execute(array(
            $bemerkung_zu_speise->getSpeiseId(),
            $bemerkung_zu_speise->getKundeId(),
            $bemerkung_zu_speise->getBemerkung()
        ));
        $bemerkung_zu_speise->setId($this->db->lastInsertId());
    }

    function aendereBemerkungZuSpeise(BemerkungZuSpeise $bemerkung_zu_speise) {
        $sql = "UPDATE bemerkung_zu_speise SET kunde_id=?, speise_id=?, bemerkung=? WHERE id=?";
        $abfrage = $this->db->prepare($sql);
        $daten = array(
            $bemerkung_zu_speise->getKundeId(),
            $bemerkung_zu_speise->getSpeiseId(),
            $bemerkung_zu_speise->getBemerkung(),
            $bemerkung_zu_speise->getId()
        );
        $abfrage->execute($daten);
        return true;
    }

    function speichere(BemerkungZuSpeise $bemerkung_zu_speise) {
        if (!$bemerkung_zu_speise->istValide()) {
            return false;
        }
        if ($bemerkung_zu_speise->getId()) {
            $this->aendereBemerkungZuSpeise($bemerkung_zu_speise);
        } else {
            $this->fuegeBemerkungZuSpeiseHinzu($bemerkung_zu_speise);
        }
        return true;
    }

    function loesche(BemerkungZuSpeise $bemerkung_zu_speise) {
        $sql = "DELETE FROM bemerkung_zu_speise WHERE id=?";
        $abfrage = $this->db->prepare($sql);
        $abfrage->execute(array($bemerkung_zu_speise->getId()));
    }

    
}
?>