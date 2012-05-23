<?php

namespace Rithis\ProfilesBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use FOS\RestBundle\View\View;
use Rithis\ProfilesBundle\Form\Type\RegistrationType;
use Rithis\ProfilesBundle\Document\Profile;

class CredentialsController extends Controller
{
    public function getCredentialsCheckAction()
    {
        if ($this->get('security.context')->isGranted('ROLE_REGISTERED')) {
            return $this->redirect($this->generateUrl('mainpage'));
        } else {
            return $this->redirect($this->generateUrl('profile_new_profile_credentials'));
        }
    }

    public function newCredentialsAction()
    {
        $loginzaData = $this->get('security.context')->getToken()->getAttribute('loginza');

        $profile = $this->getUser();
        $profile->loadFromArray(array(
            'nickname' => isset($loginzaData->nickname) ? $loginzaData->nickname : null,
            'sex' => isset($loginzaData->gender) ? $loginzaData->gender : null,
            'firstName' => isset($loginzaData->name->first_name) ? $loginzaData->name->first_name : null,
            'lastName' => isset($loginzaData->name->last_name) ? $loginzaData->name->last_name : null,
            'about' => isset($loginzaData->biography) ? $loginzaData->biography : null,
            'birthday' => isset($loginzaData->dob) ? \DateTime::createFromFormat('Y-m-d', $loginzaData->dob) : null,
        ));

        $form = $this->createForm(new RegistrationType(), $profile);

        return $this->render('RithisProfilesBundle:Credentials:newCredentials.html.twig', array(
            'form' => $form->createView(),
        ));
    }

    public function putCredentialsAction(Request $request)
    {
        $form = $this->createForm(new RegistrationType(), $this->getUser(), array(
            'validation_groups' => 'credentials',
        ));

        $form->bindRequest($request);
        if ($form->isValid()) {
            $user = $form->getData();

            $salt = md5(time());
            $encoder = $this->get('security.encoder_factory')->getEncoder($user);

            $user->setPasswordSalt($salt);
            $user->setPasswordHash($encoder->encodePassword($user->password, $salt));
            $user->addRole($user->role);

            $this->get('doctrine.odm.mongodb.document_manager')->flush();

            return $this->redirect($this->generateUrl('mainpage'));
        }

        return $this->render('RithisProfilesBundle:Credentials:newCredentials.html.twig', array(
            'form' => $form->createView(),
        ));
    }
}
