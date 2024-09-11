<?php

class FilmIKom
{
    protected $id, $naziv, $redatelj, $godina, $ocjena, $moj_komentar, $moja_ocjena;

    function __construct($id, $naziv, $redatelj, $godina, $ocjena, $moj_komentar, $moja_ocjena)
    {
        $this->id = $id;
        $this->naziv = $naziv;
        $this->redatelj = $redatelj;
        $this->godina = $godina;
        $this->ocjena = $ocjena;
        $this->moj_komentar = $moj_komentar;
        $this->moja_ocjena = $moja_ocjena;
        
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