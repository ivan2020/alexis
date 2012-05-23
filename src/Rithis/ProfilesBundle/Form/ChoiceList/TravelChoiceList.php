<?php

namespace Rithis\ProfilesBundle\Form\ChoiceList;

use Symfony\Component\Form\Extension\Core\ChoiceList\LazyChoiceList;
use Symfony\Component\Form\Extension\Core\ChoiceList\SimpleChoiceList;

class TravelChoiceList extends LazyChoiceList
{
    private $db;

    public function __construct(\MongoDB $db)
    {
        $this->db = $db;
    }

    protected function loadChoiceList()
    {
        $response = $this->db->command(array(
            'distinct' => 'hotels',
            'key' => 'country'
        ));

        $choices = array();

        if ($response['ok'] && count($response['values']) > 0) {
            $choices = array_combine($response['values'], $response['values']);
        }

        return new SimpleChoiceList($choices);
    }
}
