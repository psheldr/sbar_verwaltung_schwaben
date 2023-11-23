<?php

class User {
    private $id = 0;
    private $salt = '';
    private $passwort = '';
    private $username = '';
    private $letzter_login = 0;
    private $aktueller_login = 0;
    private $recht = 0;
    private $errors = array();


    function __construct($daten = array()) {
        if (isset($daten['id'])) {
            $this->setId($daten['id']);
        }
        if (isset($daten['salt'])) {
            $this->setSalt($daten['salt']);
        }
        if (isset($daten['passwort'])) {
            $this->setPasswort($daten['passwort']);
        }
        if (isset($daten['username'])) {
            $this->setUsername($daten['username']);
        }
        if (isset($daten['letzter_login'])) {
            $this->setLetzterLogin($daten['letzter_login']);
        }
        if (isset($daten['aktueller_login'])) {
            $this->setAktuellerLogin($daten['letzaktueller_loginter_login']);
        }
        if (isset($daten['recht'])) {
            $this->setRecht($daten['recht']);
        }
    }


    function setId($id) {
        $this->id = $id;
    }
    function setSalt($salt) {
        $this->salt = $salt;
    }
    function setPasswort($passwort) {
        $this->passwort = $passwort;
    }
    function setUsername($username) {
        $this->username = $username;
    }
    function setLetzterLogin($letzter_login) {
        $this->letzter_login = $letzter_login;
    }
    function setAktuellerLogin($aktueller_login) {
        $this->aktueller_login = $aktueller_login;
    }
    function setRecht($recht) {
        $this->recht = $recht;
    }

    function getId() {
        return $this->id;
    }
    function getSalt() {
        return $this->salt;
    }
    function getPasswort() {
        return $this->passwort;
    }
    function getUsername() {
        return $this->username;
    }
    function getLetzterLogin() {
        return $this->letzter_login;
    }
    function getAktuellerLogin() {
        return $this->aktueller_login;
    }
    function getRecht() {
        return $this->recht;
    }

    function istValide() {
        return empty($this->errors);
    }
    function zeigeFehler() {
        return implode('<br />', $this->errors);
    }

}

?>
