<?php

class Menunamen {
    private $id = 0;
    private $tag = 0;
    private $monat = 0;
    private $jahr = 0;
    private $bezeichnung = '';
    private $bezeichnung_intern = '';
    private $speise_nr = 0;
    private $fahrer_extra = '';
    private $errors = array();


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
        if (isset($daten['bezeichnung'])) {
            $this->setBezeichnung($daten['bezeichnung']);
        }
        if (isset($daten['bezeichnung_intern'])) {
            $this->setBezeichnungIntern($daten['bezeichnung_intern']);
        }
        if (isset($daten['speise_nr'])) {
            $this->setSpeiseNr($daten['speise_nr']);
        }
        if (isset($daten['fahrer_extra'])) {
            $this->setFahrerExtra($daten['fahrer_extra']);
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
    function setBezeichnung($bezeichnung) {
        $this->bezeichnung = $bezeichnung;
    }
    function setBezeichnungIntern($bezeichnung_intern) {
        $this->bezeichnung_intern = $bezeichnung_intern;
    }
    function setSpeiseNr($speise_nr) {
        $this->speise_nr = $speise_nr;
    }
    function setFahrerExtra($fahrer_extra) {
        $this->fahrer_extra = $fahrer_extra;
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
    function getBezeichnung() {
        return $this->bezeichnung;
    }
    function getBezeichnungIntern() {
        return $this->bezeichnung_intern;
    }
    function getSpeiseNr() {
        return $this->speise_nr;
    }
    function getFahrerExtra() {
        return $this->fahrer_extra;
    }

    function istValide() {
        return empty($this->errors);
    }
    function zeigeFehler() {
        return implode('<br />', $this->errors);
    }

}

?>
