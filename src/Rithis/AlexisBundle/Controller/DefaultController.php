<?php

namespace Rithis\AlexisBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Templating\TemplateReference;
use Symfony\Component\Form\FormInterface;

use FOS\RestBundle\View\View;
use FOS\RestBundle\View\RouteRedirectView;
use FOS\Rest\Util\Codes;

use Rithis\AlexisBundle\Form\Type\SearchRequestType;

class DefaultController extends Controller
{
    public function defaultAction(Request $request)
    {
        $view = View::create();
        $view->setData(array('test'=>'Test'));
        $view->setTemplate(new TemplateReference('RithisAlexisBundle', '', 'default'));

        return $view;
    }

}
