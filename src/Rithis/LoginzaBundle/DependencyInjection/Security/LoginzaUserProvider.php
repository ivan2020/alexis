<?php

namespace Rithis\LoginzaBundle\DependencyInjection\Security;

use Symfony\Component\Security\Core\User\UserProviderInterface;
use Symfony\Component\Security\Core\User\UserInterface;

class LoginzaUserProvider implements UserProviderInterface
{
    public function loadUserByUsername($username)
    {
        throw new \RuntimeException("Not supported");
    }

    public function refreshUser(UserInterface $user)
    {
        return $user;
    }

    public function supportsClass($class)
    {
        return $class === 'Symfony\Component\Security\Core\User\User';
    }
}
