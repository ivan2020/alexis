<?php

namespace Rithis\AlexisBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Templating\TemplateReference;
use Symfony\Component\Form\FormInterface;

use FOS\RestBundle\View\View;
use FOS\RestBundle\View\RouteRedirectView;
use FOS\Rest\Util\Codes;

use Rithis\AlexisBundle\Form\Type\SearchRequestType;

class RequestsController extends Controller
{
    public function newRequestsAction(Request $request)
    {
        $view = View::create();
        $view->setData($this->getNewRequestsData($request));
        $view->setTemplate(new TemplateReference('RithisAlexisBundle', 'Requests', 'newRequests'));

        return $view;
    }

    public function postRequestsAction(Request $request)
    {
        $form = $this->get('alexis.form.search_request');
        $form->bindRequest($request);

        if ($form->isValid()) {
            $query = $form->getData();

            $result = array_values(iterator_to_array($this->get('mongodb')->hotels->find($this->convertToMongoQuery($query))));

            $searchRequest = array(
                'query' => $query,
                'result' => $result
            );

            $this->get('mongodb')->search_requests->insert($searchRequest);

            return RouteRedirectView::create('alexis_get_request', array('hash' => $searchRequest['_id']));
        }

        $view = View::create();
        $view->setData($this->getNewRequestsData($request, $form));
        $view->setStatusCode(Codes::HTTP_BAD_REQUEST);
        $view->setTemplate(new TemplateReference('RithisAlexisBundle', 'Requests', 'newRequests'));

        return $view;
    }

    public function getRequestAction($hash)
    {
        $searchRequest = $this->get('mongodb')->search_requests->findOne(array('_id' => new \MongoId($hash)));

        if (!$searchRequest) {
            throw $this->createNotFoundException();
        }

        $view = View::create();
        $view->setData(array('request' => $searchRequest));
        $view->setTemplate(new TemplateReference('RithisAlexisBundle', 'Requests', 'getRequest'));

        return $view;
    }

    private function getNewRequestsData(Request $request, FormInterface $form = null)
    {
        if ($request->getRequestFormat() == 'html') {
            $form = $form ?: $this->get('alexis.form.search_request');
            return array('form' => $form);
        } else {
            $countries = $this->get('alexis.form.choicelist.country')->getValues();
            return array('countries' => $countries);
        }
    }

    private function convertToMongoQuery($data)
    {
        $query = array();

        if (!empty($data['name'])) {
            $query['name'] = array('$regex' => $data['name'], '$options' => 'i');
        }

        if (!empty($data['country'])) {
            $query['country'] = $data['country'];
        }

        return $query;
    }
}
