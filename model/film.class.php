<?php

class Film
{
    protected $id, $naziv, $redatelj, $godina, $ocjena;

    function __construct($id, $naziv, $redatelj, $godina, $ocjena)
    {
        $this->id = $id;
        $this->naziv = $naziv;
        $this->redatelj = $redatelj;
        $this->godina = $godina;
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