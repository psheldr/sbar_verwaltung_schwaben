<?php
class MengeProPortionVerwaltung {
    private $db = null;

    function __construct($db) {
        $this->db = $db;
    }

    function wandleArrayZuObjekt($mengeproportionArray) {
        $mengeproportion = new MengeProPortion();
        $mengeproportion->setId($mengeproportionArray['id']);
        $mengeproportion->setSpeiseId($mengeproportionArray['speise_id']);
        $mengeproportion->setEinrichtungskategorieId($mengeproportionArray['einrichtungskategorie_id']);
        $mengeproportion->setMenge($mengeproportionArray['menge']);
        $mengeproportion->setEinheit($mengeproportionArray['einheit']);
        return $mengeproportion;
    }

    function wandleSqlZuObjekten($sql, $bedingungen = array()) {
        $abfrage = $this->db->prepare($sql);
        $abfrage->execute($bedingungen);
        $mengenproportionenArray = $abfrage->fetchAll();
        $mengeproportionObjekte = array();
        foreach ($mengenproportionenArray as $mengeproportionArray) {
            $mengeproportionObjekte[] = $this->wandleArrayZuObjekt($mengeproportionArray);
        }
        return $mengeproportionObjekte;
    }

    function findeAlle() {
        $sql = "SELECT * FROM menge_pro_portion";
        return $this->wandleSqlZuObjekten($sql);
    }

    function findeZuSpeiseIdMitHoechsterMenge($speisen_ids_array, $limit = 3) {
        $sql = "SELECT * FROM menge_pro_portion WHERE speise_id IN (".implode(',',$speisen_ids_array).") ORDER BY menge DESC LIMIT ".$limit;
        
        return $this->wandleSqlZuObjekten($sql, array($speise_id, $menge));
    }
    function findeAnhandVonSpeiseIdUndMenge($speise_id, $menge) {
        $sql = "SELECT * FROM menge_pro_portion WHERE speise_id=? AND menge = ?";
        return $this->wandleSqlZuObjekten($sql, array($speise_id, $menge));
    }
    function findeAnhandVonId($id) {
        $sql = "SELECT * FROM menge_pro_portion WHERE id = ?";
        $abfrage = $this->db->prepare($sql);
        $abfrage->execute(array($id));
        $mengeproportionArray = $abfrage->fetch();
        return $this->wandleArrayZuObjekt($mengeproportionArray);
    }

    function findeAnhandVonSpeiseIdUndEinrichtungskategorieId($id, $einrichtungskategorie_id) {
 $sql = "SELECT * FROM menge_pro_portion WHERE speise_id = ? AND einrichtungskategorie_id=?";
        $abfrage = $this->db->prepare($sql);
        $abfrage->execute(array($id, $einrichtungskategorie_id));
        $mengeproportionArray = $abfrage->fetch();
        return $this->wandleArrayZuObjekt($mengeproportionArray);
    }


 function findeAnhandVonEinrichtungskategorieId($einrichtungskategorie_id) {
 $sql = "SELECT * FROM menge_pro_portion WHERE einrichtungskategorie_id=?";
        return $this->wandleSqlZuObjekten($sql, array($einrichtungskategorie_id));
    }

    function fuegeMengeProPortionHinzu(MengeProPortion $menge_pro_portion) {
        $sql = "INSERT INTO menge_pro_portion (speise_id, einrichtungskategorie_id, menge, einheit) VALUES (?,?,?,?)";
        $abfrage = $this->db->prepare($sql);
        $abfrage->execute(array(
                $menge_pro_portion->getSpeiseId(),
                $menge_pro_portion->getEinrichtungskategorieId(),
                $menge_pro_portion->getMenge(),
                $menge_pro_portion->getEinheit()
            ));
        $menge_pro_portion->setId($this->db->lastInsertId());
    }

    function aendereMengeProPortion(MengeProPortion $menge_pro_portion) {
        $sql = "UPDATE menge_pro_portion SET speise_id=?, einrichtungskategorie_id=?,menge=?,einheit=? WHERE id=?";
        $abfrage = $this->db->prepare($sql);
        $daten = array(
            $menge_pro_portion->getSpeiseId(),
            $menge_pro_portion->getEinrichtungskategorieId(),
            $menge_pro_portion->getMenge(),
            $menge_pro_portion->getEinheit(),
            $menge_pro_portion->getId()
        );
        $abfrage->execute($daten);
        return true;
    }

    function speichere(MengeProPortion $menge_pro_portion) {
        if (!$menge_pro_portion->istValide()) {
            return false;
        }
        if ($menge_pro_portion->getId()) {
            $this->aendereMengeProPortion($menge_pro_portion);
        } else {
            $this->fuegeMengeProPortionHinzu($menge_pro_portion);
        }
        return true;
    }

    function findeAnhandVonSpeiseId($speise_id) {
        $sql = "SELECT * FROM menge_pro_portion WHERE speise_id=$speise_id";
        return $this->wandleSqlZuObjekten($sql);
    }
    function findeAnhandVonSpeiseIdSort($speise_id) {
        $sql = "SELECT * FROM menge_pro_portion WHERE speise_id=$speise_id ORDER BY einrichtungskategorie_id ASC";
        return $this->wandleSqlZuObjekten($sql);
    }

    function loesche(MengeProPortion $menge_pro_portion) {
        $sql = "DELETE FROM menge_pro_portion WHERE id=?";
        $abfrage = $this->db->prepare($sql);
        $abfrage->execute(array($menge_pro_portion->getId()));
    }

}
?>
