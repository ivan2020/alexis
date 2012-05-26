<?php

namespace Rithis\ProfilesBundle\Document;

class Preferences
{
    /**
     * @var array
     */
    protected $sex;

    /**
     * @var array
     */
    protected $ageRange;

    /**
     * @var int
     */
    protected $weight;

    /**
     * @var int
     */
    protected $height;

    /**
     * @var int
     */
    protected $boobsSize;

    /**
     * @var string
     */
    protected $hairColor;

    /**
     * @var boolean
     */
    protected $hasPhoto;

    /**
     * @param array $ageRange
     */
    public function setAgeRange($ageRange)
    {
        $this->ageRange = $ageRange;
    }

    /**
     * @return array
     */
    public function getAgeRange()
    {
        return $this->ageRange;
    }

    /**
     * @param int $boobsSize
     */
    public function setBoobsSize($boobsSize)
    {
        $this->boobsSize = $boobsSize;
    }

    /**
     * @return int
     */
    public function getBoobsSize()
    {
        return $this->boobsSize;
    }

    /**
     * @param string $hairColor
     */
    public function setHairColor($hairColor)
    {
        $this->hairColor = $hairColor;
    }

    /**
     * @return string
     */
    public function getHairColor()
    {
        return $this->hairColor;
    }

    /**
     * @param boolean $hasPhoto
     */
    public function setHasPhoto($hasPhoto)
    {
        $this->hasPhoto = $hasPhoto;
    }

    /**
     * @return boolean
     */
    public function getHasPhoto()
    {
        return $this->hasPhoto;
    }

    /**
     * @param int $height
     */
    public function setHeight($height)
    {
        $this->height = $height;
    }

    /**
     * @return int
     */
    public function getHeight()
    {
        return $this->height;
    }

    /**
     * @param array $sex
     */
    public function setSex($sex)
    {
        $this->sex = $sex;
    }

    /**
     * @return array
     */
    public function getSex()
    {
        return $this->sex;
    }

    /**
     * @param int $weight
     */
    public function setWeight($weight)
    {
        $this->weight = $weight;
    }

    /**
     * @return int
     */
    public function getWeight()
    {
        return $this->weight;
    }
}
