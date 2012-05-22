<?php

namespace Rithis\ProfilesBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilder;


class IMType extends AbstractType
{
    public function buildForm(FormBuilder $builder, array $options)
    {
        $builder->add('icq');
        $builder->add('skype');
    }

    public function getName()
    {
        return 'im';
    }
}
