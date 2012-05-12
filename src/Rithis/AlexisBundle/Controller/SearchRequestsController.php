<?php

namespace Rithis\AlexisBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Rithis\AlexisBundle\Form\Type\SearchRequestType;

class SearchRequestsController extends Controller
{
    public function newSearchRequestsAction()
    {
        $form = $this->get('alexis.form.search_request');

        return $this->render('RithisAlexisBundle:SearchRequest:newSearchRequests.html.twig', array('form' => $form->createView()));
    }

    public function postSearchRequestsAction(Request $request)
    {
        $form = $this->get('alexis.form.search_request');
        $form->bindRequest($request);

        if ($form->isValid()) {
            $data = $form->getData();

            $query = $this->convertDataToQuery($data);
            $result = array_values(iterator_to_array($this->get('mongodb')->hotels->find($query)));

            $searchRequest = array(
                'query' => $query,
                'result' => $result
            );

            $this->get('mongodb')->search_requests->insert($searchRequest);

            return $this->redirect($this->generateUrl('alexis_get_searchrequest', array('hash' => $searchRequest['_id'])));
        }

        return $this->render('RithisAlexisBundle:SearchRequest:newSearchRequests.html.twig', array('form' => $form->createView()));
    }

    public function getSearchRequestAction($hash)
    {
        $searchRequest = $this->get('mongodb')->search_requests->findOne(array('_id' => new \MongoId($hash)));

        if (!$searchRequest) {
            throw $this->createNotFoundException();
        }

        return $this->render('RithisAlexisBundle:SearchRequest:getSearchRequest.html.twig', array('search_request' => $searchRequest));
    }

    private function convertDataToQuery($data)
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
