<?php

namespace Rithis\TravelBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Bundle\FrameworkBundle\Templating\TemplateReference;
use FOS\RestBundle\View\View;

class TravelsController extends Controller
{


    public function getTravelsRandomAction()
    {
        $result=$this->get('doctrine.odm.mongodb.document_manager')
            ->getRepository('RithisTravelBundle:Travel')
            ->findRandomTravels(3);

        $view = View::create();
        $view->setData(array('result' => $result));
        $view->setTemplate(new TemplateReference('RithisTravelBundle', 'Default', 'getTravelsRandom'));

        return $view;
    }

    public function getTravelAction($name)
    {
        $result = array_values(iterator_to_array($this->get('mongodb')->travel->find()));

        return $this->render('RithisTravelBundle:Default:index.html.twig', array('name' => $name));
    }
}
