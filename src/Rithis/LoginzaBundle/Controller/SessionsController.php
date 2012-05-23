<?php

namespace Rithis\LoginzaBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class SessionsController extends Controller
{
    public function newSessionsAction()
    {
        return $this->render('RithisLoginzaBundle:Sessions:newSessions.html.twig');
    }

    public function postSessionsAction()
    {
        return $this->redirect($this->container->getParameter('loginza.redirect_url'));
    }

    public function removeSessionAction()
    {
        return $this->render('RithisLoginzaBundle:Sessions:removeSession.html.twig');
    }

    public function deleteSessionAction()
    {
    }
}
