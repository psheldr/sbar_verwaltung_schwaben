<?php
class IndifaktorVerwaltung {
    private $db = null;

    function __construct($db) {
        $this->db = $db;
    }

    function wandleArrayZuObjekt($indifaktorArray) {
        $indifaktor = new Indifaktor();
        $indifaktor->setId($indifaktorArray['id']);
        $indifaktor->setSpeiseId($indifaktorArray['speise_id']);
        $indifaktor->setKundeId($indifaktorArray['kunde_id']);
        $indifaktor->setFaktor($indifaktorArray['faktor']);
        return $indifaktor;
    }

    function wandleSqlZuObjekten($sql, $bedingungen = array()) {
        $abfrage = $this->db->prepare($sql);
        $abfrage->execute($bedingungen);
        $indifaktorenArray = $abfrage->fetchAll();
        $indifaktorenObjekte = array();
        foreach ($indifaktorenArray as $indifaktorArray) {
            $indifaktorenObjekte[] = $this->wandleArrayZuObjekt($indifaktorArray);
        }
        return $indifaktorenObjekte;
    }

    function findeAlle() {
        $sql = "SELECT * FROM indi_faktor";
        return $this->wandleSqlZuObjekten($sql);
    }
    
    function findeAlleZuKundenId($kunde_id) {
        $sql = "SELECT * FROM indi_faktor WHERE kunde_id=$kunde_id";
        return $this->wandleSqlZuObjekten($sql);
    }

     function findeAlleZuSpeiseId($speise_id) {
        $sql = "SELECT * FROM indi_faktor WHERE speise_id=$speise_id";
        return $this->wandleSqlZuObjekten($sql);
    }

    function findeAnhandVonSpeiseIdUndKundeId($speise_id, $kunde_id) {
        $sql = "SELECT * FROM indi_faktor WHERE speise_id=? AND kunde_id = ?";
        $abfrage = $this->db->prepare($sql);
        $abfrage->execute(array($speise_id, $kunde_id));
        $indifaktorArray = $abfrage->fetch();
        return $this->wandleArrayZuObjekt($indifaktorArray);
    }
    function findeAnhandVonId($id) {
        $sql = "SELECT * FROM indi_faktor WHERE id = ?";
        $abfrage = $this->db->prepare($sql);
        $abfrage->execute(array($id));
        $indifaktorArray = $abfrage->fetch();
        return $this->wandleArrayZuObjekt($indifaktorArray);
    }


    function fuegeIndifaktorHinzu(Indifaktor $indi_faktor) {
        $sql = "INSERT INTO indi_faktor (speise_id, kunde_id, faktor) VALUES (?,?,?)";
        $abfrage = $this->db->prepare($sql);
        $abfrage->execute(array(
                $indi_faktor->getSpeiseId(),
                $indi_faktor->getKundeId(),
                $indi_faktor->getFaktor()
            ));
        $indi_faktor->setId($this->db->lastInsertId());
    }

    function aendereIndifaktor(Indifaktor $indi_faktor) {
        $sql = "UPDATE indi_faktor SET speise_id=?, kunde_id=?,faktor=?WHERE id=?";
        $abfrage = $this->db->prepare($sql);
        $daten = array(
            $indi_faktor->getSpeiseId(),
            $indi_faktor->getKundeId(),
            $indi_faktor->getFaktor(),
            $indi_faktor->getId()
        );
        $abfrage->execute($daten);
        return true;
    }

    function speichere(Indifaktor $indi_faktor) {
        if (!$indi_faktor->istValide()) {
            return false;
        }
        if ($indi_faktor->getId()) {
            $this->aendereIndifaktor($indi_faktor);
        } else {
            $this->fuegeIndifaktorHinzu($indi_faktor);
        }
        return true;
    }

    function loesche(Indifaktor $indi_faktor) {
        $sql = "DELETE FROM indi_faktor WHERE id=?";
        $abfrage = $this->db->prepare($sql);
        $abfrage->execute(array($indi_faktor->getId()));
    }

}
?>
