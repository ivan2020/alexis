<?php
/**
 * User: Alexander Kondratenko
 * Date: 19.05.12
 * Time: 18:20
 */

namespace Rithis\ProfileBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilder;


class TimeRangeType extends AbstractType
{
    public function buildForm(FormBuilder $builder, array $options)
    {
        $builder->add('start', 'date')
                ->add('end', 'date');
    }

//    public function getDefaultOptions(array $options)
//    {
//        return array(
//            'data_class' => 'Acme\TaskBundle\Entity\Tag',
//        );
//    }

    public function getName()
    {
        return 'im';
    }
}
