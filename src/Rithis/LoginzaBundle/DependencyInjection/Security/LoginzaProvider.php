<?php

namespace Rithis\LoginzaBundle\DependencyInjection\Security;

use Symfony\Component\Security\Core\Authentication\Provider\AuthenticationProviderInterface;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\User\UserProviderInterface;

class LoginzaProvider implements AuthenticationProviderInterface
{
    private $userProvider;

    public function __construct(UserProviderInterface $userProvider)
    {
        $this->userProvider = $userProvider;
    }

    public function authenticate(TokenInterface $token)
    {
        $userData = $token->getAttribute('loginza');

        $user = $this->userProvider->loadUserByUsername($userData->identity);

        $authenticatedToken = new LoginzaToken($user->getRoles());
        $authenticatedToken->setUser($user);
        $authenticatedToken->setAuthenticated(true);
        $authenticatedToken->setAttribute('loginza', $userData);

        return $authenticatedToken;
    }

    public function supports(TokenInterface $token)
    {
        return $token instanceof LoginzaToken;
    }
}
