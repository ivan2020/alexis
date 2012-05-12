<?php

namespace Rithis\AlexisBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilder;
use Rithis\AlexisBundle\Form\ChoiceList\CountryChoiceList;

class SearchRequestType extends AbstractType
{
    private $countryChoiceList;

    public function __construct(CountryChoiceList $countryChoiceList)
    {
        $this->countryChoiceList = $countryChoiceList;
    }

    public function buildForm(FormBuilder $builder, array $options)
    {
        $builder->add('name', 'text');

        $builder->add('country', 'choice', array("choice_list" => $this->countryChoiceList));
    }

    public function getName()
    {
        return 'search_request';
    }
}
