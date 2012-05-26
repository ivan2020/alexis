<?php

namespace Rithis\ProfilesBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Templating\TemplateReference;
use Symfony\Component\Security\Core\Authentication\Token\UsernamePasswordToken;
use FOS\RestBundle\View\View;
use Rithis\ProfilesBundle\Form\Type\EmailConfirmationType;

class EmailController extends Controller
{
    public function getEmailConfirmationAction(Request $request, $id)
    {
        $dm = $this->get('doctrine.odm.mongodb.document_manager');
        $user = $dm->getRepository('RithisProfilesBundle:Profile')->find($id);

        if (!$user || $user->isConfirmed()) {
            throw $this->createNotFoundException();
        }

        $data = array();
        if ($request->query->has('token')) {
            $data['token'] = $request->query->get('token');
        }

        $form = $this->createForm(new EmailConfirmationType(), $data);

        $view = View::create();
        $view->setData(array('form' => $form, 'user' => $user));
        $view->setTemplate(new TemplateReference('RithisProfilesBundle', 'Email', 'getEmailConfirmation'));

        return $view;
    }

    public function putEmailAction(Request $request, $id)
    {
        $dm = $this->get('doctrine.odm.mongodb.document_manager');
        $user = $dm->getRepository('RithisProfilesBundle:Profile')->find($id);

        $form = $this->createForm(new EmailConfirmationType());
        $form->bindRequest($request);
        $data = $form->getData();

        if ($user->getPasswordSalt() == $data['token']) {
            $user->addRole('ROLE_EMAIL_CONFIRMED');
            $dm->flush();

            $token = new UsernamePasswordToken($user, null, 'secured_area', $user->getRoles());
            $this->get('security.context')->setToken($token);

            return $this->redirect($this->generateUrl('profile_get_profile', array('id' => $id)));
        }

        $form->get('token')->addError(new \Symfony\Component\Form\FormError('Token is wrong'));

        $view = View::create();
        $view->setData(array('form' => $form, 'user' => $user));
        $view->setTemplate(new TemplateReference('RithisProfilesBundle', 'Email', 'getEmailConfirmation'));

        return $view;
    }
}
