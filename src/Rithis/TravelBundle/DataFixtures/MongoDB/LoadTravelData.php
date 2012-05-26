<?php

namespace Rithis\TravelBundle\DataFixtures\MongoDB;

use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use Rithis\TravelBundle\Document\Travel;

class LoadTravelData extends AbstractFixture implements OrderedFixtureInterface
{
    public function load(ObjectManager $manager)
    {
        $hurgada = $manager->merge($this->getReference('resort-hurgada'));
        $paris = $manager->merge($this->getReference('resort-paris'));
        $pattaya = $manager->merge($this->getReference('resort-pattaya'));
        $pipi = $manager->merge($this->getReference('resort-pi-pi'));

        $travel = new Travel();
        $travel->setPrice(100);
        $travel->setResort($hurgada);
        $travel->setPhoto('http://www.russkiyrim.com/img/RomaColosseo.jpg');
        $manager->persist($travel);

        $travel = new Travel();
        $travel->setPrice(500);
        $travel->setResort($hurgada);
        $travel->setPhoto('http://lifeglobe.net/media/entry/1403/Rome.jpg');
        $manager->persist($travel);

        $travel = new Travel();
        $travel->setPrice(1000);
        $travel->setResort($paris);
        $travel->setPhoto('http://www.earth-tour.ru/images/stories/France/wer2.jpg');
        $manager->persist($travel);

        $travel = new Travel();
        $travel->setPrice(1000);
        $travel->setResort($pattaya);
        $travel->setPhoto('http://www.paragontravel.ru/sites/default/files/tours/img_2006517_13423838.jpg');
        $manager->persist($travel);

        $travel = new Travel();
        $travel->setPrice(2000);
        $travel->setResort($pattaya);
        $travel->setPhoto('http://tourhot.ru/uploads/posts/2008-01/1201002808_pattaya.jpg');
        $manager->persist($travel);

        $travel = new Travel();
        $travel->setPrice(800);
        $travel->setResort($pipi);
        $travel->setPhoto('http://pattayathailand.ru/images/Phi-Phi-Island-0.jpg');
        $manager->persist($travel);

        $travel = new Travel();
        $travel->setPrice(1000);
        $travel->setResort($pipi);
        $travel->setPhoto('http://www.i-assist.cz/files/editor/image/Fotky%20destinac%C3%AD/Thajsko/phi%20phi.jpg');
        $manager->persist($travel);

        $manager->flush();
    }

    public function getOrder()
    {
        return 2;
    }
}