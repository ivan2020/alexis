<?php

namespace Rithis\ProfilesBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilder;

class AgeRangeType extends AbstractType
{
    public function buildForm(FormBuilder $builder, array $options)
    {
        $this->add($builder, $options, 'ageFrom', 'integer', array('required' => false));
        $this->add($builder, $options, 'ageTo', 'integer', array('required' => false));
    }

    protected function add(FormBuilder $builder, array $formOptions, $child, $type = null, array $options = array())
    {
        $builder->add($child, $type, $options);
    }

    public function getDefaultOptions()
    {
        return array(
            'data_class' => 'Rithis\ProfilesBundle\Document\AgeRange',
        );
    }

    public function getName()
    {
        return 'ageRange';
    }
}
