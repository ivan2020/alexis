<?php

namespace Rithis\ProfilesBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilder;
use Symfony\Component\DependencyInjection\ContainerInterface;
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
        $builder->add('name');
        $builder->add('nickname');
        $builder->add('avatar');
        $builder->add('birthday', 'birthday', array(
            'format' => 'dd MMMM yyyy'
        ));
        $builder->add('weight', 'integer');
        $builder->add('height', 'integer');
        $builder->add('sex', 'choice', array('choices' => array('F' => 'женщина', 'M' => 'мужчина')));
        $builder->add('preferences', new PreferencesType());
        $builder->add('role', 'choice', array('choices' => array('Спонсор', 'Халява')));
        $builder->add('about_me', 'textarea');
        $builder->add('photos', 'text');
        $builder->add('budget', 'integer');
        $builder->add('im', new IMType());
        $builder->add('timerange', new TimeRangeType());
        $builder->add('travel', 'choice', array('choice_list' => $this->travelChoiceList));
    }

    public function getName()
    {
        return 'profile';
    }
}
