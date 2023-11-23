<?php
class InfotickerVerwaltung {
    private $db = null;

    function __construct($db) {
        $this->db = $db;
    }

    function wandleArrayZuObjekt($infotickerArray) {
        $infoticker = new Infoticker();
        $infoticker->setId($infotickerArray['id']);
        $infoticker->setTag($infotickerArray['tag']);
        $infoticker->setMonat($infotickerArray['monat']);
        $infoticker->setJahr($infotickerArray['jahr']);
        $infoticker->setDatumTs($infotickerArray['datum_ts']);
        $infoticker->setEingetragen($infotickerArray['eingetragen']);
        $infoticker->setText($infotickerArray['text']);
        $infoticker->setErledigt($infotickerArray['erledigt']);
        return $infoticker;
    }

    function wandleSqlZuObjekten($sql, $bedingungen = array()) {
        $abfrage = $this->db->prepare($sql);
        $abfrage->execute($bedingungen);
        $infotickersArray = $abfrage->fetchAll();
        $infotickerObjekte = array();
        foreach ($infotickersArray as $infotickerArray) {
            $infotickerObjekte[] = $this->wandleArrayZuObjekt($infotickerArray);
        }
        return $infotickerObjekte;
    }

    function findeAlle() {
        $sql = "SELECT * FROM infoticker";
        return $this->wandleSqlZuObjekten($sql);
    }

    function findeAlleUnerledigten() {
        $heute = mktime(12,0,0, date('m'), date('d'), date('Y'));
        $sql = "SELECT * FROM infoticker WHERE erledigt != 1 AND datum_ts > $heute AND datum_ts < $heute+86400 ORDER BY datum_ts, eingetragen DESC";
        return $this->wandleSqlZuObjekten($sql);
    }

    function findeAlleUnerledigten2() {
        $heute = mktime(12,0,0, date('m'), date('d'), date('Y'));
        //$sql = "SELECT * FROM infoticker WHERE erledigt != 1 AND datum_ts > $heute ORDER BY datum_ts, eingetragen ASC";
        $sql = "SELECT * FROM infoticker WHERE erledigt != 1 ORDER BY datum_ts ASC";
        return $this->wandleSqlZuObjekten($sql);
    }

    function findeAlleUnerledigten3() {
        $heute = mktime(12,0,0, date('m'), date('d'), date('Y'));
        $uebermorgen = $heute + 86400*2;
        $sql = "SELECT * FROM infoticker WHERE erledigt != 1 AND datum_ts <= $uebermorgen ORDER BY datum_ts ASC";
        return $this->wandleSqlZuObjekten($sql);
    }

    function findeAnhandVonId($id) {
        $sql = "SELECT * FROM infoticker WHERE id = ?";
        $abfrage = $this->db->prepare($sql);
        $abfrage->execute(array($id));
        $infotickerArray = $abfrage->fetch();
        return $this->wandleArrayZuObjekt($infotickerArray);
    }


    function fuegeInfotickerHinzu(Infoticker $infoticker) {
        $sql = "INSERT INTO infoticker (tag, monat, jahr, datum_ts, eingetragen, text, erledigt) VALUES (?,?,?,?,?,?,?)";
        $abfrage = $this->db->prepare($sql);
        $abfrage->execute(array(
                $infoticker->getTag(),
                $infoticker->getMonat(),
                $infoticker->getJahr(),
                $infoticker->getDatumTs(),
                $infoticker->getEingetragen(),
                $infoticker->getText(),
                $infoticker->getErledigt()
            ));
        $infoticker->setId($this->db->lastInsertId());
    }

    function aendereInfoticker(Infoticker $infoticker) {
        $sql = "UPDATE infoticker SET tag=?, monat=?, jahr=?, datum_ts=?, eingetragen=?, text=?, erledigt=? WHERE id=?";
        $abfrage = $this->db->prepare($sql);
        $daten = array(
            $infoticker->getTag(),
            $infoticker->getMonat(),
            $infoticker->getJahr(),
            $infoticker->getDatumTs(),
            $infoticker->getEingetragen(),
            $infoticker->getText(),
            $infoticker->getErledigt(),
            $infoticker->getId()
        );
        $abfrage->execute($daten);
        return true;
    }

    function speichere(Infoticker $infoticker) {
        if (!$infoticker->istValide()) {
            return false;
        }
        if ($infoticker->getId()) {
            $this->aendereInfoticker($infoticker);
        } else {
            $this->fuegeInfotickerHinzu($infoticker);
        }
        return true;
    }

    function loesche(Infoticker $infoticker) {
        $sql = "DELETE FROM infoticker WHERE id=?";
        $abfrage = $this->db->prepare($sql);
        $abfrage->execute(array($infoticker->getId()));
    }

}
?>
