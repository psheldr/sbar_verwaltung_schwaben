<?php

class Rechnungsadresse {
    private $id = 0;
    private $kunde_id = 0;
    private $firma = '';
    private $vorname = '';
    private $nachname = '';
    private $strasse = '';
    private $plz = '';
    private $ort = '';
    private $email = '';
    private $aktiv = 0;
    private $errors = array();

    function __construct($daten = array()) {
        if (isset($daten['id'])) {
            $this->setId($daten['id']);
        }
        if (isset($daten['kunde_id'])) {
            $this->setKundeId($daten['kunde_id']);
        }
        if (isset($daten['firma'])) {
            $this->setFirma($daten['firma']);
        }
        if (isset($daten['vorname'])) {
            $this->setVorname($daten['vorname']);
        }
        if (isset($daten['nachname'])) {
            $this->setNachname($daten['nachname']);
        }
        if (isset($daten['strasse'])) {
            $this->setStrasse($daten['strasse']);
        }
        if (isset($daten['plz'])) {
            $this->setPlz($daten['plz']);
        }
        if (isset($daten['ort'])) {
            $this->setOrt($daten['ort']);
        }
        if (isset($daten['email'])) {
            $this->setEmail($daten['email']);
        }
        if (isset($daten['aktiv'])) {
            $this->setAktiv($daten['aktiv']);
        }
    }

    function setId($id) {
        $this->id = $id;
    }
    function setKundeId($kunde_id) {
        $this->kunde_id = $kunde_id;
    }
    function setFirma($firma) {
        $this->firma = $firma;
    }
    function setVorname($vorname) {
        $this->vorname = $vorname;
    }
    function setNachname($nachname) {
        $this->nachname = $nachname;
    }
    function setStrasse($strasse) {
        $this->strasse = $strasse;
    }
    function setPlz($plz) {
        $this->plz = $plz;
    }
    function setOrt($ort) {
        $this->ort = $ort;
    }
    function setEmail($email) {
        $this->email = $email;
    }
    function setAktiv($aktiv) {
        $this->aktiv = $aktiv;
    }

    function getId() {
        return $this->id;
    }
    function getFirma() {
        return $this->firma;
    }
    function getKundeId() {
        return $this->kunde_id;
    }
    function getVorname() {
        return $this->vorname;
    }
    function getNachname() {
        return $this->nachname;
    }
    function getStrasse() {
        return $this->strasse;
    }
    function getPlz() {
        return $this->plz;
    }
    function getOrt() {
        return $this->ort;
    }
    function getEmail() {
        return $this->email;
    }
    function getAktiv() {
        return $this->aktiv;
    }



    function istValide()
    {
        return empty($this->errors);
    }
    function zeigeFehler() {
        return implode('<br />', $this->errors);
    }
}
?>
