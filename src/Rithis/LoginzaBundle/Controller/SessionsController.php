<?php

namespace Rithis\LoginzaBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Templating\TemplateReference;
use Rithis\LoginzaBundle\Form\Type\RegistrationType;

class SessionsController extends Controller
{
    public function newSessionsAction()
    {
        return $this->render('RithisLoginzaBundle:Sessions:newSessions.html.twig');
    }

    public function postSessionsAction()
    {
        if ($this->get('security.context')->isGranted('ROLE_REGISTERED')) {
            return $this->redirect($this->container->getParameter('loginza.redirect_url'));
        } else {
            return $this->redirect($this->generateUrl('loginza_new_session_credentionals'));
        }
    }

    public function newSessionCredentionalsAction()
    {
        $userData = $this->get('security.context')->getToken()->getAttribute('loginza');

        $form = $this->createForm(new RegistrationType(), array(
            'email' => isset($userData->email) ? $userData->email : null,
        ));

        return $this->render('RithisLoginzaBundle:Sessions:newSessionCredentionals.html.twig', array(
            'form' => $form->createView(),
        ));
    }

    public function postSessionCredentionalsAction(Request $request)
    {
        $form = $this->createForm(new RegistrationType());

        $form->bindRequest($request);
        if ($form->isValid()) {
            $user = $this->getUser();
            $data = $form->getData();

            $salt = md5(time());
            $encoder = $this->get('security.encoder_factory')->getEncoder($user);
            $userData = $user->getData();

            $userData['email'] = $data['email'];
            $userData['password_salt'] = $salt;
            $userData['password_hash'] = $encoder->encodePassword($data['password'], $salt);
            $userData['roles'][] = $data['role'];

            $this->get('mongodb')->users->save($userData);

            return $this->redirect($this->container->getParameter('loginza.redirect_url'));
        }

        var_dump($form);die;
    }

    public function removeSessionAction()
    {
        return $this->render('RithisLoginzaBundle:Sessions:removeSession.html.twig');
    }
}
