<?php

namespace Rithis\TravelBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Bundle\FrameworkBundle\Templating\TemplateReference;
use FOS\RestBundle\View\View;

class TravelsController extends Controller
{

    public function addFakeTravelAction()
    {
        $count = $this->get('mongodb')->travel->find()->count();

        if ($count==0)
        {
            $fakeTravel=array();
            $fakeTravel[]=array('Азия'=>array('Тайланд'=>array('Пхукет'=>array(
                array(100,'http://co-traveling.ru/photo/kupon_o/0177f4ca90f187fb6a768e7f764c2902.jpg'),
                array(500,'http://co-traveling.ru/photo/kupon_o/03a6282199dc262fdf7dc94ac3dce845.JPG'),
                array(1000,'http://co-traveling.ru/photo/kupon_o/04f43bca221df7f355aed50f4aa5f7dc.jpg'),
            )
            )
            )
            );
            $fakeTravel[]=array('Европа'=>array('Франция'=>array('Париж'=>array(
                array(100,'http://co-traveling.ru/photo/kupon_o/0177f4ca90f187fb6a768e7f764c2902.jpg'),
                array(500,'http://co-traveling.ru/photo/kupon_o/03a6282199dc262fdf7dc94ac3dce845.JPG'),
                array(1000,'http://co-traveling.ru/photo/kupon_o/04f43bca221df7f355aed50f4aa5f7dc.jpg'),
            )
            ),
                'Италия'=>array('Рим'=>array(
                    array(100,'http://www.russkiyrim.com/img/RomaColosseo.jpg'),
                    array(500,'http://lifeglobe.net/media/entry/1403/Rome.jpg'),
                    array(1000,'http://kyrortu.ru/images/opisanie_italii/gorod_rim/foto_goroda_rima/foto%20goroda%20rima1.jpg'),
                )
                )
            )
            );


            $this->get('mongodb')->travel->insert($fakeTravel);
        }

        $result = array_values(iterator_to_array($this->get('mongodb')->travel->find()));

        $resultOut=array();
        foreach ($result as $val)
        {
            foreach ($val as $key1=>$val1)
            {
                if (is_array($val1))
                {
                    $resultOut[$key1]=$val1;
                }
            }
        }

        return $this->render('RithisTravelBundle:Default:index.html.twig', array('result' => $resultOut));
    }

    public function getTravelsRandomAction()
    {
        $result = $this->get('mongodb')->travel->find();

        $resultOut=array();
        foreach ($result as $val)
        {
            foreach ($val as $key1=>$val1)
            {
                if (is_array($val1))
                {
                    $resultOut[$key1]=$val1;
                }
            }
        }

        $keyArray=array();
        foreach ($resultOut as $item)
        {
            foreach ($item as $keycontinent=>$continent)
            {
                foreach ($continent as $keycountry=>$country)
                {
                    foreach ($country as $keykurort=>$kurort)
                    {
                        foreach ($kurort as $price)
                        {
                            $keyArray[]=array('place'=>$keycontinent.' '.$keycountry.' '.$keykurort,'price'=>$price[0],'img'=>$price[1]);
                        }
                    }
                }
            }
        }

        shuffle($keyArray);
        $result=array_slice($keyArray, 0, 3);


        $view = View::create();
        $view->setData(array('result' => $result));
        $view->setTemplate(new TemplateReference('RithisTravelBundle', 'Default', 'getTravelsRandom'));

        return $view;
    }

    public function getTravelAction($name)
    {
        $result = array_values(iterator_to_array($this->get('mongodb')->travel->find()));

        return $this->render('RithisTravelBundle:Default:index.html.twig', array('name' => $name));
    }
}
