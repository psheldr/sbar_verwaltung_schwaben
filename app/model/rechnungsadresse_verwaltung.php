<?php
class RechnungsadresseVerwaltung {
    private $db = null;
    
    function __construct($db) {
        $this->db = $db;
    }
    
    function wandleArrayZuObjekt($rechnungsadresseArray) {
        $rechnungsadresse = New Rechnungsadresse() ;
        $rechnungsadresse->setId($rechnungsadresseArray['id']);
        $rechnungsadresse->setKundeId($rechnungsadresseArray['kunde_id']);
        $rechnungsadresse->setFirma($rechnungsadresseArray['firma']);
        $rechnungsadresse->setVorname($rechnungsadresseArray['vorname']);
        $rechnungsadresse->setNachname($rechnungsadresseArray['nachname']);
        $rechnungsadresse->setStrasse($rechnungsadresseArray['strasse']);
        $rechnungsadresse->setPlz($rechnungsadresseArray['plz']);
        $rechnungsadresse->setOrt($rechnungsadresseArray['ort']);
        $rechnungsadresse->setEmail($rechnungsadresseArray['email']);
        $rechnungsadresse->setAktiv($rechnungsadresseArray['aktiv']);
        return $rechnungsadresse;
    }
    
    function wandleSqlZuObjekten($sql, $bedingungen = array()) {
        $abfrage = $this->db->prepare($sql);
        $abfrage->execute($bedingungen);
        $rechnungsadressenArray = $abfrage->fetchAll();
        $rechnungsadresseObjekte = array();
        foreach ($rechnungsadressenArray as $rechnungsadresseArray) {
            $rechnungsadresseObjekte[] = $this->wandleArrayZuObjekt($rechnungsadresseArray);
        }
        return $rechnungsadresseObjekte;
    }

  
    
    function findeAnhandVonId($id) {
        $sql = "SELECT * FROM rechnungsadresse WHERE id=?";
        $abfrage = $this->db->prepare($sql);
        $abfrage->execute(array($id));
        $rechnungsadresseArray = $abfrage->fetch();
        return $this->wandleArrayZuObjekt($rechnungsadresseArray);
    }

    function findeAnhandVonKundeId($kunde_id) {
        $sql = "SELECT * FROM rechnungsadresse WHERE kunde_id=?";
        $abfrage = $this->db->prepare($sql);
        $abfrage->execute(array($kunde_id));
        $rechnungsadresseArray = $abfrage->fetch();
        return $this->wandleArrayZuObjekt($rechnungsadresseArray);
    }

    function fuegeRechnungsadresseHinzu(Rechnungsadresse $rechnungsadresse) {
        $sql = "INSERT INTO rechnungsadresse (kunde_id, firma, vorname, nachname, strasse, plz, ort, email, aktiv) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)";
        $abfrage = $this->db->prepare($sql);
        $abfrage->execute(array(
                $rechnungsadresse->getKundeId(),
                $rechnungsadresse->getFirma(),
                $rechnungsadresse->getVorname(),
                $rechnungsadresse->getNachname(),
                $rechnungsadresse->getStrasse(),
                $rechnungsadresse->getPlz(),
                $rechnungsadresse->getOrt(),
                $rechnungsadresse->getEmail(),
                $rechnungsadresse->getAktiv()
            ));
        $rechnungsadresse->setId($this->db->lastInsertId());
    }

    function aendereRechnungsadresse(Rechnungsadresse $rechnungsadresse) {
        $sql = "UPDATE rechnungsadresse SET kunde_id=?, firma=?, vorname=?, nachname=?, strasse=?, plz=?, ort=?, email=?, aktiv=? WHERE id=?";
        $abfrage = $this->db->prepare($sql);
        $daten = array(
          $rechnungsadresse->getKundeId(),
          $rechnungsadresse->getFirma(),
          $rechnungsadresse->getVorname(),
          $rechnungsadresse->getNachname(),
          $rechnungsadresse->getStrasse(),
          $rechnungsadresse->getPlz(),
          $rechnungsadresse->getOrt(),
          $rechnungsadresse->getEmail(),
          $rechnungsadresse->getAktiv(),
          $rechnungsadresse->getId()
        );
        $abfrage->execute($daten);
        return true;
    }

    function speichere(Rechnungsadresse $rechnungsadresse) {
        if (!$rechnungsadresse->istValide()) {
            return false;
        }
        if ($rechnungsadresse->getId()) {
            $this->aendereRechnungsadresse($rechnungsadresse);
        } else {
            $this->fuegeRechnungsadresseHinzu($rechnungsadresse);
        }
        return true;
    }

      function loesche(Rechnungsadresse $rechnungsadresse) {
        $sql = "DELETE FROM rechnungsadresse WHERE id=?";
        $abfrage = $this->db->prepare($sql);
        $abfrage->execute(array($rechnungsadresse->getId()));
    }
}
?>
