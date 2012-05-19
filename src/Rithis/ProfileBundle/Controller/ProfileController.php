<?php

namespace Rithis\ProfileBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Templating\TemplateReference;
use Symfony\Component\Form\FormInterface;

use FOS\RestBundle\View\View;
use FOS\RestBundle\View\RouteRedirectView;
use FOS\Rest\Util\Codes;

use Rithis\ProfileBundle\UserConverter;

class ProfileController extends Controller
{
    public function newRequestsAction(Request $request) {
        $view = View::create();
        $view->setData($this->getNewRequestsData($request));
        $view->setTemplate(new TemplateReference('RithisProfileBundle', 'Requests', 'newRequests'));

        return $view;
    }

    public function getProfileAction($hash) {
        $mongo_id = new MongoID($hash);
        $dbuser=$this->get('mongodb')->users->findOne(array("_id",$mongo_id));

        $converter=new UserConverter($dbuser);
        $user=$converter->fromDb();

        $view = View::create();
        $view->setData(array('user'=>$user));
        $view->setTemplate(new TemplateReference('RithisProfileBundle', 'Profile', 'profile'));

        return $view;
    }
}
