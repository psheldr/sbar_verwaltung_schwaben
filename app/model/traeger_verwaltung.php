<?php

class TraegerVerwaltung {
    private $db = null;

    function __construct($db) {
        $this->db = $db;
    }

    function wandleArrayZuObjekt($traegerArray) {
        $traeger = new Traeger();
        $traeger->setId($traegerArray['id']);
        $traeger->setBezeichnung($traegerArray['bezeichnung']);
        $traeger->setAsp($traegerArray['asp']);
        $traeger->setStrasse($traegerArray['strasse']);
        $traeger->setPlz($traegerArray['plz']);
        $traeger->setOrt($traegerArray['ort']);
        $traeger->setTel($traegerArray['tel']);
        $traeger->setTel2($traegerArray['tel2']);
        $traeger->setFax($traegerArray['fax']);
        $traeger->setEmail($traegerArray['email']);
        $traeger->setEmail2($traegerArray['email2']);
        $traeger->setEmail3($traegerArray['email3']);
        $traeger->setEmail4($traegerArray['email4']);
        $traeger->setEmail5($traegerArray['email5']);
        $traeger->setAktiv($traegerArray['aktiv']);
        $traeger->setNotiz($traegerArray['notiz']);
        $traeger->setMatchcode($traegerArray['matchcode']);
        $traeger->setReferenz($traegerArray['referenz']);
        return $traeger;
    }

    function wandleSqlZuObjekten($sql, $bedingungen = array()) {
        $abfrage = $this->db->prepare($sql);
        $abfrage->execute($bedingungen);
        $traegerArray = $abfrage->fetchAll();
        $traegerObjekte = array();
        foreach ($traegerArray as $traegerArray) {
            $traegerObjekte[] = $this->wandleArrayZuObjekt($traegerArray);
        }
        return $traegerObjekte;
    }

    function findeAlle($sort = 'bezeichnung', $sort_dir = 'ASC') {
        $sql = "SELECT * FROM traeger ORDER BY $sort $sort_dir";
        return $this->wandleSqlZuObjekten($sql);
    }
    
    function findeAlleProjektIds() {
        $sql = "SELECT projekt_id FROM traeger ORDER BY projekt_id ASC";
        return $this->wandleSqlZuObjekten($sql);
    }
    

    function findeAlleZuCaterer($cat_id) {
        $sql = "SELECT * FROM traeger WHERE caterer_id = $cat_id ORDER BY bezeichnung ASC";
        return $this->wandleSqlZuObjekten($sql);
    }
    
    
    function findeAlleCsv() {
        $sql = "SELECT * FROM traeger WHERE csv_bildungskarte = 1 ORDER BY bezeichnung ASC";
        return $this->wandleSqlZuObjekten($sql);
    }
    
    /*function findeAlleMitSortierung($order_by_col) {
        if($order_by_col == NULL){
            $order_by_col = 'projekt_id';
        }
        $sql = "SELECT * FROM traeger ORDER BY $order_by_col ASC";
        return $this->wandleSqlZuObjekten($sql);
    }*/
    
    function findeAlleMitSortierung($order_by_col, $sort_dir) {
        if($order_by_col == NULL){
            $order_by_col = 'projekt_id';
        }
        
        $sql = "SELECT traeger.*, caterer.cat_name FROM traeger LEFT JOIN caterer ON traeger.caterer_id = caterer.id ORDER BY $order_by_col $sort_dir";
        return $this->wandleSqlZuObjekten($sql);
    }
    

    function findeAnhandVonId($id, $cols = '*') {
        $sql = "SELECT $cols FROM traeger WHERE id=?";
        $abfrage = $this->db->prepare($sql);
        $abfrage->execute(array($id));
        $traegerArray = $abfrage->fetch();
        return $this->wandleArrayZuObjekt($traegerArray);
    }

       function findeAnhandVonProjektId($projekt_id, $cols = '*') {
        $sql = "SELECT $cols FROM traeger WHERE BINARY projekt_id=?";
        $abfrage = $this->db->prepare($sql);
        $abfrage->execute(array($projekt_id));
        $traegerArray = $abfrage->fetch();
        return $this->wandleArrayZuObjekt($traegerArray);
    }


    function fuegeTraegerHinzu(Traeger $traeger) {
        $sql = "INSERT INTO traeger (bezeichnung, asp, strasse, plz, ort, tel, tel2, fax, email, email2, email3,email4,email5, aktiv, notiz, matchcode, referenz) VALUES (?,?,?,?,?,?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
        $abfrage = $this->db->prepare($sql);
        $abfrage->execute(array(
                $traeger->getBezeichnung(),
                $traeger->getAsp(),
                $traeger->getStrasse(),
                $traeger->getPlz(),
                $traeger->getOrt(),
                $traeger->getTel(),
                $traeger->getTel2(),
                $traeger->getFax(),
                $traeger->getEmail(),
                $traeger->getEmail2(),
                $traeger->getEmail3(),
                $traeger->getEmail4(),
                $traeger->getEmail5(),
                $traeger->getAktiv(),
            $traeger->getNotiz(),
            $traeger->getMatchcode(),
            $traeger->getReferenz()
            ));
        $traeger->setId($this->db->lastInsertId());
    }

    function aendereTraeger(Traeger $traeger) {
        $sql = "UPDATE traeger SET bezeichnung=?, asp=?, strasse=?, plz=?, ort=?, tel=?, tel2=?, fax=?, email=?, email2=?, email3=?, email4=?,email5=?,aktiv=?, notiz=?, matchcode=?, referenz=? WHERE id=?";
        $abfrage = $this->db->prepare($sql);
        $daten = array(
            $traeger->getBezeichnung(),
            $traeger->getAsp(),
            $traeger->getStrasse(),
            $traeger->getPlz(),
            $traeger->getOrt(),
            $traeger->getTel(),
            $traeger->getTel2(),
            $traeger->getFax(),
            $traeger->getEmail(),
            $traeger->getEmail2(),
            $traeger->getEmail3(),
            $traeger->getEmail4(),
            $traeger->getEmail5(),
            $traeger->getAktiv(),
            $traeger->getNotiz(),
            $traeger->getMatchcode(),
            $traeger->getReferenz(),
            $traeger->getId()
        );
        $abfrage->execute($daten);
        return true;
    }

    function loesche(Traeger $traeger) {
        $sql = "DELETE FROM traeger WHERE id=?";;
        $abfrage = $this->db->prepare($sql);
        $abfrage->execute(array($traeger->getId()));
    }
    function speichere(Traeger $traeger) {
        if (!$traeger->istValide()) {
            return false;
        }
        if ($traeger->getId()) {
            $this->aendereTraeger($traeger);
        } else {
            $this->fuegeTraegerHinzu($traeger);
        }
        return true;
    }
}

?>
