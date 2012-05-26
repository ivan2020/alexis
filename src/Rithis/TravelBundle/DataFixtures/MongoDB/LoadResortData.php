<?php

namespace Rithis\TravelBundle\DataFixtures\MongoDB;

use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use Rithis\TravelBundle\Document\Resort;

class LoadResortData extends AbstractFixture implements OrderedFixtureInterface
{
    public function load(ObjectManager $manager)
    {
        $resort = new Resort();
        $resort->setContinent('Африка');
        $resort->setCountry('Египет');
        $resort->setName('Хургада');
        $manager->persist($resort);
        $this->addReference('resort-hurgada', $resort);

        $resort = new Resort();
        $resort->setContinent('Европа');
        $resort->setCountry('Франция');
        $resort->setName('Париж');
        $manager->persist($resort);
        $this->addReference('resort-paris', $resort);

        $resort = new Resort();
        $resort->setContinent('Азия');
        $resort->setCountry('Тайланд');
        $resort->setName('Паттайя');
        $manager->persist($resort);
        $this->addReference('resort-pattaya', $resort);

        $resort = new Resort();
        $resort->setContinent('Азия');
        $resort->setCountry('Тайланд');
        $resort->setName('Пи-Пи');
        $manager->persist($resort);
        $this->addReference('resort-pi-pi', $resort);

        $manager->flush();
    }

    public function getOrder()
    {
        return 1;
    }
}