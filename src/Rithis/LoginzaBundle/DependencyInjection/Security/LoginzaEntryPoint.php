<?php

namespace Rithis\LoginzaBundle\DependencyInjection\Security;

use Symfony\Component\Security\Http\EntryPoint\AuthenticationEntryPointInterface;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Core\Exception\AuthenticationException;
use Symfony\Component\Security\Core\Exception\AuthenticationCredentialsNotFoundException;
use Symfony\Component\HttpFoundation\RedirectResponse;

class LoginzaEntryPoint implements AuthenticationEntryPointInterface
{
    private $session;
    private $config;

    public function __construct(Session $session, array $config = array())
    {
        $this->session = $session;
        $this->config = $config;
    }

    public function start(Request $request, AuthenticationException $authException = null)
    {
        if ($authException !== null && $authException instanceof AuthenticationCredentialsNotFoundException) {
            $this->session->setFlash('error', $authException->getMessage());
        }

        return new RedirectResponse($this->config['login_url']);
    }
}
