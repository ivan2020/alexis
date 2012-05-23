<?php

namespace Rithis\ProfilesBundle\Security;

use Symfony\Component\Security\Core\User\UserProviderInterface;
use Symfony\Component\Security\Core\User\UserInterface;
use Doctrine\ODM\MongoDB\DocumentManager;
use Rithis\ProfilesBundle\Document\Profile;

class UserProvider implements UserProviderInterface
{
    private $dm;
    private $repository;

    public function __construct(DocumentManager $dm, $documentName)
    {
        $this->dm = $dm;
        $this->repository = $dm->getRepository($documentName);
    }

    public function loadUserByUsername($identity)
    {
        $profile = $this->repository->findOneBy(array('identities' => $identity));

        if (!$profile) {
            $profile = new Profile();
            $profile->addIdentity($identity);
            $profile->addRole('ROLE_USER');

            $this->dm->persist($profile);
            $this->dm->flush();
        }

        return $profile;
    }

    public function refreshUser(UserInterface $user)
    {
        return $this->dm->merge($user);
    }

    public function supportsClass($class)
    {
        return $class === 'Rithis\ProfilesBundle\Document\Profile';
    }
}
