<?php

namespace Rithis\ProfilesBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilder;
use Rithis\ProfilesBundle\Form\ChoiceList\TravelChoiceList;

class ProfileType extends AbstractType
{
    protected $travelChoiceList;

    public function __construct(TravelChoiceList $travelChoiceList)
    {
        $this->travelChoiceList = $travelChoiceList;
    }

    public function buildForm(FormBuilder $builder, array $options)
    {
        $this->add($builder, $options, 'nickname');

        $this->add($builder, $options, 'firstName');

        $this->add($builder, $options, 'lastName');

        $this->add($builder, $options, 'avatar');

        $this->add($builder, $options, 'birthday', 'birthday', array(
            'format' => 'dd MMMM yyyy',
        ));

        $this->add($builder, $options, 'weight', 'integer');

        $this->add($builder, $options, 'height', 'integer');

        $this->add($builder, $options, 'sex', 'choice', array(
            'choices' => array('F' => 'женщина', 'M' => 'мужчина'),
        ));

        $this->add($builder, $options, 'about', 'textarea');

        if ($options['data']->isSponsor()) {
            $this->add($builder, $options, 'budget', 'integer');
        }

        /*
        $builder->add('preferences', new PreferencesType());
        $builder->add('photos', 'text');
        $builder->add('timerange', new TimeRangeType());
        $builder->add('travel', 'choice', array('choice_list' => $this->travelChoiceList));
        */
    }

    protected function add(FormBuilder $builder, array $formOptions, $child, $type = null, array $options = array())
    {
        $builder->add($child, $type, $options);
    }

    public function getName()
    {
        return 'profile';
    }
}
