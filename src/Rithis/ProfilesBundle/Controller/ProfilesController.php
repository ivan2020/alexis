<?php

namespace Rithis\ProfilesBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Templating\TemplateReference;
use Symfony\Component\Security\Core\Authentication\Token\UsernamePasswordToken;
use FOS\RestBundle\View\View;
use FOS\RestBundle\View\RouteRedirectView;
use FOS\Rest\Util\Codes;
use Rithis\ProfilesBundle\Form\Type\RegistrationType;
use Rithis\ProfilesBundle\Document\Profile;

class ProfilesController extends Controller
{
    public function newProfilesAction()
    {
        if ($this->get('security.context')->isGranted('ROLE_USER')) {
            throw $this->createNotFoundException();
        }

        $view = View::create();
        $view->setData(array('form' => $this->getRegistrationForm()));
        $view->setTemplate(new TemplateReference('RithisProfilesBundle', 'Profiles', 'newProfiles'));

        return $view;
    }

    public function postProfilesAction(Request $request)
    {
        $form = $this->getRegistrationForm();
        $form->bindRequest($request);

        if ($form->isValid()) {
            $user = $form->getData();

            $encoder = $this->get('security.encoder_factory')->getEncoder($user);
            $user->addRole('ROLE_USER');
            $user->mergeFormData($encoder);

            $dm = $this->get('doctrine.odm.mongodb.document_manager');
            $dm->persist($user);
            $dm->flush();

            $token = new UsernamePasswordToken($user, null, 'secured_area', $user->getRoles());
            $this->get('security.context')->setToken($token);

            return RouteRedirectView::create('mainpage');
        }

        $view = View::create();
        $view->setData(array('form' => $form));
        $view->setStatusCode(Codes::HTTP_BAD_REQUEST);
        $view->setTemplate(new TemplateReference('RithisProfilesBundle', 'Profiles', 'newProfiles'));

        return $view;
    }

    public function editProfileAction()
    {
        $view = View::create();
        $view->setData($this->getData());
        $view->setTemplate(new TemplateReference('RithisProfilesBundle', 'Profiles', 'editProfile'));

        return $view;
    }

    public function putProfileAction(Request $request)
    {
        $form = $this->getProfileForm();
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
            $form = $this->getProfileForm();

            return array('form' => $form);
        } else {
            $user = $this->getUser();

            return array('profile' => $user);
        }
    }

    private function getProfileForm()
    {
        return $this->createForm($this->get('profiles.form.type.profile'), $this->getUser());
    }

    private function getRegistrationForm()
    {
        return $this->createForm(new RegistrationType(), new Profile(), array(
            'validation_groups' => array('Default', 'credentials')
        ));
    }
}
