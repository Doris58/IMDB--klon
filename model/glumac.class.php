<?php

class Glumac
{
    protected $id, $ime;

    function __construct($id, $ime)
    {
        $this->id = $id;
        $this->ime = $ime;
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