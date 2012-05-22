<?php

namespace Rithis\ProfilesBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilder;

class PreferencesType extends AbstractType
{
    public function buildForm(FormBuilder $builder, array $options)
    {
        $builder->add('sex', 'choice', array('choices' => array('F' => 'женщина', 'M' => 'мужчина')));
        $builder->add('age', 'integer');
    }

    public function getName()
    {
        return 'preferences';
    }
}
