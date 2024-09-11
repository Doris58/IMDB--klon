<?php

class Film_zanr
{
    protected $id, $film_id, $zanr_id;

    function __construct($id, $film_id, $zanr_id)
    {
        $this->id = $id;
        $this->film_id = $film_id;
        $this->zanr_id = $zanr_id;
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