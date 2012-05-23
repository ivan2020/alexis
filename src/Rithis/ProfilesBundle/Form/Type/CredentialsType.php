<?php

namespace Rithis\ProfilesBundle\Form\Type;

use Symfony\Component\Form\FormBuilder;

class CredentialsType extends ProfileType
{
    public function __construct()
    {
    }

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
        if (!is_string($child)) {
            return parent::add($builder, $formOptions, $child, $type, $options);
        }

        $profileData = $formOptions['data']->toArray();

        if (isset($profileData[$child]) && ($profileData[$child] instanceof \DateTime || strlen($profileData[$child]) > 0)) {
            return parent::add($builder, $formOptions, $child, $type, $options);
        }
    }

    public function getName()
    {
        return 'credentials';
    }
}
