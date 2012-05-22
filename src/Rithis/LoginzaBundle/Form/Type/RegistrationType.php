<?php

namespace Rithis\LoginzaBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilder;

class RegistrationType extends AbstractType
{
    public function buildForm(FormBuilder $builder, array $options)
    {
        $builder->add('email', 'email');
        $builder->add('password', 'password');
        $builder->add('role', 'choice', array('choices' => array('ROLE_SPONSOR' => 'Sponsor', 'ROLE_FREE' => 'Free')));

        /*
        if ($options['data']['nickname'] !== null) {
            $builder->add('nickname');
        }

        if ($options['data']['sex'] !== null) {
            $builder->add('sex', 'choice', array('choices' => array('M' => 'Male', 'F' => 'Female')));
        }

        if ($options['data']['first_name'] !== null) {
            $builder->add('first_name');
        }

        if ($options['data']['last_name'] !== null) {
            $builder->add('last_name');
        }

        if ($options['data']['birthday'] !== null) {
            $builder->add('birthday', 'date', array(
                'years' => range(1900, (int) date('Y')),
            ));
        }

        if ($options['data']['icq'] !== null) {
            $builder->add('icq');
        }

        if ($options['data']['skype'] !== null) {
            $builder->add('skype');
        }

        if ($options['data']['about_me'] !== null) {
            $builder->add('about_me', 'textarea');
        }
        */

        $builder->add('license', 'checkbox', array('required' => true));
    }

    public function getName()
    {
        return 'registration';
    }
}
