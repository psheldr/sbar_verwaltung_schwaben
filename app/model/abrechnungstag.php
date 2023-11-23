<?php

class Abrechnungstag {
    private $id = 0;
    private $kunde_id = 0;
    private $tag = 0;
    private $portionen = 0;
    private $speisen_ids = '';
    private $tag2 = 0;
    private $monat = 0;
    private $jahr = 0;
    private $speise_nr = 1;
    private $lieferschein = NULL;
    private $errors = array();


    function __construct($daten = array()) {
        if (isset($daten['id'])) {
            $this->setId($daten['id']);
        }
        if (isset($daten['kunde_id'])) {
            $this->setKundeId($daten['kunde_id']);
        }
        if (isset($daten['tag'])) {
            $this->setTag($daten['tag']);
        }
        if (isset($daten['portionen'])) {
            $this->setPortionen($daten['portionen']);
        }
        if (isset($daten['speisen_ids'])) {
            $this->setSpeisenIds($daten['speisen_ids']);
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
        if (isset($daten['speise_nr'])) {
            $this->setSpeiseNr($daten['speise_nr']);
        }     
        if (isset($daten['lieferschein'])) {
            $this->setLieferschein($daten['lieferschein']);
        }
    }


    function setId($id) {
        $this->id = $id;
    }
    function setKundeId($kunde_id) {
        $this->kunde_id = $kunde_id;
    }
    function setTag($tag) {
        $this->tag = $tag;
    }
    function setPortionen($portionen) {
        $this->portionen = $portionen;
    }
    function setSpeisenIds($speisen_ids) {
        $this->speisen_ids = $speisen_ids;
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
    function setSpeiseNr($speise_nr) {
        $this->speise_nr = $speise_nr;
    }
    function setLieferschein($lieferschein) {
        $this->lieferschein = $lieferschein;
    }

    function getId() {
        return $this->id;
    }
    function getKundeId() {
        return $this->kunde_id;
    }
    function getTag() {
        return $this->tag;
    }
    function getPortionen() {
        return $this->portionen;
    }
    function getSpeisenIds() {
        return $this->speisen_ids;
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
    function getSpeiseNr() {
        return $this->speise_nr;
    }
    function getLieferschein() {
        return $this->lieferschein;
    }

    function istValide() {
        return empty($this->errors);
    }
    function zeigeFehler() {
        return implode('<br />', $this->errors);
    }

}

?>
