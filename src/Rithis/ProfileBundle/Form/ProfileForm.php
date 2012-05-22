<?php
/**
 * User: Alexander Kondratenko
 * Date: 19.05.12
 * Time: 14:02
 */

namespace Rithis\ProfileBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilder;
use Symfony\Component\DependencyInjection\ContainerInterface;
//use Symfony\Component\Translation\Translator;

use Rithis\ProfileBundle\Form\Type\PreferencesType;
use Rithis\ProfileBundle\Form\Type\IMType;
use Rithis\ProfileBundle\Form\Type\TimeRangeType;
use Rithis\ProfileBundle\Form\ChoiceList\TravelChoiceList;


class ProfileForm extends AbstractType
{
//    protected $translator;

    protected $container;

    public function __construct(ContainerInterface $container) //, Translator $translator)
    {
//        $this->translator = $translator;
        $this->container = $container;
    }

    public function buildForm(FormBuilder $builder, array $options)
    {
        $travel = new TravelChoiceList($this->container->get('mongodb'));
        $builder->add('name', 'text')
                ->add('nickname', 'text')
                ->add('avatar', 'file')
                ->add('birthdate', 'birthday', array(
                    'format' => 'dd MMMM yyyy'
                ))
                ->add('weight', 'integer')
                ->add('height', 'integer')
                ->add('sex', 'choice', array('choices' => array('F' => 'женщина', 'M' => 'мужчина')))
                ->add('preferences', new PreferencesType())
                ->add('roles', 'choice', array('choices' => array('Спонсор', 'Халява')))
                ->add('about_me', 'textarea')
                ->add('photos', 'text')
                ->add('budget', 'integer')
                ->add('im', new IMType())
                ->add('timerange', new TimeRangeType())
                ->add('travel', 'choice', array('choices' => $travel->getChoices()))
        ;
    }

//    public function getDefaultOptions(array $options)
//    {
//        return array(
//            'data_class' => 'Acme\TaskBundle\Entity\Task',
//        );
//    }

    public function getName() {
        return 'Profile';
    }
}
