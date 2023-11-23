<?php

class Scan {
    private $id = 0;
    private $tag = 0;
    private $monat = 0;
    private $jahr = 0;
    private $kunden_id = 0;
    private $zeit = '';
    private $code = '';
    private $gepackt = 0;
    private $verladen = 0;


    function __construct($daten = array()) {
        if (isset($daten['id'])) {
            $this->setId($daten['id']);
        }        
        if (isset($daten['tag'])) {
            $this->setTag($daten['tag']);
        }
        if (isset($daten['monat'])) {
            $this->setMonat($daten['monat']);
        }
        if (isset($daten['jahr'])) {
            $this->setJahr($daten['jahr']);
        }
        if (isset($daten['kunden_id'])) {
            $this->setKundenId($daten['kunden_id']);
        }
        if (isset($daten['zeit'])) {
            $this->setZeit($daten['zeit']);
        }
        if (isset($daten['code'])) {
            $this->setCode($daten['code']);
        }
        if (isset($daten['gepackt'])) {
            $this->setGepackt($daten['gepackt']);
        }
        if (isset($daten['verladen'])) {
            $this->setVerladen($daten['verladen']);
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
    function setKundenId($kunden_id) {
        $this->kunden_id = $kunden_id;
    }
    function setZeit($zeit) {
        $this->zeit = $zeit;
    }
    function setCode($code) {
        $this->code = $code;
    }
    function setGepackt($gepackt) {
        $this->gepackt = $gepackt;
    }
    function setVerladen($verladen) {
        $this->verladen = $verladen;
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
    function getKundenId() {
        return $this->kunden_id;
    }
    function getZeit() {
        return $this->zeit;
    }
    function getCode() {
        return $this->code;
    }
    function getGepackt() {
        return $this->gepackt;
    }
    function getVerladen() {
        return $this->verladen;
    }
    
    function istValide() {
        return empty($this->errors);
    }
    function zeigeFehler() {
        return implode('<br />', $this->errors);
    }

}

?>
