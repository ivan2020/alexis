<?php

namespace Rithis\ProfilesBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilder;

class BirthdayType extends AbstractType
{
    private $months = array(
        'января', 'февраля', 'марта', 'апреля', 'мая', 'июня',
        'июля', 'августа', 'сентября', 'октября', 'ноября', 'декабря'
    );

    public function buildForm(FormBuilder $builder, array $options)
    {
        $this->add($builder, $options, 'birthDay', 'choice', array('required' => false, 'choices' => array_combine(range(1,31), range(1,31))));
        $this->add($builder, $options, 'birthMonth', 'choice', array('required' => false, 'choices' => array_combine(range(1, 12), $this->months)));
        $this->add($builder, $options, 'birthYear', 'choice', array('required' => false, 'choices' => array_combine(range(1900, date('Y') - 18), range(1900, date('Y') - 18))));
    }

    protected function add(FormBuilder $builder, array $formOptions, $child, $type = null, array $options = array())
    {
        $builder->add($child, $type, $options);
    }

    public function getName()
    {
        return 'birthday';
    }
}
