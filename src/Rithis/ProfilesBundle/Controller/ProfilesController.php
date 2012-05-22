<?php

namespace Rithis\ProfilesBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Templating\TemplateReference;
use Symfony\Component\Form\FormInterface;

use FOS\RestBundle\View\View;
use FOS\RestBundle\View\RouteRedirectView;
use FOS\Rest\Util\Codes;

class ProfilesController extends Controller
{
    public function editProfileAction($hash)
    {
        $user = $this->get('mongodb')->users->findOne(array("_id" => new \MongoID($hash)));

        $birthday = new \DateTime();
        $birthday->setTimestamp($user['birthday']->sec);
        $user['birthday'] = $birthday;

        $form = $this->createForm($this->get('profiles.form.type.profile'), $user);

        $view = View::create();
        $view->setData(array('form' => $form, 'hash' => $hash));
        $view->setTemplate(new TemplateReference('RithisProfilesBundle', 'Profiles', 'editProfile'));

        return $view;
    }

    public function getProfileAction($hash)
    {
        /*
         * db.users.insert({'nickname': 'vslinko',
         * 'avatar': '//s3-eu-west-1.amazonaws.com/rithis-alexis/hotels/4fb378e16eb7bddd10000000/714c5b4d587b6aa3916ca82c3fdd325b/original.png',
         * 'birthday': new Date(1991, 01, 19), 'weight': 80, 'height': 170, sex: 'M', 'about_me': 'Vyacheslav Slinko',
         * 'budget': 1000, 'roles': ['ROLE_SPONSOR']})
         */
        $user = $this->get('mongodb')->users->findOne(array("_id" => new \MongoID($hash)));

        if (!$user) {
            $this->createNotFoundException();
        }

        $user['age'] = date('Y') - date('Y', $user['birthday']->sec);
        $user['role'] = in_array('ROLE_SPONSOR', $user['roles']) ? 'sponsor' : 'free';

        $view = View::create();
        $view->setData(array('user' => $user));
        $view->setTemplate(new TemplateReference('RithisProfilesBundle', 'Profiles', 'getProfile'));

        return $view;
    }

    public function putProfileAction(Request $request, $hash)
    {
        $form = $this->createForm($this->get('profiles.form.type.profile'));
        $form->bindRequest($request);

        if ($form->isValid()) {
            var_dump($form->getData());
            die;
        }

        $view = View::create();
        $view->setData(array('form' => $form));
        $view->setStatusCode(Codes::HTTP_BAD_REQUEST);
        $view->setTemplate(new TemplateReference('RithisProfilesBundle', 'Profiles', 'newProfiles'));

        return $view;
    }
}
