<?php

class Komentar_ocjena
{
    protected  $id, $film_id, $korisnik_id, $komentar, $ocjena;

    function __construct($id, $film_id, $korisnik_id, $komentar, $ocjena)
    {
        $this->id = $id;
        $this->film_id = $film_id;
        $this->korisnik_id = $korisnik_id;
        $this->komentar = $komentar;
        $this->ocjena = $ocjena;
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