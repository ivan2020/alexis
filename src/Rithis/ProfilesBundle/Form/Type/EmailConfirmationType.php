<?php

namespace Rithis\ProfilesBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilder;
use Rithis\ProfilesBundle\Document\Profile;
use Rithis\ProfilesBundle\Form\ChoiceList\TravelChoiceList;

class EmailConfirmationType extends AbstractType
{
    public function buildForm(FormBuilder $builder, array $options)
    {
        $builder->add('token');
    }

    public function getName()
    {
        return 'email_confirmation';
    }
}
