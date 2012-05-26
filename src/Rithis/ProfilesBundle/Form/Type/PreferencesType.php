<?php

namespace Rithis\ProfilesBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilder;

use Rithis\ProfilesBundle\Form\Type\AgeRangeType;

class PreferencesType extends AbstractType
{
    public function buildForm(FormBuilder $builder, array $options)
    {
        $this->add($builder, $options, 'sex', 'choice', array('required' => false, 'choices' => array('F' => 'женщина', 'M' => 'мужчина'), 'multiple' => true, 'expanded' => true));

        $this->add($builder, $options, 'ageRange', new AgeRangeType());

        $this->add($builder, $options, 'weight', 'integer', array('required' => false));

        $this->add($builder, $options, 'height', 'integer', array('required' => false));

        $this->add($builder, $options, 'hairColor', 'text', array('required' => false));

        $this->add($builder, $options, 'boobsSize', 'integer', array('required' => false));

        $this->add($builder, $options, 'hasPhoto', 'checkbox', array('required' => false));
    }

    protected function add(FormBuilder $builder, array $formOptions, $child, $type = null, array $options = array())
    {
        $builder->add($child, $type, $options);
    }

    public function getDefaultOptions()
    {
        return array(
            'data_class' => 'Rithis\ProfilesBundle\Document\Preferences',
        );
    }

    public function getName()
    {
        return 'preferences';
    }
}
