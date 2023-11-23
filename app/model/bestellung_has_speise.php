<?php

class BestellungHasSpeise {
    private $bestellung_id = 0;
    private $speise_id = 0;
    private $speise_nr = 1;
    private $kitafino_speise_nr = 0;



    function __construct($daten = array()) {
        if (isset($daten['bestellung_id'])) {
            $this->setBestellungId($daten['bestellung_id']);
        }
        if (isset($daten['speise_id'])) {
            $this->setSpeiseId($daten['speise_id']);
        }
        if (isset($daten['speise_nr'])) {
            $this->setSpeiseNr($daten['speise_nr']);
        }
        if (isset($daten['kitafino_speise_nr'])) {
            $this->setKitafinoSpeiseNr($daten['kitafino_speise_nr']);
        }
    }


    function setBestellungId($bestellung_id) {
        $this->bestellung_id = $bestellung_id;
    }
    function setSpeiseId($speise_id) {
        $this->speise_id = $speise_id;
    }
    function setSpeiseNr($speise_nr) {
        $this->speise_nr = $speise_nr;
    }
    function setKitafinoSpeiseNr($kitafino_speise_nr) {
        $this->kitafino_speise_nr = $kitafino_speise_nr;
    }

    function getBestellungId() {
        return $this->bestellung_id;
    }
    function getSpeiseId() {
        return $this->speise_id;
    }
    function getSpeiseNr() {
        return $this->speise_nr;
    }
    function getKitafinoSpeiseNr() {
        return $this->kitafino_speise_nr;
    }
    
    
    function istValide() {
        return empty($this->errors);
    }
    function zeigeFehler() {
        return implode('<br />', $this->errors);
    }

}

?>
