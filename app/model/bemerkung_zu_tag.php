<?php

class BemerkungZuTag {
    private $id = 0;
    private $speise_id = 0;
    private $kunde_id = 0;
    private $bemerkung = '';
    private $tag = 0;
    private $tag2 = 0;
    private $monat = 0;
    private $jahr = 0;
    private $errors = array();


    function __construct($daten = array()) {
        if (isset($daten['id'])) {
            $this->setId($daten['id']);
        }
        if (isset($daten['speise_id'])) {
            $this->setSpeiseId($daten['speise_id']);
        }
        if (isset($daten['kunde_id'])) {
            $this->setKundeId($daten['kunde_id']);
        }
        if (isset($daten['bemerkung'])) {
            $this->setBemerkung($daten['bemerkung']);
        }
        if (isset($daten['tag'])) {
            $this->setTag($daten['tag']);
        }
        if (isset($daten['tag2'])) {
            $this->setTag2($daten['tag2']);
        }
        if (isset($daten['monat'])) {
            $this->setMonat($daten['monat']);
        }
        if (isset($daten['jahr'])) {
            $this->setJahr($daten['jahr']);
        }
    }


    function setId($id) {
        $this->id = $id;
    }
    function setSpeiseId($speise_id) {
        $this->speise_id = $speise_id;
    }
    function setKundeId($kunde_id) {
        $this->kunde_id = $kunde_id;
    }
    function setBemerkung($bemerkung) {
        $this->bemerkung = $bemerkung;
    }
    function setTag($tag) {
        $this->tag = $tag;
    }
    function setTag2($tag2) {
        $this->tag2 = $tag2;
    }
    function setMonat($monat) {
        $this->monat = $monat;
    }
    function setJahr($jahr) {
        $this->jahr = $jahr;
    }

    function getId() {
        return $this->id;
    }
    function getSpeiseId() {
        return $this->speise_id;
    }
    function getKundeId() {
        return $this->kunde_id;
    }
    function getBemerkung() {
        return $this->bemerkung;
    }
    function getTag() {
        return $this->tag;
    }
    function getTag2() {
        return $this->tag2;
    }
    function getMonat() {
        return $this->monat;
    }
    function getJahr() {
        return $this->jahr;
    }

    function istValide() {
        return empty($this->errors);
    }
    function zeigeFehler() {
        return implode('<br />', $this->errors);
    }

}

?>
