<?php

namespace Rithis\TravelBundle\Document;

class Resort
{
    protected $id;
    protected $name;
    protected $country;
    protected $continent;

    public function setId($id)
    {
        $this->id = $id;
    }

    public function getId()
    {
        return $this->id;
    }

    public function setName($name)
    {
        $this->name = $name;
    }

    public function getName()
    {
        return $this->name;
    }

    public function setCountry($country)
    {
        $this->country = $country;
    }

    public function getCountry()
    {
        return $this->country;
    }

    public function setContinent($continent)
    {
        $this->continent = $continent;
    }

    public function getContinent()
    {
        return $this->continent;
    }
}