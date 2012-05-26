<?php

namespace Rithis\ProfilesBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilder;
use Rithis\ProfilesBundle\Document\Profile;
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
        $builder->add('nickname');

        $this->add($builder, $options, 'firstName');

        $this->add($builder, $options, 'lastName');

        $this->add($builder, $options, 'avatar');

        $this->add($builder, $options, 'birthday', new BirthdayType());

        $this->add($builder, $options, 'weight', 'integer');

        $this->add($builder, $options, 'height', 'integer');

        $this->add($builder, $options, 'sex', 'choice', array(
            'choices' => array('F' => 'женщина', 'M' => 'мужчина'),
        ));

        $this->add($builder, $options, 'about', 'textarea');

        if ($options['data'] instanceof Profile && $options['data']->isSponsor()) {
            $this->add($builder, $options, 'budget', 'integer');
        }

        $this->add($builder, $options, 'preferences', new PreferencesType());

//        $this->add($builder, $options, 'timerange', new TimeRangeType());

        /*
        $builder->add('photos', 'text');
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
