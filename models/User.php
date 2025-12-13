<?php

namespace models;

class User
{
    public $id;
    public $imie;
    public $nazwisko;
    public $typ; // 0 - rodzic, 1 - nauczyciel, 2 - dyrekcja
    public $telefon;
    public $email;
    public $firstLogin;

    function __construct($id = -1, $imie="", $nazwisko="", $typ=[], $telefon="", $email="", $firstLogin=false)
    {
        $this->id = $id;
        $this->imie = $imie;
        $this->nazwisko = $nazwisko;
        $this->typ = $typ;
        $this->telefon = $telefon;
        $this->email = $email;
        $this->firstLogin = $firstLogin;
    }
//    public function setImie($imie)
//    {
//        $this->imie = $imie;
//    }
//    public function getImie()
//    {
//        return $this->imie;
//    }
//    public function setNazwisko($nazwisko)
//    {
//        $this->nazwisko = $nazwisko;
//    }
//    public function getNazwisko()
//    {
//        return $this->nazwisko;
//    }
//    public function setTyp($typ)
//    {
//        $this->typ = $typ;
//    }
//    public function getTyp()
//    {
//        return $this->typ;
//    }
//    public function setTelefon($telefon){
//        $this->telefon = $telefon;
//    }
//    public function getTelefon(){
//        return $this->telefon;
//    }
//    public function setEmail($email){
//        $this->email = $email;
//    }
//    public function getEmail(){
//        return $this->email;
//    }


}
