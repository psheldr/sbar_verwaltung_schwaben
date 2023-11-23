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
        $abrechnungstag->setSpeiseNr($abrechnungstagArray['speise_nr']);
        $abrechnungstag->setLieferschein($abrechnungstagArray['lieferschein']);
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

    function findeAlleZuMonatAbTagImJahr($tag, $monat,$jahr,$kunde_id) {
        $sql = "SELECT * FROM abrechnungstag WHERE monat = $monat AND jahr = $jahr AND tag2 > $tag  AND kunde_id=$kunde_id";
        return $this->wandleSqlZuObjekten($sql);
        
    }
    function findeAlleNachMonatJahrZuKunde($monat,$jahr,$kunde_id) {
        $sql = "SELECT * FROM abrechnungstag WHERE monat > $monat AND jahr >= $jahr AND kunde_id=$kunde_id";
        //var_dump($sql);
        return $this->wandleSqlZuObjekten($sql);
    }
    function findeAlleFuerAbrechnung($monat = 0, $jahr = 0, $stadtkunden_ids_array = []) {
        $stadtkunden_ids_string = "'".implode("', '",$stadtkunden_ids_array)."'";
        $sql = "SELECT * FROM abrechnungstag WHERE monat = $monat AND jahr = $jahr AND kunde_id IN ($stadtkunden_ids_string) ORDER BY kunde_id, speise_nr, tag ";
        return $this->wandleSqlZuObjekten($sql);
    }

    function findeAlleZuMonatUndKunde($monat,$jahr,$kunde_id) {
        $sql = "SELECT * FROM abrechnungstag WHERE monat = $monat AND jahr = $jahr AND kunde_id=$kunde_id ORDER BY tag2 ASC, speise_nr ASC";
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

      function findeAnhandVonKundeIdUndTagMonatJahr($kunde_id, $tag, $monat, $jahr, $speise_nr = 1) {

        $sql = "SELECT * FROM abrechnungstag WHERE kunde_id=? AND tag2=? AND monat=? AND jahr=? AND speise_nr=?";
        $abfrage = $this->db->prepare($sql);
        $abfrage->execute(array($kunde_id, $tag, $monat, $jahr, $speise_nr));
        $abrechnungstagArray = $abfrage->fetch();
        return $this->wandleArrayZuObjekt($abrechnungstagArray);
    }
    function findeAlleZuKundeIdUndTagMonatJahr($kunde_id, $tag, $monat, $jahr) {
        $sql = "SELECT * FROM abrechnungstag WHERE kunde_id=$kunde_id AND tag2=$tag AND monat=$monat AND jahr=$jahr ORDER BY speise_nr";
        return $this->wandleSqlZuObjekten($sql);
    }


    function fuegeAbrechnungstagHinzu(Abrechnungstag $abrechnungstag) {
        $sql = "INSERT INTO abrechnungstag (kunde_id, tag, portionen, speisen_ids, tag2, monat, jahr, speise_nr, lieferschein) VALUES (?,?,?,?,?,?,?,?,?)";
        $abfrage = $this->db->prepare($sql);
        $abfrage->execute(array(
                $abrechnungstag->getKundeId(),
                $abrechnungstag->getTag(),
                $abrechnungstag->getPortionen(),
                $abrechnungstag->getSpeisenIds(),
                $abrechnungstag->getTag2(),
                $abrechnungstag->getMonat(),
                $abrechnungstag->getJahr(),
                $abrechnungstag->getSpeiseNr(),
                $abrechnungstag->getLieferschein()
            ));
        $abrechnungstag->setId($this->db->lastInsertId());
    }

    function aendereAbrechnungstag(Abrechnungstag $abrechnungstag) {
        $sql = "UPDATE abrechnungstag SET kunde_id=?, tag=?, portionen=?, speisen_ids=?, tag2=?, monat=?, jahr=?, speise_nr=?, lieferschein=? WHERE id=?";
        $abfrage = $this->db->prepare($sql);
        $daten = array(
            $abrechnungstag->getKundeId(),
            $abrechnungstag->getTag(),
            $abrechnungstag->getPortionen(),
            $abrechnungstag->getSpeisenIds(),
            $abrechnungstag->getTag2(),
            $abrechnungstag->getMonat(),
            $abrechnungstag->getJahr(),
            $abrechnungstag->getSpeiseNr(),
            $abrechnungstag->getLieferschein(),
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
