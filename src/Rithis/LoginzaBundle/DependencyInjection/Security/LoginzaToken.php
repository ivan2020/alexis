<?php

namespace Rithis\LoginzaBundle\DependencyInjection\Security;

use Symfony\Component\Security\Core\Authentication\Token\AbstractToken;

class LoginzaToken extends AbstractToken
{
    public function __construct(array $roles)
    {
        if (!isset($roles['ROLE_LOGINZA_USER'])) {
            $roles[] = 'ROLE_LOGINZA_USER';
        }

        parent::__construct($roles);
    }

    public function getCredentials()
    {
        return '';
    }
}
