<?php

class Lista
{
    protected $id, $film_id, $korisnik_id, $pogledao_film;

    function __construct($id, $film_id, $korisnik_id, $pogledao_film)
    {
        $this->id = $id;
        $this->film_id = $film_id;
        $this->korisnik_id = $korisnik_id;
        $this->pogledao_film = $pogledao_film;
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