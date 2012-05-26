<?php

namespace Rithis\TravelBundle\Document;

class Travel
{
    protected $id;
    protected $price;
    protected $photo;
    protected $resort;

    public function setId($id)
    {
        $this->id = $id;
    }

    public function getId()
    {
        return $this->id;
    }

    public function setPrice($price)
    {
        $this->price = $price;
    }

    public function getPrice()
    {
        return $this->price;
    }

    public function setPhoto($photo)
    {
        $this->photo = $photo;
    }

    public function getPhoto()
    {
        return $this->photo;
    }

    public function setResort(Resort $resort)
    {
        $this->resort = $resort;
    }

    public function getResort()
    {
        return $this->resort;
    }
}