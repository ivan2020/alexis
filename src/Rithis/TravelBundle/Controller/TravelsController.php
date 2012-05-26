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

    public function getTravelsAction()
    {
        $result=$this->get('doctrine.odm.mongodb.document_manager')
            ->getRepository('RithisTravelBundle:Travel')->findAllTravels();

        $view = View::create();
        $view->setData(array('result' => $result));
        $view->setTemplate(new TemplateReference('RithisTravelBundle', 'Default', 'index'));

        return $view;
    }

    public function getTravelsItemAction($id)
    {
        $result=$this->get('doctrine.odm.mongodb.document_manager')
            ->getRepository('RithisTravelBundle:Travel')->findOneById($id);

        $view = View::create();
        $view->setData(array('item' => $result));
        $view->setTemplate(new TemplateReference('RithisTravelBundle', 'Default', 'getTravelItem'));

        return $view;
    }
}
