<?php
class Traeger {
    private $id = 0;
    private $bezeichnung = '';
    private $asp = '';
    private $strasse = '';
    private $plz = 0;
    private $ort = '';
    private $tel = '';
    private $tel2 = '';
    private $fax = '';
    private $email = '';
    private $email2 = '';
    private $email3 = '';
    private $email4 = '';
    private $email5 = '';
    private $aktiv = 0;
    private $notiz = '';
    private $matchcode = '';
    private $referenz = '';
    private $errors = array();


    function __construct($daten = array()) {
        if (isset($daten['id'])) {
            $this->setId($daten['id']);
        }
        if (isset($daten['bezeichnung'])) {
            $this->setBezeichnung($daten['bezeichnung']);
        }
        if (isset($daten['asp'])) {
            $this->setAsp($daten['asp']);
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
        if (isset($daten['tel'])) {
            $this->setTel($daten['tel']);
        }
        if (isset($daten['tel2'])) {
            $this->setTel2($daten['tel2']);
        }
        if (isset($daten['fax'])) {
            $this->setFax($daten['fax']);
        }
        if (isset($daten['email'])) {
            $this->setEmail($daten['email']);
        }
        if (isset($daten['email2'])) {
            $this->setEmail2($daten['email2']);
        }
        if (isset($daten['email3'])) {
            $this->setEmail3($daten['email3']);
        }
        if (isset($daten['email4'])) {
            $this->setEmail4($daten['email4']);
        }
        if (isset($daten['email5'])) {
            $this->setEmail5($daten['email5']);
        }
        if (isset($daten['aktiv'])) {
            $this->setAktiv($daten['aktiv']);
        }
        if (isset($daten['notiz'])) {
            $this->setNotiz($daten['notiz']);
        }
        if (isset($daten['matchcode'])) {
            $this->setMatchcode($daten['matchcode']);
        }
        if (isset($daten['referenz'])) {
            $this->setReferenz($daten['referenz']);
        }
    }


    function setId($id) {
        $this->id = $id;
    }
    function setBezeichnung($bezeichnung) {
            if (empty($bezeichnung)) {
            $this->errors[] = 'Bezeichnung';
        }
        $this->bezeichnung = $bezeichnung;
    }
    function setAsp($asp) {
        $this->asp = $asp;
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
    function setTel($tel) {
        $this->tel = $tel;
    }
    function setTel2($tel2) {
        $this->tel2 = $tel2;
    }
    function setFax($fax) {
        $this->fax = $fax;
    }
    function setEmail($email) {
        $this->email = $email;
    }
    function setEmail2($email2) {
        $this->email2 = $email2;
    }
    function setEmail3($email3) {
        $this->email3 = $email3;
    }
    function setEmail4($email4) {
        $this->email4 = $email4;
    }
    function setEmail5($email5) {
        $this->email5 = $email5;
    }
    function setAktiv($aktiv) {
        $this->aktiv = $aktiv;
    }
    function setNotiz($notiz) {
        $this->notiz = $notiz;
    }
    function setMatchcode($matchcode) {
        $this->matchcode = $matchcode;
    }
    function setReferenz($referenz) {
        $this->referenz = $referenz;
    }
    function setErrors($error) {
        $this->errors = $error;
    }

    function getId() {
        return $this->id;
    }
    function getBezeichnung() {
        return $this->bezeichnung;
    }
    function getAsp() {
        return $this->asp;
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
    function getTel() {
        return $this->tel;
    }
    function getTel2() {
        return $this->tel2;
    }
    function getFax() {
        return $this->fax;
    }
    function getEmail() {
        return $this->email;
    }
    function getEmail2() {
        return $this->email2;
    }
    function getEmail3() {
        return $this->email3;
    }
    function getEmail4() {
        return $this->email4;
    }
    function getEmail5() {
        return $this->email5;
    }
    function getAktiv() {
        return $this->aktiv;
    }
    function getNotiz() {
        return $this->notiz;
    }
    function getMatchcode() {
        return $this->matchcode;
    }
    function getReferenz() {
        return $this->referenz;
    }

    function istValide() {
        return empty($this->errors);
    }
    function zeigeFehler() {
        return implode('<br />', $this->errors);
    }

}

?>
