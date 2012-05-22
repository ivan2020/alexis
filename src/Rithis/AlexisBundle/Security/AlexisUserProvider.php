<?php

namespace Rithis\AlexisBundle\Security;

use Symfony\Component\Security\Core\User\UserProviderInterface;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\DependencyInjection\Container;

class AlexisUserProvider implements UserProviderInterface
{
    private $collection;
    private $container;

    public function __construct(\MongoCollection $collection, Container $container)
    {
        $this->collection = $collection;
        $this->container = $container;
    }

    public function loadUserByUsername($username)
    {
        $userData = $this->collection->findOne(array('identities' => $username));

        if (!$userData) {
            $userData = array(
                'identities' => array($username),
                'roles' => array('ROLE_USER'),
            );

            $this->collection->insert($userData, array('safe' => true));
        }

        return new User($userData, $username);
    }

    public function refreshUser(UserInterface $user)
    {
        return $this->loadUserByUsername($user->getUsername());
    }

    public function supportsClass($class)
    {
        return $class === 'Rithis\AlexisBundle\Security\User';
    }
}
