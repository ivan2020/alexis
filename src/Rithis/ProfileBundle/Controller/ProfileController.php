<?php

namespace Rithis\ProfileBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Templating\TemplateReference;
use Symfony\Component\Form\FormInterface;

use FOS\RestBundle\View\View;
use FOS\RestBundle\View\RouteRedirectView;
use FOS\Rest\Util\Codes;

class ProfileController extends Controller
{
    public function newRequestsAction(Request $request)
    {
        $view = View::create();
        $view->setData($this->getNewRequestsData($request));
        $view->setTemplate(new TemplateReference('RithisProfileBundle', 'Requests', 'newRequests'));

        return $view;
    }
}
