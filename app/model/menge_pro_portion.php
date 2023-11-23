<?php

class MengeProPortion {
    private $id = 0;
    private $speise_id = 0;
    private $einrichtungskategorie_id = 0;
    private $menge = 0;
    private $einheit = '';


    function __construct($daten = array()) {
        if(isset($daten['id'])) {
            $this->setId($daten['id']);
        }
        if(isset($daten['speise_id'])) {
            $this->setSpeiseId($daten['speise_id']);
        }
        if(isset($daten['einrichtungskategorie_id'])) {
            $this->setEinrichtungskategorieId($daten['einrichtungskategorie_id']);
        }
        if(isset($daten['menge'])) {
            //$daten['menge'] = str_replace(',','.',$daten['menge']);
            $this->setMenge($daten['menge']);
           
        }
        if(isset($daten['einheit'])) {
            $this->setEinheit($daten['einheit']);
        }
    }


    function setId($id) {
        $this->id = $id;
    }
    function setSpeiseId($speise_id) {
        $this->speise_id = $speise_id;
    }
    function setEinrichtungskategorieId($einrichtungskategorie_id) {
        $this->einrichtungskategorie_id = $einrichtungskategorie_id;
    }
    function setMenge($menge) {
       $menge = 1* (float)str_replace(',','.',$menge);
           $this->menge = number_format($menge, 3, '.','');
    }
    function setEinheit($einheit) {
        $this->einheit = $einheit;
    }


    function getId() {
        return $this->id;
    }
    function getSpeiseId() {
        return $this->speise_id;
    }
    function getEinrichtungskategorieId() {
        return $this->einrichtungskategorie_id;
    }
    function getMenge() {            
        return $this->menge;
    }
    function getEinheit() {
        return $this->einheit;
    }


    function istValide() {
        return empty($this->errors);
    }
    function zeigeFehler() {
        return implode('<br />', $this->errors);
    }
}

?>
