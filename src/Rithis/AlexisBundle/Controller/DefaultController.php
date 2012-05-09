<?php

namespace Rithis\AlexisBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class DefaultController extends Controller
{
    public function indexAction($name)
    {
        return $this->render('RithisAlexisBundle:Default:index.html.twig', array('name' => $name));
    }
}
