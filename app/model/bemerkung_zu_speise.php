<?php

class BemerkungZuSpeise {
    private $id = 0;
    private $speise_id = 0;
    private $kunde_id = 0;
    private $bemerkung = '';
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
        if (empty($bemerkung)) {
            $this->errors[] = 'Es wurde keine Bemerkung eingegeben!';
        }
            $this->bemerkung = $bemerkung;

        
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

    function istValide() {
        return empty($this->errors);
    }
    function zeigeFehler() {
        return implode('<br />', $this->errors);
    }

}

?>
