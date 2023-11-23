<?php

class PortionenaenderungVerwaltung {
    private $db = null;

    function __construct($db) {
        $this->db = $db;
    }

    function wandleArrayZuObjekt($portionenaenderungArray) {
        $portionenaenderung = new Portionenaenderung();
        $portionenaenderung->setId($portionenaenderungArray['id']);
        $portionenaenderung->setKundeId($portionenaenderungArray['kunde_id']);
        $portionenaenderung->setPortionenMo($portionenaenderungArray['portionen_mo']);
        $portionenaenderung->setPortionenDi($portionenaenderungArray['portionen_di']);
        $portionenaenderung->setPortionenMi($portionenaenderungArray['portionen_mi']);
        $portionenaenderung->setPortionenDo($portionenaenderungArray['portionen_do']);
        $portionenaenderung->setPortionenFr($portionenaenderungArray['portionen_fr']);
        $portionenaenderung->setWochenstarttagTs($portionenaenderungArray['wochenstarttagts']);
        $portionenaenderung->setStarttag($portionenaenderungArray['starttag']);
        $portionenaenderung->setStartmonat($portionenaenderungArray['startmonat']);
        $portionenaenderung->setStartjahr($portionenaenderungArray['startjahr']);
        $portionenaenderung->setSpeiseNr($portionenaenderungArray['speise_nr']);
        return $portionenaenderung;
    }

    function wandleSqlZuObjekten($sql, $bedingungen = array()) {
        $abfrage = $this->db->prepare($sql);
        $abfrage->execute($bedingungen);
        $portionenaenderungenArray = $abfrage->fetchAll();
        $portionenaenderungenObjekte = array();
        foreach($portionenaenderungenArray as $portionenaenderungArray) {
            $portionenaenderungenObjekte[]= $this->wandleArrayZuObjekt($portionenaenderungArray);
        }
        return $portionenaenderungenObjekte;
    }

    function findeAlle() {
        $sql = "SELECT * FROM portionenaenderung";
        return $this->wandleSqlZuObjekten($sql);
    }
	
 function findeAlleZuKundenIdUndWochenstartdatum($kid, $starttag, $startmonat, $startjahr) {
         $starttag = $starttag*1;
         $startmonat = $startmonat*1;
        $sql = "SELECT * FROM portionenaenderung WHERE kunde_id=$kid AND starttag=$starttag AND startmonat=$startmonat AND startjahr=$startjahr";
      
        return $this->wandleSqlZuObjekten($sql);
    }
	
    function findeAnhandVonId($id) {
        $abfrage = $this->db->prepare($sql);
        $abfrage->execute(array($id));
        $portionenaenderungArray = $abfrage->fetch();
        return $this->wandleArrayZuObjekt($portionenaenderungArray);
    }
    
    function findeAnhandVonKundenId($kid) {
        $sql = "SELECT * FROM portionenaenderung WHERE kunde_id=$kid";
        return $this->wandleSqlZuObjekten($sql);
    }

    function findeAnhandVonKundenIdUndWochenstarttagts($kid, $wochenstarttagts) {
        $sql = "SELECT * FROM portionenaenderung WHERE kunde_id=? AND wochenstarttagts=?";
        $abfrage = $this->db->prepare($sql);
        $abfrage->execute(array($kid,$wochenstarttagts));
        $portionenaenderungArray = $abfrage->fetch();
        return $this->wandleArrayZuObjekt($portionenaenderungArray);
    }

     function findeAnhandVonKundenIdUndWochenstartdatum($kid, $starttag, $startmonat, $startjahr, $speise_nr = 1) {
         $starttag = $starttag*1;
         $startmonat = $startmonat*1;
        $sql = "SELECT * FROM portionenaenderung WHERE kunde_id=? AND starttag=? AND startmonat=? AND startjahr=? AND speise_nr=?";
        $abfrage = $this->db->prepare($sql);
        $abfrage->execute(array($kid, $starttag, $startmonat, $startjahr, $speise_nr));
        $portionenaenderungArray = $abfrage->fetch();
        return $this->wandleArrayZuObjekt($portionenaenderungArray);
    }
    
    
     function findeAnhandVonKundenIdUndWochenstartdatumSpeiseNummern($kid, $starttag, $startmonat, $startjahr, $speise_nrs = '1') {
         $starttag = $starttag*1;
         $startmonat = $startmonat*1;
        $sql = "SELECT * FROM portionenaenderung WHERE kunde_id=$kid AND starttag=$starttag AND startmonat=$startmonat AND startjahr=$startjahr AND speise_nr IN ($speise_nrs)";
      
        return $this->wandleSqlZuObjekten($sql);
    }

