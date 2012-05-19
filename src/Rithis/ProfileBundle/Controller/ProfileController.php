<?php

namespace Rithis\ProfileBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Templating\TemplateReference;
use Symfony\Component\Form\FormInterface;

use FOS\RestBundle\View\View;
use FOS\RestBundle\View\RouteRedirectView;
use FOS\Rest\Util\Codes;

use Rithis\ProfileBundle\Form\ProfileForm;

class ProfileController extends Controller
{
    public function profileAction(Request $request)
    {
//        $profile = array();
        $form = $this->createForm(new ProfileForm($this->container));
        $view = View::create();
        $view->setData(array('form' => $form));
        $view->setTemplate(new TemplateReference('RithisProfileBundle', 'Profile', 'profile'));

        return $view;
    }

    public function postProfileAction(Request $request) {
        $form = $this->createForm(new ProfileForm($this->container));
        $form->bindRequest($request);

        if ($form->isValid()) {
//            $query = $form->getData();
//
//            $result = array_values(iterator_to_array($this->get('mongodb')->hotels->find($this->convertToMongoQuery($query))));
//
//            $searchRequest = array(
//                'query' => $query,
//                'result' => $result
//            );
//
//            $this->get('mongodb')->search_requests->insert($searchRequest);

            return RouteRedirectView::create('profile_get_request', array('ok' => true));
        }

        $view = View::create();
        $view->setData($this->getNewRequestsData($request, $form));
        $view->setStatusCode(Codes::HTTP_BAD_REQUEST);
        $view->setTemplate(new TemplateReference('RithisAlexisBundle', 'Profile', 'profile'));

        return $view;
    }

    private function getNewRequestsData(Request $request, FormInterface $form = null)
    {
        if ($request->getRequestFormat() == 'html') {
            $form = $form ?: $this->createForm(new ProfileForm($this->container));
            return array('form' => $form);
        }
//        else {
//            $countries = $this->get('alexis.form.choicelist.country')->getValues();
//            return array('countries' => $countries);
//        }
    }
}
