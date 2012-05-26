<?php

namespace Rithis\ProfilesBundle\Document;

class AgeRange
{
    /**
     * @var integer
     */
    protected $ageFrom;

    /**
     * @var integer
     */
    protected $ageTo;

    /**
     * @param int $ageFrom
     */
    public function setAgeFrom($ageFrom)
    {
        $this->ageFrom = $ageFrom;
    }

    /**
     * @return int
     */
    public function getAgeFrom()
    {
        return $this->ageFrom;
    }

    /**
     * @param int $ageTo
     */
    public function setAgeTo($ageTo)
    {
        $this->ageTo = $ageTo;
    }

    /**
     * @return int
     */
    public function getAgeTo()
    {
        return $this->ageTo;
    }
}