    function findeAnhandVonKundenIdUndAbWochenstarttagts($kid, $wochenstarttagts) {
        $sql = "SELECT * FROM portionenaenderung WHERE kunde_id=$kid AND wochenstarttagts >= $wochenstarttagts ORDER BY wochenstarttagts ASC";
        return $this->wandleSqlZuObjekten($sql);
    }

    function findeAnhandVonKundenIdUndAbWochenstarttagDatum($kid, $starttag, $startmonat, $startjahr, $speise_nr = 1) {
        $startjahr2 = $startjahr+1;
        $sql = "SELECT * FROM portionenaenderung WHERE kunde_id=$kid AND speise_nr=$speise_nr AND ((startmonat >= $startmonat AND startjahr >= $startjahr) OR (startjahr = $startjahr2)) ORDER BY startjahr, startmonat, starttag";
        return $this->wandleSqlZuObjekten($sql);
    }

    function findeAnhandVonKundenIdUndAbWochenstarttagDatum2($kid, $starttag, $startmonat, $startjahr) {
        
        $sql = "SELECT * FROM portionenaenderung WHERE kunde_id=$kid AND (wochenstarttagts >= $starttag AND startmonat >= $startmonat AND startjahr >= $startjahr) ORDER BY startjahr, startmonat, starttag";
        return $this->wandleSqlZuObjekten($sql);
    }

    function fuegePortionenaenderungHinzu(Portionenaenderung $portionenaenderung) {
        $sql = "INSERT INTO portionenaenderung (kunde_id, portionen_mo, portionen_di, portionen_mi, portionen_do, portionen_fr, wochenstarttagts, starttag, startmonat, startjahr, speise_nr) VALUES (?,?,?,?,?,?,?,?,?,?,?)";
        $abfrage = $this->db->prepare($sql);
        $abfrage->execute(array(
                $portionenaenderung->getKundeId(),
                $portionenaenderung->getPortionenMo(),
                $portionenaenderung->getPortionenDi(),
                $portionenaenderung->getPortionenMi(),
                $portionenaenderung->getPortionenDo(),
                $portionenaenderung->getPortionenFr(),
                $portionenaenderung->getWochenstarttagTs(),
                $portionenaenderung->getStarttag(),
                $portionenaenderung->getStartmonat(),
                $portionenaenderung->getStartjahr(),
                $portionenaenderung->getSpeiseNr()
            ));
        $portionenaenderung->setId($this->db->lastInsertId());
    }

    function aenderePortionenaenderung(Portionenaenderung $portionenaenderung) {
        $sql = "UPDATE portionenaenderung SET kunde_id=?, portionen_mo=?, portionen_di=?, portionen_mi=?, portionen_do=?, portionen_fr=?, wochenstarttagts=?, starttag=?, startmonat=?, startjahr=?, speise_nr=? WHERE id=?";
        $abfrage = $this->db->prepare($sql);
        $daten = array(
            $portionenaenderung->getKundeId(),
            $portionenaenderung->getPortionenMo(),
            $portionenaenderung->getPortionenDi(),
            $portionenaenderung->getPortionenMi(),
            $portionenaenderung->getPortionenDo(),
            $portionenaenderung->getPortionenFr(),
            $portionenaenderung->getWochenstarttagTs(),
            $portionenaenderung->getStarttag(),
            $portionenaenderung->getStartmonat(),
            $portionenaenderung->getStartjahr(),
            $portionenaenderung->getSpeiseNr(),
            $portionenaenderung->getId()
        );
        $abfrage->execute($daten);
        return true;
    }

    function speichere(Portionenaenderung $portionenaenderung) {
        if (!$portionenaenderung->istValide()) {
            return false;
        }
        if ($portionenaenderung->getId()) {
            $this->aenderePortionenaenderung($portionenaenderung);
        } else {
            $this->fuegePortionenaenderungHinzu($portionenaenderung);
        }
        return true;
    }

    function loesche(Portionenaenderung $portionenaenderung) {
        $sql = "DELETE FROM portionenaenderung WHERE kunde_id=?";
        $abfrage = $this->db->prepare($sql);
        $abfrage->execute(array($portionenaenderung->getKundeId()));
    }
}

?>
