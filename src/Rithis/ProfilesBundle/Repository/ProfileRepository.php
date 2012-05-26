<?php

namespace Rithis\ProfilesBundle\Repository;

use Doctrine\ODM\MongoDB\DocumentRepository;
use Symfony\Component\Security\Core\User\UserProviderInterface;
use Symfony\Component\Security\Core\User\UserInterface;

class ProfileRepository extends DocumentRepository implements UserProviderInterface
{
    public function loadUserByUsername($username)
    {
        return $this->findOneBy(array('email' => $username));
    }

    public function refreshUser(UserInterface $user)
    {
        return $this->dm->merge($user);
    }

    public function supportsClass($class)
    {
        return $class == 'Rithis\\ProfilesBundle\\Document\\Profile';
    }
}
