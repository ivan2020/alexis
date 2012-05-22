<?php

namespace Rithis\AlexisBundle\Security;

use Symfony\Component\Security\Core\User\UserInterface;

class User implements UserInterface
{
    private $data;
    private $identity;

    public function __construct(array $data, $identity)
    {
        $this->data = $data;
        $this->identity = $identity;
    }

    public function getData()
    {
        return $this->data;
    }

    public function getRoles()
    {
        return isset($this->data['roles']) ? $this->data['roles'] : array();
    }

    public function getPassword()
    {
        return isset($this->data['password_hash']) ? $this->data['password_hash'] : null;
    }

    public function getSalt()
    {
        return isset($this->data['password_salt']) ? $this->data['password_salt'] : null;
    }

    public function getUsername()
    {
        return isset($this->data['email']) ? $this->data['email'] : $this->identity;
    }

    public function eraseCredentials()
    {
    }
}
