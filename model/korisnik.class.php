<?php

class Korisnik
{
    protected $id, $username, $ime, $prezime;
    protected $email, $lozinka, $neprimjereni_kom;

    function __construct($id, $username, $ime, $prezime,
                         $email, $lozinka, $neprimjereni_kom)
    {
        $this->id = $id;
        $this->username = $username;
        $this->ime = $ime;
        $this->prezime = $prezime;
        $this->email = $email;
        $this->lozinka = $lozinka;
        $this->neprimjereni_kom = $neprimjereni_kom;
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