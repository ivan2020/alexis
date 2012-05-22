<?php

namespace Rithis\ProfilesBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilder;


class TimeRangeType extends AbstractType
{
    public function buildForm(FormBuilder $builder, array $options)
    {
        $builder->add('start', 'date');
        $builder->add('end', 'date');
    }

    public function getName()
    {
        return 'im';
    }
}
