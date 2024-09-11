<?php

class Admin
{
    protected $id, $ime, $prezime, $email, $lozinka;

    function __construct($id, $ime, $prezime, $email, $lozinka)
    {
        $this->id = $id;
        $this->ime = $ime;
        $this->prezime = $prezime;
        $this->email = $email;
        $this->lozinka = $lozinka;
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