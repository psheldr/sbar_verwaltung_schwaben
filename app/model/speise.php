<?php

class Speise {

    private $id = 0;
    private $bezeichnung = '';
    private $rezept = '';
    private $kalt_verpackt = 0;
    private $bio = 0;
    private $cooled = 0;
    private $nonprint = 0;
    private $errors = array();

    function __construct($daten = array()) {
        if (isset($daten['id'])) {
            $this->setId($daten['id']);
        }
        if (isset($daten['bezeichnung'])) {
            $this->setBezeichnung($daten['bezeichnung']);
        }
        if (isset($daten['rezept'])) {
            $this->setRezept($daten['rezept']);
        }
        if (isset($daten['kalt_verpackt'])) {
            $this->setKaltVerpackt($daten['kalt_verpackt']);
        }
        if (isset($daten['bio'])) {
            $this->setBio($daten['bio']);
        }
        if (isset($daten['cooled'])) {
            $this->setCooled($daten['cooled']);
        }
        if (isset($daten['nonprint'])) {
            $this->setNonprint($daten['nonprint']);
        }
    }

    function setId($id) {
        $this->id = $id;
    }
    function setBezeichnung($bezeichnung) {
        $this->bezeichnung = $bezeichnung;
    }
    function setRezept($rezept) {
        $this->rezept = $rezept;
    }
    function setKaltVerpackt($kalt_verpackt) {
        $this->kalt_verpackt = $kalt_verpackt;
    }
    function setBio($bio) {
        $this->bio = $bio;
    }
    function setCooled($cooled) {
        $this->cooled = $cooled;
    }
    function setNonprint($nonprint) {
        $this->nonprint = $nonprint;
    }

    function getId() {
        return $this->id;
    }
    function getBezeichnung() {
        return $this->bezeichnung;
    }
    function getRezept() {
        return $this->rezept;
    }
    function getKaltVerpackt() {
        return $this->kalt_verpackt;
    }
    function getBio() {
        return $this->bio;
    }
    function getCooled() {
        return $this->cooled;
    }
    function getNonprint() {
        return $this->nonprint;
    }
    
    function istValide() {
        return empty($this->errors);
    }
    function zeigeFehler() {
        return implode('<br />', $this->errors);
    }
}

?>
