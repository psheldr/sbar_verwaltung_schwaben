<?php
class Infoticker {
    private $id =0;
    private $tag = 0;
    private $monat = 0;
    private $jahr = 0;
    private $datum_ts = 0;
    private $eingetragen = 0;
    private $text = '';
    private $erledigt = 0;
    private $faktor = 0;

    function __construct($daten = array()) {
        if(isset($daten['id'])) {
            $this->setId($daten['id']);
        }
        if(isset($daten['tag'])) {
            $this->setTag($daten['tag']);
        }
        if(isset($daten['monat'])) {
            $this->setMonat($daten['monat']);
        }
        if(isset($daten['jahr'])) {
            $this->setJahr($daten['jahr']);
        }
        if(isset($daten['datum_ts'])) {
            $this->setDatumTs($daten['datum_ts']);
        }
        if(isset($daten['eingetragen'])) {
            $this->setEingetragen($daten['eingetragen']);
        }
        if(isset($daten['text'])) {
            $this->setText($daten['text']);
        }
        if(isset($daten['erledigt'])) {
            $this->setErledigt($daten['erledigt']);
        }
    }

    function setId($id) {
        $this->id = $id;
    }
    function setTag($tag) {
        $this->tag = $tag;
    }
    function setMonat($monat) {
        $this->monat = $monat;
    }
    function setJahr($jahr) {
        $this->jahr = $jahr;
    }
    function setDatumTs($datum_ts) {
        $this->datum_ts = $datum_ts;
    }
    function setEingetragen($eingetragen) {
        $this->eingetragen = $eingetragen;
    }
    function setText($text) {
        $this->text = $text;
    }
    function setErledigt($erledigt) {
        $this->erledigt = $erledigt;
    }

    function getId() {
        return $this->id;
    }
    function getTag() {
        return $this->tag;
    }
    function getMonat() {
        return $this->monat;
    }
    function getJahr() {
        return $this->jahr;
    }
    function getDatumTs() {
        return $this->datum_ts;
    }
    function getEingetragen() {
        return $this->eingetragen;
    }
    function getText() {
        return $this->text;
    }
    function getErledigt() {
        return $this->erledigt;
    }


   function istValide() {
        return empty($this->errors);
    }
}
        
?>
