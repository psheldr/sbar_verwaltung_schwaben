<?php

class MenunamenVerwaltung {
    private $db = null;

    function __construct($db) {
        $this->db = $db;
    }

    function wandleArrayZuObjekt($menunamenArray) {
        $menunamen = new Menunamen();
        $menunamen->setId($menunamenArray['id']);
        $menunamen->setTag($menunamenArray['tag']);
        $menunamen->setMonat($menunamenArray['monat']);
        $menunamen->setJahr($menunamenArray['jahr']);
        $menunamen->setBezeichnung($menunamenArray['bezeichnung']);
        $menunamen->setBezeichnungIntern($menunamenArray['bezeichnung_intern']);
        $menunamen->setSpeiseNr($menunamenArray['speise_nr']);
        $menunamen->setFahrerExtra($menunamenArray['fahrer_extra']);
        return $menunamen;
    }

    function wandleSqlZuObjekten($sql, $bedingungen = array()) {
        $abfrage = $this->db->prepare($sql);
        $abfrage->execute($bedingungen);
        $menunamenenArray = $abfrage->fetchAll();
        $menunamenenObjekte = array();
        foreach ($menunamenenArray as $menunamenArray) {
            $menunamenenObjekte[] = $this->wandleArrayZuObjekt($menunamenArray);
        }
        return $menunamenenObjekte;
    }

    function findeAlle() {
        $sql = "SELECT * FROM menunamen";
        return $this->wandleSqlZuObjekten($sql);
    }


    function findeAlleZuDatum($tag,$monat,$jahr) {
        $tag = $tag*1;
        $monat = $monat*1;
        $sql = "SELECT * FROM menunamen WHERE tag=$tag AND monat=$monat AND jahr=$jahr";
        return $this->wandleSqlZuObjekten($sql);
    }

     function findeAlleZuBezeichnung($bezeichnung) {
        $sql = "SELECT * FROM menunamen WHERE bezeichnung=$bezeichnung";
        return $this->wandleSqlZuObjekten($sql);
    }


     function findeAnhandVonTagMonatJahrSpeiseNr($tag, $monat, $jahr, $speise_nr) {
        $sql = "SELECT * FROM menunamen WHERE tag=? AND monat=? AND jahr=? AND speise_nr=? ";
        $abfrage = $this->db->prepare($sql);
        $abfrage->execute(array($tag, $monat, $jahr, $speise_nr));
        $menunamenArray = $abfrage->fetch();
        return $this->wandleArrayZuObjekt($menunamenArray);
    }

    function findeAnhandVonId($id) {
        $sql = "SELECT * FROM menunamen WHERE id=?";
        $abfrage = $this->db->prepare($sql);
        $abfrage->execute(array($id));
        $menunamenArray = $abfrage->fetch();
        return $this->wandleArrayZuObjekt($menunamenArray);
    }

    function fuegeMenunamenHinzu(Menunamen $menunamen) {
        $sql = "INSERT INTO menunamen (tag, monat, jahr, bezeichnung, bezeichnung_intern, speise_nr, fahrer_extra) VALUES (?,?,?,?,?,?,?)";
        $abfrage = $this->db->prepare($sql);
        $abfrage->execute(array(
                $menunamen->getTag(),
                $menunamen->getMonat(),
                $menunamen->getJahr(),
                $menunamen->getBezeichnung(),
                $menunamen->getBezeichnungIntern(),
                $menunamen->getSpeiseNr(),
                $menunamen->getFahrerExtra()
            ));
        $menunamen->setId($this->db->lastInsertId());
    }

    function aendereMenunamen(Menunamen $menunamen) {
        $sql = "UPDATE menunamen SET tag=?, monat=?, jahr=?, bezeichnung=?, bezeichnung_intern=?, speise_nr=?, fahrer_extra=? WHERE id=?";
        $abfrage = $this->db->prepare($sql);
        $daten = array(
            $menunamen->getTag(),
            $menunamen->getMonat(),
            $menunamen->getJahr(),
            $menunamen->getBezeichnung(),
            $menunamen->getBezeichnungIntern(),
            $menunamen->getSpeiseNr(),
            $menunamen->getFahrerExtra(),
            $menunamen->getId()
        );
        $abfrage->execute($daten);
        return true;
    }

    function speichere(Menunamen $menunamen) {
        if (!$menunamen->istValide()) {
            return false;
        }
        if ($menunamen->getId()) {
            $this->aendereMenunamen($menunamen);
        } else {
            $this->fuegeMenunamenHinzu($menunamen);
        }
        return true;
    }

     function loesche(Menunamen $menunamen) {
        $sql = "DELETE FROM menunamen WHERE id=?";
        $abfrage = $this->db->prepare($sql);
        $abfrage->execute(array($menunamen->getId()));
    }
}

?>
