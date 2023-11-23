<?php

class AbrechnungstagVerwaltung {
    private $db = null;

    function __construct($db) {
        $this->db = $db;
    }

    function wandleArrayZuObjekt($abrechnungstagArray) {
        $abrechnungstag = new Abrechnungstag();
        $abrechnungstag->setId($abrechnungstagArray['id']);
        $abrechnungstag->setKundeId($abrechnungstagArray['kunde_id']);
        $abrechnungstag->setTag($abrechnungstagArray['tag']);
        $abrechnungstag->setPortionen($abrechnungstagArray['portionen']);
        $abrechnungstag->setSpeisenIds($abrechnungstagArray['speisen_ids']);
        $abrechnungstag->setTag2($abrechnungstagArray['tag2']);
        $abrechnungstag->setMonat($abrechnungstagArray['monat']);
        $abrechnungstag->setJahr($abrechnungstagArray['jahr']);
        return $abrechnungstag;
    }

    function wandleSqlZuObjekten($sql, $bedingungen = array()) {
        $abfrage = $this->db->prepare($sql);
        $abfrage->execute($bedingungen);
        $abrechnungstageArray = $abfrage->fetchAll();
        $abrechnungstageObjekte = array();
        foreach ($abrechnungstageArray as $abrechnungstagArray) {
            $abrechnungstageObjekte[] = $this->wandleArrayZuObjekt($abrechnungstagArray);
        }
        return $abrechnungstageObjekte;
    }

    function findeAlle() {
        $sql = "SELECT * FROM abrechnungstag ORDER BY tag DESC";
        return $this->wandleSqlZuObjekten($sql);
    }


    function findeAlleZuZeitraumUndKunde($von,$bis,$kunde_id) {
        $sql = "SELECT * FROM abrechnungstag WHERE tag >= $von AND tag <= $bis AND kunde_id=$kunde_id ORDER BY tag ASC";
        return $this->wandleSqlZuObjekten($sql);
    }

    function findeAlleZuMonatUndKunde($monat,$jahr,$kunde_id) {
        $sql = "SELECT * FROM abrechnungstag WHERE monat = $monat AND jahr = $jahr AND kunde_id=$kunde_id ORDER BY tag2 ASC";
        return $this->wandleSqlZuObjekten($sql);
    }

    function findeAlleZuKundenId($kunde_id) {
        $sql = "SELECT * FROM abrechnungstag WHERE kunde_id=$kunde_id";
        return $this->wandleSqlZuObjekten($sql);
    }
 

    function findeAnhandVonId($id) {
        $sql = "SELECT * FROM abrechnungstag WHERE id=?";
        $abfrage = $this->db->prepare($sql);
        $abfrage->execute(array($id));
        $abrechnungstagArray = $abfrage->fetch();
        return $this->wandleArrayZuObjekt($abrechnungstagArray);
    }

    function findeAnhandVonKundeIdUndTag($kunde_id, $tag) {
        $sql = "SELECT * FROM abrechnungstag WHERE kunde_id=? AND tag=?";
        $abfrage = $this->db->prepare($sql);
        $abfrage->execute(array($kunde_id, $tag));
        $abrechnungstagArray = $abfrage->fetch();
        return $this->wandleArrayZuObjekt($abrechnungstagArray);
    }

      function findeAnhandVonKundeIdUndTagMonatJahr($kunde_id, $tag, $monat, $jahr) {

         $tag = $tag*1;
         $monat = $monat*1;
        $sql = "SELECT * FROM abrechnungstag WHERE kunde_id=? AND tag2=? AND monat=? AND jahr=?";
        $abfrage = $this->db->prepare($sql);
        $abfrage->execute(array($kunde_id, $tag, $monat, $jahr));
        $abrechnungstagArray = $abfrage->fetch();
        return $this->wandleArrayZuObjekt($abrechnungstagArray);
    }


    function fuegeAbrechnungstagHinzu(Abrechnungstag $abrechnungstag) {
        $sql = "INSERT INTO abrechnungstag (kunde_id, tag, portionen, speisen_ids, tag2, monat, jahr) VALUES (?,?,?,?,?,?,?)";
        $abfrage = $this->db->prepare($sql);
        $abfrage->execute(array(
                $abrechnungstag->getKundeId(),
                $abrechnungstag->getTag(),
                $abrechnungstag->getPortionen(),
                $abrechnungstag->getSpeisenIds(),
                $abrechnungstag->getTag2(),
                $abrechnungstag->getMonat(),
                $abrechnungstag->getJahr()
            ));
        $abrechnungstag->setId($this->db->lastInsertId());
    }

    function aendereAbrechnungstag(Abrechnungstag $abrechnungstag) {
        $sql = "UPDATE abrechnungstag SET kunde_id=?, tag=?, portionen=?, speisen_ids=?, tag2=?, monat=?, jahr=? WHERE id=?";
        $abfrage = $this->db->prepare($sql);
        $daten = array(
            $abrechnungstag->getKundeId(),
            $abrechnungstag->getTag(),
            $abrechnungstag->getPortionen(),
            $abrechnungstag->getSpeisenIds(),
            $abrechnungstag->getTag2(),
            $abrechnungstag->getMonat(),
            $abrechnungstag->getJahr(),
            $abrechnungstag->getId()
        );
        $abfrage->execute($daten);
        return true;
    }

    function speichere(Abrechnungstag $abrechnungstag) {
        if (!$abrechnungstag->istValide()) {
            return false;
        }
        if ($abrechnungstag->getId()) {
            $this->aendereAbrechnungstag($abrechnungstag);
        } else {
            $this->fuegeAbrechnungstagHinzu($abrechnungstag);
        }
        return true;
    }

    function loesche(Abrechnungstag $abrechnungstag) {
        $sql = "DELETE FROM abrechnungstag WHERE id=?";
        $abfrage = $this->db->prepare($sql);
        $abfrage->execute(array($abrechnungstag->getId()));
    }
}

?>
