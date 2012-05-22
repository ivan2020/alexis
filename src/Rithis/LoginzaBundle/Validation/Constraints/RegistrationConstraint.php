<?php

namespace Rithis\LoginzaBundle\Validation\Constraints;

use Symfony\Component\Validator\Constraints;

class RegistrationConstraint extends Constraints\Collection
{
    public function __construct()
    {
        parent::__construct(array(
        ));
    }
}
