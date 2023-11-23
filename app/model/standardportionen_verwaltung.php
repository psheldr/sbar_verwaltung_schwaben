<?php

class StandardportionenVerwaltung {
    private $db = null;

    function __construct($db) {
        $this->db = $db;
    }

    function wandleArrayZuObjekt($standardportionenArray) {
        $standardportionen = new Standardportionen();
        $standardportionen->setId($standardportionenArray['id']);
        $standardportionen->setKundeId($standardportionenArray['kunde_id']);
        $standardportionen->setPortionenMo($standardportionenArray['portionen_mo']);
        $standardportionen->setPortionenDi($standardportionenArray['portionen_di']);
        $standardportionen->setPortionenMi($standardportionenArray['portionen_mi']);
        $standardportionen->setPortionenDo($standardportionenArray['portionen_do']);
        $standardportionen->setPortionenFr($standardportionenArray['portionen_fr']);
        $standardportionen->setSpeiseNr($standardportionenArray['speise_nr']);
        return $standardportionen;
    }

    function wandleSqlZuObjekten($sql, $bedingungen = array()) {
        $abfrage = $this->db->prepare($sql);
        $abfrage->execute($bedingungen);
        $standardportionen_pl_Array = $abfrage->fetchAll();
        $standardportionenObjekte = array();
        foreach($standardportionen_pl_Array as $standardportionenArray) {
            $standardportionenObjekte[]= $this->wandleArrayZuObjekt($standardportionenArray);
        }
        return $standardportionenObjekte;
    }

    function findeAlle() {
        $sql = "SELECT * FROM standardportionen";
        return $this->wandleSqlZuObjekten($sql);
    }


    function findeAlleZuKundenId($kid) {
        $sql = "SELECT * FROM standardportionen WHERE kunde_id=$kid";
        return $this->wandleSqlZuObjekten($sql);
    }
    
    function findeAnhandVonId($id) {
        $abfrage = $this->db->prepare($sql);
        $abfrage->execute(array($id));
        $standardportionenArray = $abfrage->fetch();
        return $this->wandleArrayZuObjekt($standardportionenArray);
    }
    function findeAnhandVonKundenIdUndSpeiseNr($kid, $speise_nr = 1) {
        $sql = "SELECT * FROM standardportionen WHERE kunde_id=? AND speise_nr=?";
        $abfrage = $this->db->prepare($sql);
        $abfrage->execute(array($kid, $speise_nr));
        $standardportionenArray = $abfrage->fetch();
        return $this->wandleArrayZuObjekt($standardportionenArray);
    }
    function findeAnhandVonKundenId($kid) {
        $sql = "SELECT * FROM standardportionen WHERE kunde_id=?";
        $abfrage = $this->db->prepare($sql);
        $abfrage->execute(array($kid));
        $standardportionenArray = $abfrage->fetch();
        return $this->wandleArrayZuObjekt($standardportionenArray);
    }


    function fuegeStandardportionenHinzu(Standardportionen $standardportionen) {
        $sql = "INSERT INTO standardportionen (kunde_id, portionen_mo, portionen_di, portionen_mi, portionen_do, portionen_fr, speise_nr) VALUES (?, ?, ?, ?, ?, ?, ?)";
        $abfrage = $this->db->prepare($sql);
        $abfrage->execute(array(
                $standardportionen->getKundeId(),
                $standardportionen->getPortionenMo(),
                $standardportionen->getPortionenDi(),
                $standardportionen->getPortionenMi(),
                $standardportionen->getPortionenDo(),
                $standardportionen->getPortionenFr(),
                $standardportionen->getSpeiseNr()
            ));
        $standardportionen->setId($this->db->lastInsertId());
    }

    function aendereStandardportionen(Standardportionen $standardportionen) {
        $sql = "UPDATE standardportionen SET kunde_id=?,portionen_mo=?,portionen_di=?,portionen_mi=?,portionen_do=?,portionen_fr=?,speise_nr=? WHERE id=?";
        $abfrage = $this->db->prepare($sql);
        $daten = array(
            $standardportionen->getKundeId(),
            $standardportionen->getPortionenMo(),
            $standardportionen->getPortionenDi(),
            $standardportionen->getPortionenMi(),
            $standardportionen->getPortionenDo(),
            $standardportionen->getPortionenFr(),
            $standardportionen->getSpeiseNr(),
            $standardportionen->getId()
        );
        $abfrage->execute($daten);
        return true;
    }

    function speichere(Standardportionen $standardportionen) {
        if (!$standardportionen->istValide()) {
            return false;
        }
        if ($standardportionen->getId()) {
            $this->aendereStandardportionen($standardportionen);
        } else {
            $this->fuegeStandardportionenHinzu($standardportionen);
        }
        return true;
    }

    function loesche(Standardportionen $standardportionen) {
        $sql = "DELETE FROM standardportionen WHERE id=?";
        $abfrage = $this->db->prepare($sql);
        $abfrage->execute(array($standardportionen->getId()));
    }
}

?>
