<?php
class FahrerCode {
    private $id =0;
    private $code = 0;
    private $name = 0;
    private $faktor = 0;

    function __construct($daten = array()) {
        if(isset($daten['id'])) {
            $this->setId($daten['id']);
        }
        if(isset($daten['code'])) {
            $this->setCode($daten['code']);
        }
        if(isset($daten['name'])) {
            $this->setName($daten['name']);
        }  
    }

    function setId($id) {
        $this->id = $id;
    }
    function setCode($code) {
        $this->code = $code;
    }
    function setName($name) {
        $this->name = $name;
    }
 
    function getId() {
        return $this->id;
    }
    function getCode() {
        return $this->code;
    }
    function getName() {
        return $this->name;
    }
  

   function istValide() {
        return empty($this->errors);
    }
}
        
?>
