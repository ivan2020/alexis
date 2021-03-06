<?php

namespace Rithis\LoginzaBundle\DependencyInjection\Security;

use Symfony\Component\Security\Http\Firewall\ListenerInterface;
use Symfony\Component\HttpKernel\Event\GetResponseEvent;
use Symfony\Component\Security\Core\SecurityContextInterface;
use Symfony\Component\Security\Core\User\User;
use Symfony\Component\Security\Core\Exception\AuthenticationException;
use Symfony\Component\Security\Core\Authentication\AuthenticationManagerInterface;
use Symfony\Component\HttpFoundation\Response;

class LoginzaListener implements ListenerInterface
{
    private $securityContext;
    private $authenticationManager;
    private $config;

    public function __construct(SecurityContextInterface $securityContext, AuthenticationManagerInterface $authenticationManager, array $config = array())
    {
        $this->securityContext = $securityContext;
        $this->authenticationManager = $authenticationManager;
        $this->config = $config;
    }

    public function handle(GetResponseEvent $event)
    {
        $request = $event->getRequest();

        if ($request->request->has('token')) {
            $token = $request->request->get('token');

            if ($this->config['is_secure']) {
                $sig = md5($request->request->get('token') . $this->config['secret_key']);
                $url = sprintf('http://loginza.ru/api/authinfo?token=%s&id=%s&sig=%s', $token, $this->config['widget_id'], $sig);
            } else {
                $url = sprintf('http://loginza.ru/api/authinfo?token=%s', $token);
            }

            $response = json_decode(file_get_contents($url));

            if (empty($response)) {
                throw new AuthenticationException("Wrong loginza responce format");
            }
            if (isset($response->error_type)) {
                throw new AuthenticationException($response->error_message);
            }

            $user = new User($response->identity, null, array('ROLE_USER'));

            $token = new LoginzaToken($user->getRoles());
            $token->setUser($user);
            $token->setAuthenticated(true);
            $token->setAttribute('loginza', $response);

            try {
                $returnValue = $this->authenticationManager->authenticate($token);

                return $this->securityContext->setToken($returnValue);
            } catch (AuthenticationException $e) {
                // you might log something here
            }
        }
    }
}
