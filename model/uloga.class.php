<?php

class Uloga
{
    protected $id, $glumac_id, $film_id;

    function __construct($id, $glumac_id, $film_id)
    {
        $this->id = $id;
        $this->glumac_id = $glumac_id;
        $this->film_id = $film_id;
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