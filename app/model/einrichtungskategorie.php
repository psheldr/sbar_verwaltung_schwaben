<?php

class Einrichtungskategorie {
    private $id = 0;
    private $bezeichnung = '';
    private $errors = array();

    function __construct($daten = array()) {
        if (isset($daten['id'])) {
            $this->setId($daten['id']);
        }
        if (isset($daten['bezeichnung'])) {
            $this->setBezeichnung($daten['bezeichnung']);
        }
    }

    function setId($id) {
        $this->id = $id;
    }
    function setBezeichnung($bezeichnung) {
        $this->bezeichnung = $bezeichnung;
    }

    function getId() {
        return $this->id;
    }
    function getBezeichnung() {
        return $this->bezeichnung;
    }


    function istValide() {
        return empty($this->errors);
    }
    function zeigeFehler() {
        return implode('<br />', $this->errors);
    }
}

?>
