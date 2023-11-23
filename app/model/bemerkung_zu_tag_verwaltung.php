<?php

class BemerkungZuTagVerwaltung {
    private $db = null;

    function __construct($db) {
        $this->db = $db;
    }

    function wandleArrayZuObjekt($bemerkung_zu_tag_Array) {
        $bemerkung_zu_tag = new BemerkungZuTag();
        $bemerkung_zu_tag->setId($bemerkung_zu_tag_Array['id']);
        $bemerkung_zu_tag->setSpeiseId($bemerkung_zu_tag_Array['speise_id']);
        $bemerkung_zu_tag->setKundeId($bemerkung_zu_tag_Array['kunde_id']);
        $bemerkung_zu_tag->setBemerkung($bemerkung_zu_tag_Array['bemerkung']);
        $bemerkung_zu_tag->setTag($bemerkung_zu_tag_Array['tag']);
        $bemerkung_zu_tag->setTag2($bemerkung_zu_tag_Array['tag2']);
        $bemerkung_zu_tag->setMonat($bemerkung_zu_tag_Array['monat']);
        $bemerkung_zu_tag->setJahr($bemerkung_zu_tag_Array['jahr']);
        return $bemerkung_zu_tag;
    }

    function wandleSqlZuObjekten($sql, $bedingungen = array()) {
        $abfrage = $this->db->prepare($sql);
        $abfrage->execute($bedingungen);
        $bemerkungen_zu_tag_Array = $abfrage->fetchAll();
        $bemerkungen_zu_tagObjekte = array();
        foreach ($bemerkungen_zu_tag_Array as $bemerkungen_zu_tag_Array) {
            $bemerkungen_zu_tagObjekte[] = $this->wandleArrayZuObjekt($bemerkungen_zu_tag_Array);
        }
        return $bemerkungen_zu_tagObjekte;
    }

    function findeAlle() {
        $sql = "SELECT * FROM bemerkung_zu_tag";
        return $this->wandleSqlZuObjekten($sql);
    }
 function findeAlleZuKundenId($kunde_id) {
        $sql = "SELECT * FROM bemerkung_zu_tag WHERE kunde_id=$kunde_id";
        return $this->wandleSqlZuObjekten($sql);
    }
    function findeAnhandVonId($id) {
        $sql = "SELECT * FROM bemerkung_zu_tag WHERE id=$id";
        return $this->wandleSqlZuObjekten($sql);
    }

    function findeAnhandVonKundeIdUndTagUndSpeiseId($kunde_id, $tag, $speise_id) {
        $sql = "SELECT * FROM bemerkung_zu_tag WHERE kunde_id=? AND tag=? AND speise_id=?";
        $abfrage = $this->db->prepare($sql);
        $abfrage->execute(array($kunde_id, $tag, $speise_id));
        $bemerkungen_zu_tag_Array = $abfrage->fetch();
        return $this->wandleArrayZuObjekt($bemerkungen_zu_tag_Array);
    }

     function findeAnhandVonKundeIdUndDatumUndSpeiseId($kunde_id, $tag, $monat, $jahr, $speise_id) {
        $sql = "SELECT * FROM bemerkung_zu_tag WHERE kunde_id=? AND tag2=? AND monat=? AND jahr=? AND speise_id=?";
        $abfrage = $this->db->prepare($sql);
        $abfrage->execute(array($kunde_id, $tag, $monat, $jahr, $speise_id));
        $bemerkungen_zu_tag_Array = $abfrage->fetch();
        return $this->wandleArrayZuObjekt($bemerkungen_zu_tag_Array);
    }

    function fuegeBemerkungZuTagHinzu(BemerkungZuTag $bemerkung_zu_tag) {
        $sql = "INSERT INTO bemerkung_zu_tag (speise_id, kunde_id, bemerkung, tag, tag2, monat, jahr) VALUES (?,?,?,?,?,?,?)";
        $abfrage = $this->db->prepare($sql);
        $abfrage->execute(array(
            $bemerkung_zu_tag->getSpeiseId(),
            $bemerkung_zu_tag->getKundeId(),
            $bemerkung_zu_tag->getBemerkung(),
            $bemerkung_zu_tag->getTag(),
            $bemerkung_zu_tag->getTag2(),
            $bemerkung_zu_tag->getMonat(),
            $bemerkung_zu_tag->getJahr()
        ));
        $bemerkung_zu_tag->setId($this->db->lastInsertId());
    }

    function aendereBemerkungZuTag(BemerkungZuTag $bemerkung_zu_tag) {
        $sql = "UPDATE bemerkung_zu_tag SET speise_id=?, kunde_id=?, bemerkung=?, tag=?, tag2=?, monat=?, jahr=? WHERE id=?";
        $abfrage = $this->db->prepare($sql);
        $daten = array(
            $bemerkung_zu_tag->getSpeiseId(),
            $bemerkung_zu_tag->getKundeId(),
            $bemerkung_zu_tag->getBemerkung(),
            $bemerkung_zu_tag->getTag(),
            $bemerkung_zu_tag->getTag2(),
            $bemerkung_zu_tag->getMonat(),
            $bemerkung_zu_tag->getJahr(),
            $bemerkung_zu_tag->getId()
        );
        $abfrage->execute($daten);
        return true;
    }

    function speichere(BemerkungZuTag $bemerkung_zu_tag) {
        if (!$bemerkung_zu_tag->istValide()) {
            return false;
        }
        if ($bemerkung_zu_tag->getId()) {
            $this->aendereBemerkungZuTag($bemerkung_zu_tag);
        } else {
            $this->fuegeBemerkungZuTagHinzu($bemerkung_zu_tag);
        }
        return true;
    }

    function loesche(BemerkungZuTag $bemerkung_zu_tag) {
        $sql = "DELETE FROM bemerkung_zu_tag WHERE id=?";
        $abfrage = $this->db->prepare($sql);
        $abfrage->execute(array($bemerkung_zu_tag->getId()));
    }

    
}
?>