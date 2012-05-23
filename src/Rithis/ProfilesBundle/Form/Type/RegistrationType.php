<?php

namespace Rithis\ProfilesBundle\Form\Type;

use Symfony\Component\Form\FormBuilder;

class RegistrationType extends CredentialsType
{
    public function buildForm(FormBuilder $builder, array $options)
    {
        $builder->add('email', 'email');
        $builder->add('password', 'password');
        $builder->add('role', 'choice', array('choices' => array('ROLE_SPONSOR' => 'Sponsor', 'ROLE_FREE' => 'Free')));

        parent::buildForm($builder, $options);

        $builder->add('license', 'checkbox', array('required' => true));
    }

    protected function add(FormBuilder $builder, array $formOptions, $child, $type = null, array $options = array())
    {
        $builder->add($child, $type, $options);
    }

    public function getName()
    {
        return 'registration';
    }
}
