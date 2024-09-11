<?php

class Komentari_ocjene2
{
    protected $komentar, $ocjena, $korisnik_username;

    function __construct($komentar, $ocjena, $korisnik_username)
    {
        $this->komentar = $komentar;
        $this->ocjena = $ocjena;
        $this->korisnik_username = $korisnik_username;
    }

    function __get($property)
    {
        if(property_exists($this, $property))
            return $this->$property;
    }

    function __set($property, $value)
    {
        if(property_exists($this, $property))
            $this->$property = $value;

        return $this; 
    }
}

?>