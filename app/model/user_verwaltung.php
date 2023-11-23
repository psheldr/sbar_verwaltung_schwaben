<?php

class UserVerwaltung {
    private $db = null;

    function __construct($db) {
        $this->db = $db;
    }

    function wandleArrayZuObjekt($userArray) {
        $user = new User();
        $user->setId($userArray['id']);
        $user->setSalt($userArray['salt']);
        $user->setPasswort($userArray['passwort']);
        $user->setUsername($userArray['username']);
        $user->setLetzterLogin($userArray['letzter_login']);
        $user->setAktuellerLogin($userArray['aktueller_login']);
        $user->setRecht($userArray['recht']);
        return $user;
    }

    function wandleSqlZuObjekten($sql, $bedingungen = array()) {
        $abfrage = $this->db->prepare($sql);
        $abfrage->execute($bedingungen);
        $usersArray = $abfrage->fetchAll();
        $userObjekte = array();
        foreach ($usersArray as $userArray) {
            $userObjekte[] = $this->wandleArrayZuObjekt($userArray);
        }
        return $userObjekte;
    }

    function findeAlle() {
        $sql = "SELECT * FROM tbl_user";
        return $this->wandleSqlZuObjekten($sql);
    }
 
    function findeAnhandVonId($id) {
        $sql = "SELECT * FROM tbl_user WHERE id=?";
        $abfrage = $this->db->prepare($sql);
        $abfrage->execute(array($id));
        $userArray = $abfrage->fetch();
        return $this->wandleArrayZuObjekt($userArray);
    }

      function findeAnhandVonUsername($username) {
        $sql = "SELECT * FROM tbl_user WHERE username=?";
        $abfrage = $this->db->prepare($sql);
        $abfrage->execute(array($username));
        $userArray = $abfrage->fetch();
        return $this->wandleArrayZuObjekt($userArray);
    }

    function fuegeUserHinzu(User $user) {
        $sql = "INSERT INTO tbl_user (salt, passwort, username, letzter_login, aktueller_login, recht) VALUES (?,?,?,?,?,?)";
        $abfrage = $this->db->prepare($sql);
        $abfrage->execute(array(
                $user->getSalt(),
                $user->getPasswort(),
                $user->getUsername(),
                $user->getLetzterLogin(),
                $user->getAktuellerLogin(),
                $user->getRecht()
            ));
        $user->setId($this->db->lastInsertId());
    }

    function aendereUser(User $user) {
        $sql = "UPDATE tbl_user SET salt=?, passwort=?, username=?, letzter_login=?,aktueller_login=?, recht=? WHERE id=?";
        $abfrage = $this->db->prepare($sql);
        $daten = array(
            $user->getSalt(),
            $user->getPasswort(),
            $user->getUsername(),
            $user->getLetzterLogin(),
            $user->getAktuellerLogin(),
            $user->getRecht(),
            $user->getId()
        );
        $abfrage->execute($daten);
        return true;
    }

    function speichere(User $user) {
        if (!$user->istValide()) {
            return false;
        }
        if ($user->getId()) {
            $this->aendereUser($user);
        } else {
            $this->fuegeUserHinzu($user);
        }
        return true;
    }
}

?>
