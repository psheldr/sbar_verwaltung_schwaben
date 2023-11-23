<?php
class Standardportionen {
    private $id = 0;
    private $kunde_id = 0;
    private $portionen_mo = 0;
    private $portionen_di = 0;
    private $portionen_mi = 0;
    private $portionen_do = 0;
    private $portionen_fr = 0;
    private $speise_nr = 1;
    private $errors = array();

    function __construct($daten = array()) {
        if (isset($daten['id'])) {
            $this->setId($daten['id']);
        }
        if(isset($daten['kunde_id'])) {
            $this->setKundeId((int)$daten['kunde_id']);
        }
        if(isset($daten['portionen_mo'])) {
            $this->setPortionenMo((int)$daten['portionen_mo']);
        }
        if(isset($daten['portionen_di'])) {
            $this->setPortionenDi((int)$daten['portionen_di']);
        }
        if(isset($daten['portionen_mi'])) {
            $this->setPortionenMi((int)$daten['portionen_mi']);
        }
        if(isset($daten['portionen_do'])) {
            $this->setPortionenDo((int)$daten['portionen_do']);
        }
        if(isset($daten['portionen_fr'])) {
            $this->setPortionenFr((int)$daten['portionen_fr']);
        }
        if (isset($daten['speise_nr'])) {
            $this->setSpeiseNr((int)$daten['speise_nr']);
        }
    }


    function setId($id) {
        $this->id = $id;
    }
    function setKundeId($kunde_id) {
        $this->kunde_id = $kunde_id;
    }
    function setPortionenMo($portionen_mo) {
        $this->portionen_mo = $portionen_mo;
    }
    function setPortionenDi($portionen_di) {
        $this->portionen_di = $portionen_di;
    }
    function setPortionenMi($portionen_mi) {
        $this->portionen_mi = $portionen_mi;
    }
    function setPortionenDo($portionen_do) {
        $this->portionen_do = $portionen_do;
    }
    function setPortionenFr($portionen_fr) {
        $this->portionen_fr = $portionen_fr;
    }
    function setSpeiseNr($speise_nr) {
        $this->speise_nr = $speise_nr;
    }


    function getId() {
        return $this->id;
    }
    function getKundeId() {
        return $this->kunde_id;
    }
    function getPortionenMo() {
        return $this->portionen_mo;
    }
    function getPortionenDi() {
        return $this->portionen_di;
    }
    function getPortionenMi() {
        return $this->portionen_mi;
    }
    function getPortionenDo() {
        return $this->portionen_do;
    }
    function getPortionenFr() {
        return $this->portionen_fr;
    }
    function getSpeiseNr() {
        return $this->speise_nr;
    }


    function istValide() {
        return empty($this->errors);
    }

    function zeigeFehler() {
        return implode('<br />', $this->errors);
    }
}
?>
