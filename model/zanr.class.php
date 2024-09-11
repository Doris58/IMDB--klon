<?php

class Zanr
{
    protected $id, $naziv;

    function __construct($id, $naziv)
    {
        $this->id = $id;
        $this->naziv = $naziv;
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