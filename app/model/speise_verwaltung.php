<?php

class SpeiseVerwaltung {

    private $db = null;

    function __construct($db) {
        $this->db = $db;
    }

    function wandleArrayZuObjekt($speiseArray) {
        $speise = new Speise();
        $speise->setId($speiseArray['id']);
        $speise->setBezeichnung($speiseArray['bezeichnung']);
        $speise->setRezept($speiseArray['rezept']);
        $speise->setKaltVerpackt($speiseArray['kalt_verpackt']);
        $speise->setBio($speiseArray['bio']);
        $speise->setCooled($speiseArray['cooled']);
        $speise->setNonprint($speiseArray['nonprint']);
        return $speise;
    }

    function wandleSqlZuObjetken($sql, $bedingungen = array()) {
        $abfrage = $this->db->prepare($sql);
        $abfrage->execute($bedingungen);
        $speisenArray = $abfrage->fetchAll();
        $speisenObjekte = array();
        foreach ($speisenArray as $speiseArray) {
            $speisenObjekte[] = $this->wandleArrayZuObjekt($speiseArray);
        }
        return $speisenObjekte;
    }

    function findeAlle($sort = '') {
        if ($sort == '') {
            $sort = 'bezeichnung';
        }
        $sql = "SELECT * FROM speise ORDER BY $sort ASC";
        return $this->wandleSqlZuObjetken($sql);
    }

    function findeAlleKaltVerpackten($sort = '') {
        if ($sort == '') {
            $sort = 'bezeichnung';
        }
        $sql = "SELECT * FROM speise WHERE kalt_verpackt = 1 ORDER BY $sort ASC";
        return $this->wandleSqlZuObjetken($sql);
    }

    function findeAnhandVonId($id) {
        $sql = "SELECT * FROM speise WHERE id=?";
        $abfrage = $this->db->prepare($sql);
        $abfrage->execute(array($id));
        $speiseArray = $abfrage->fetch();
        return $this->wandleArrayZuObjekt($speiseArray);
    }

    function fuegeSpeiseHinzu(Speise $speise) {
        $sql = "INSERT INTO speise (bezeichnung, rezept, kalt_verpackt, bio, cooled, nonprint) VALUES (?, ?, ?, ?, ?, ?)";
        $abfrage = $this->db->prepare($sql);
        $abfrage->execute(array(
            $speise->getBezeichnung(),
            $speise->getRezept(),
            $speise->getKaltVerpackt(),
            $speise->getBio(),
            $speise->getCooled(),
            $speise->getNonprint()
        ));
        $speise->setId($this->db->lastInsertId());
    }

    function aendereSpeise(Speise $speise) {
        $sql = "UPDATE speise SET bezeichnung=?, rezept=?, kalt_verpackt=?, bio=?, cooled=?, nonprint=? WHERE id=?";
        $abfrage = $this->db->prepare($sql);
        $daten = array(
            $speise->getBezeichnung(),
            $speise->getRezept(),
            $speise->getKaltVerpackt(),
            $speise->getBio(),
            $speise->getCooled(),
            $speise->getNonprint(),
            $speise->getId()
        );
        $abfrage->execute($daten);
        return true;
    }

    function speichere(Speise $speise) {
        if (!$speise->istValide()) {
            return false;
        }
        if ($speise->getId()) {
            $this->aendereSpeise($speise);
        } else {
            $this->fuegeSpeiseHinzu($speise);
        }
        return true;
    }

    function loesche(Speise $speise) {
        $sql = "DELETE FROM speise WHERE id=?";
        $abfrage = $this->db->prepare($sql);
        $abfrage->execute(array($speise->getId()));
    }

}

?>
