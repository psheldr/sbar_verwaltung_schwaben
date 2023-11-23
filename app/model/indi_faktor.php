<?php
class Indifaktor {
    private $id =0;
    private $speise_id = 0;
    private $kunde_id = 0;
    private $faktor = 0;

    function __construct($daten = array()) {
        if(isset($daten['id'])) {
            $this->setId($daten['id']);
        }
        if(isset($daten['speise_id'])) {
            $this->setSpeiseId($daten['speise_id']);
        }
        if(isset($daten['kunde_id'])) {
            $this->setKundeId($daten['kunde_id']);
        }
        if(isset($daten['faktor'])) {
            $daten['faktor'] = str_replace(',','.',$daten['faktor']);
            if ($daten['faktor'] == 0) {
                $daten['faktor'] = 1;
           }
            $this->setFaktor(number_format($daten['faktor'], 2, '.',''));
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
    function setFaktor($faktor) {
        $this->faktor = $faktor;
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
    function getFaktor() {
        if ($this->faktor == 0) {
            return 1;
        } else {
            return $this->faktor;
        }
        
    }

   function istValide() {
        return empty($this->errors);
    }
}
        
?>
