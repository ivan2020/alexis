<?php

namespace Rithis\ProfilesBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Templating\TemplateReference;
use Symfony\Component\Form\FormInterface;

use FOS\RestBundle\View\View;
use FOS\RestBundle\View\RouteRedirectView;
use FOS\Rest\Util\Codes;

class ProfilesController extends Controller
{
    public function editProfileAction()
    {
        $view = View::create();
        $view->setData($this->getData());
        $view->setTemplate(new TemplateReference('RithisProfilesBundle', 'Profiles', 'editProfile'));

        return $view;
    }

    public function putProfileAction(Request $request)
    {
        $form = $this->getForm();
        $form->bindRequest($request);

        if ($form->isValid()) {
            $this->get('doctrine.odm.mongodb.document_manager')->flush();
            return RouteRedirectView::create('profile_edit_profile');
        }

        $view = View::create();
        $view->setData(array('form' => $form));
        $view->setStatusCode(Codes::HTTP_BAD_REQUEST);
        $view->setTemplate(new TemplateReference('RithisProfilesBundle', 'Profiles', 'editProfile'));

        return $view;
    }

    public function getProfileAction($id)
    {
        $user = $this->get('doctrine.odm.mongodb.document_manager')
            ->getRepository('RithisProfilesBundle:Profile')
            ->find($id);

        if (!$user) {
            throw $this->createNotFoundException();
        }

        $view = View::create();
        $view->setData(array('profile' => $user));
        $view->setTemplate(new TemplateReference('RithisProfilesBundle', 'Profiles', 'getProfile'));

        return $view;
    }

    private function getData()
    {
        if ($this->getRequest()->getRequestFormat() == 'html') {
            $form = $this->getForm();
            return array('form' => $form);
        } else {
            $user = $this->getUser();
            return array('profile' => $user);
        }
    }

    private function getForm()
    {
        return $this->createForm($this->get('profiles.form.type.profile'), $this->getUser());
    }
}
