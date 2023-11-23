<?php

use sbarscan\model\Scan;

class ScanVerwaltung {
    private $db = null;

    function __construct($db) {
        $this->db = $db;
    }

    function wandleArrayZuObjekt($scanArray) {
        $scan = new Scan();
        $scan->setId($scanArray['id']);
        $scan->setTag($scanArray['tag']);
        $scan->setMonat($scanArray['monat']);
        $scan->setJahr($scanArray['jahr']);
        $scan->setKundenId($scanArray['kunden_id']);
        $scan->setZeit($scanArray['zeit']);
        $scan->setCode($scanArray['code']);
        $scan->setGepackt($scanArray['gepackt']);
        $scan->setVerladen($scanArray['verladen']);
        return $scan;
    }

    function wandleSqlZuObjekten($sql, $bedingungen = array()) {
        $abfrage = $this->db->prepare($sql);
        $abfrage->execute($bedingungen);
        $scansArray = $abfrage->fetchAll();
        $scansObjekte = array();
        foreach ($scansArray as $scanArray) {
            $scansObjekte[] = $this->wandleArrayZuObjekt($scanArray);
        }
        return $scansObjekte;
    }

    function findeAlle() {
        $sql = "SELECT * FROM scans";
        return $this->wandleSqlZuObjekten($sql);
    }

    function findeAlleGepacktenZuDatum($tag, $monat, $jahr) {
        $sql = "SELECT * FROM scans WHERE tag=$tag AND monat=$monat AND jahr=$jahr AND gepackt > 0 ORDER BY gepackt DESC";
        return $this->wandleSqlZuObjekten($sql);
    }
    function findeAlleVerladenenZuDatum($tag, $monat, $jahr) {
        $sql = "SELECT * FROM scans WHERE tag=$tag AND monat=$monat AND jahr=$jahr AND verladen > 0 ORDER BY gepackt DESC";
        return $this->wandleSqlZuObjekten($sql);
    }

    function findeAlleZuDatumUndKundenId($tag,$monat,$jahr, $kunden_id, $format = '%d.%m.%Y %H:%m:%s') {
        $tag = $tag*1;
        $monat = $monat*1;
        $sql = "SELECT *, DATE_FORMAT(zeit,'$format') as zeit FROM scans WHERE tag=$tag AND monat=$monat AND jahr=$jahr  AND kunden_id=$kunden_id ORDER BY id";
        return $this->wandleSqlZuObjekten($sql);
    }

    function findeAlleGescanntAnTag($tag,$monat,$jahr, $format = '%d.%m.%Y %H:%m:%s', $kunden_id) {

        $sql = "SELECT *, DATE_FORMAT(zeit,'$format') as zeit FROM scans WHERE zeit >= '$jahr-$monat-$tag 00:00:00' AND zeit <= '$jahr-$monat-$tag 23:59:59' AND kunden_id = $kunden_id ORDER BY zeit DESC";

        return $this->wandleSqlZuObjekten($sql);
    }
    function findeAlleZuDatum($tag,$monat,$jahr, $format = '%d.%m.%Y %H:%m:%s') {
        $tag = $tag*1;
        $monat = $monat*1;
        $sql = "SELECT *, DATE_FORMAT(zeit,'$format') as zeit FROM scans WHERE tag=$tag AND monat=$monat AND jahr=$jahr";
        return $this->wandleSqlZuObjekten($sql);
    }

     function findeAlleZuKundenId($kunde_id) {
        $sql = "SELECT * FROM scans WHERE kunden_id=$kunde_id";
        return $this->wandleSqlZuObjekten($sql);
    }



    function findeAnhandVonCode($code) {
        $sql = "SELECT * FROM scans WHERE code=?";
        $abfrage = $this->db->prepare($sql);
        $abfrage->execute(array($code));
        $scanArray = $abfrage->fetch();
        return $this->wandleArrayZuObjekt($scanArray);
    }

    function findeAnhandVonId($id) {
        $sql = "SELECT * FROM scans WHERE id=?";
        $abfrage = $this->db->prepare($sql);
        $abfrage->execute(array($id));
        $scanArray = $abfrage->fetch();
        return $this->wandleArrayZuObjekt($scanArray);
    }

    function fuegeScanHinzu(Scan $scan) {
        $sql = "INSERT INTO scans (tag, monat, jahr, kunden_id, zeit, code, gepackt, verladen) VALUES (?, ?,?,?,?,?,?,?)";
        $abfrage = $this->db->prepare($sql);
        $abfrage->execute(array(
                $scan->getTag(),
                $scan->getMonat(),
                $scan->getJahr(),
                $scan->getKundenId(),
                $scan->getZeit(),
                $scan->getCode(),
                $scan->getGepackt(),
                $scan->getVerladen()
            ));
        $scan->setId($this->db->lastInsertId());
    }

    function aendereScan(Scan $scan) {
        $sql = "UPDATE scans SET tag=?, monat=?, jahr=?, kunden_id=?, zeit=?, code=?, gepackt=?, verladen=? WHERE id=?";
        $abfrage = $this->db->prepare($sql);
        $daten = array(
            $scan->getTag(),
            $scan->getMonat(),
            $scan->getJahr(),
            $scan->getKundenId(),
            $scan->getZeit(),
            $scan->getCode(),
            $scan->getGepackt(),
            $scan->getVerladen(),
            $scan->getId()
        );
        $abfrage->execute($daten);
        return true;
    }

    function speichere(Scan $scan) {
        if (!$scan->istValide()) {
            return false;
        }
        if ($scan->getId()) {
            $this->aendereScan($scan);
        } else {
            $this->fuegeScanHinzu($scan);
        }
        return true;
    }

     function loesche(Scan $scan) {
        $sql = "DELETE FROM scans WHERE id=?";
        $abfrage = $this->db->prepare($sql);
        $abfrage->execute(array($scan->getId()));
    }
}

?>
