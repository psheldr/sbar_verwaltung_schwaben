<?php
class FahrerCodeVerwaltung {
    private $db = null;

    function __construct($db) {
        $this->db = $db;
    }

    function wandleArrayZuObjekt($fahrercodeArray) {
        $fahrercode = new FahrerCode();
        $fahrercode->setId($fahrercodeArray['id']);
        $fahrercode->setCode($fahrercodeArray['code']);
        $fahrercode->setName($fahrercodeArray['name']);
        return $fahrercode;
    }

    function wandleSqlZuObjekten($sql, $bedingungen = array()) {
        $abfrage = $this->db->prepare($sql);
        $abfrage->execute($bedingungen);
        $fahrercodeenArray = $abfrage->fetchAll();
        $fahrercodeenObjekte = array();
        foreach ($fahrercodeenArray as $fahrercodeArray) {
            $fahrercodeenObjekte[] = $this->wandleArrayZuObjekt($fahrercodeArray);
        }
        return $fahrercodeenObjekte;
    }

    function findeAlle() {
        $sql = "SELECT * FROM fahrer";
        return $this->wandleSqlZuObjekten($sql);
    }
    
 
     function findeAlleZuCode($code) {
        $sql = "SELECT * FROM fahrer WHERE code=$code";
        return $this->wandleSqlZuObjekten($sql);
    }

    function findeAnhandVonCode($code) {
        $sql = "SELECT * FROM fahrer WHERE code=?";
        $abfrage = $this->db->prepare($sql);
        $abfrage->execute(array($code));
        $fahrercodeArray = $abfrage->fetch();
        return $this->wandleArrayZuObjekt($fahrercodeArray);
    }
    function findeAnhandVonId($id) {
        $sql = "SELECT * FROM fahrer WHERE id = ?";
        $abfrage = $this->db->prepare($sql);
        $abfrage->execute(array($id));
        $fahrercodeArray = $abfrage->fetch();
        return $this->wandleArrayZuObjekt($fahrercodeArray);
    }


    function fuegeFahrerCodeHinzu(FahrerCode $fahrercode) {
        $sql = "INSERT INTO fahrer (code, name) VALUES (?,?)";
        $abfrage = $this->db->prepare($sql);
        $abfrage->execute(array(
                $fahrercode->getCode(),
                $fahrercode->getName()
            ));
        $fahrercode->setId($this->db->lastInsertId());
    }

    function aendereFahrerCode(FahrerCode $fahrercode) {
        $sql = "UPDATE fahrer SET code=?, name=? WHERE id=?";
        $abfrage = $this->db->prepare($sql);
        $daten = array(
            $fahrercode->getCode(),
            $fahrercode->getName(),
            $fahrercode->getId()
        );
        $abfrage->execute($daten);
        return true;
    }

    function speichere(FahrerCode $fahrercode) {
        if (!$fahrercode->istValide()) {
            return false;
        }
        if ($fahrercode->getId()) {
            $this->aendereFahrerCode($fahrercode);
        } else {
            $this->fuegeFahrerCodeHinzu($fahrercode);
        }
        return true;
    }

    function loesche(FahrerCode $fahrercode) {
        $sql = "DELETE FROM fahrer WHERE id=?";
        $abfrage = $this->db->prepare($sql);
        $abfrage->execute(array($fahrercode->getId()));
    }

}
?>
