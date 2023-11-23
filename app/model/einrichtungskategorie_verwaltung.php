<?php

class EinrichtungskategorieVerwaltung {
    private $db = null;
    
    function __construct($db) {
        $this->db = $db;
    }

    function wandleArrayZuObjekt($einrichtungskategorieArray) {
        $einrichtungskategorie = new Einrichtungskategorie();
        $einrichtungskategorie->setId($einrichtungskategorieArray['id']);
        $einrichtungskategorie->setBezeichnung($einrichtungskategorieArray['bezeichnung']);
        return $einrichtungskategorie;
    }

    function wandleSqlZuObjekten($sql, $bedingungen = array()) {
        $abfrage = $this->db->prepare($sql);
        $abfrage->execute($bedingungen);
        $einrichtungskategorienArray = $abfrage->fetchAll();
        $einrichtungskategorienObjekte = array();
        foreach ($einrichtungskategorienArray as $einrichtungskategorieArray) {
            $einrichtungskategorienObjekte[] = $this->wandleArrayZuObjekt($einrichtungskategorieArray);
        }
        return $einrichtungskategorienObjekte;
    }
	

    function findeAlle() {
        $sql = "SELECT * FROM einrichtungskategorie";
        return $this->wandleSqlZuObjekten($sql);
    }

    function findeAlleSort() {
        $sql = "SELECT * FROM einrichtungskategorie ORDER BY id ASC";
        return $this->wandleSqlZuObjekten($sql);
    }

    function findeAnhandVonId($id) {
        $sql = "SELECT * FROM einrichtungskategorie WHERE id=?";
        $abfrage = $this->db->prepare($sql);
        $abfrage->execute(array($id));
        $einrichtungskategorieArray = $abfrage->fetch();
        return $this->wandleArrayZuObjekt($einrichtungskategorieArray);
    }


    function fuegeEinrichtungskategorieHinzu(Einrichtungskategorie $einrichtungskategorie) {
        $sql = "INSERT INTO einrichtungskategorie (bezeichnung) VALUES (?)";
        $abfrage = $this->db->prepare($sql);
        $abfrage->execute(array(
                $einrichtungskategorie->getBezeichnung()
            ));
        $einrichtungskategorie->setId($this->db->lastInsertId());
    }

    function aendereEinrichtungskategorie(Einrichtungskategorie $einrichtungskategorie) {
        $sql = "UPDATE einrichtungskategorie SET bezeichnung=? WHERE id=?";
        $abfrage = $this->db->prepare($sql);
        $daten = array(
            $einrichtungskategorie->getBezeichnung(),
            $einrichtungskategorie->getId()
        );
        $abfrage->execute($daten);
        return true;
    }

    function speichere(Einrichtungskategorie $einrichtungskategorie) {
        if (!$einrichtungskategorie->istValide()) {
            return false;
        }
        if ($einrichtungskategorie->getId()) {
            $this->aendereEinrichtungskategorie($einrichtungskategorie);
        } else {
            $this->fuegeEinrichtungskategorieHinzu($einrichtungskategorie);
        }
        return true;
    }

    function loesche(Einrichtungskategorie $einrichtungskategorie) {
        $sql = "DELETE FROM einrichtungskategorie WHERE id=?";
        $abfrage = $this->db->prepare($sql);
        $abfrage->execute(array($einrichtungskategorie->getId()));
    }

}

?>
