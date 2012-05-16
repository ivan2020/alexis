<?php

namespace Rithis\AlexisBundle\Command;

use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class ImportHotelsCommand extends ContainerAwareCommand
{
    private $textTags = array("company-id", "country", "admn-area", "sub-admn-area", "locality-name", "street",
        "email", "url", "sub-locality-name", "house-add", "address-add");

    private $intTags = array("house", "km", "build");

    private $batchSize = 100;

    protected function configure()
    {
        $this->setName("alexis:import:hotels");
        $this->setDescription("Import hotels from Pegas Touristik");
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $dom = new \DOMDocument();
        $dom->loadXML(file_get_contents('http://hotels.pegast.su/xml'));

        $db = $this->getContainer()->get('mongodb');
        $collection = $db->hotels;
        $temporaryCollection = $collection->temporary;
        $temporaryCollection->drop();

        $cachedData = array();
        foreach ($collection->find(array(), array('company_id' => 1, 'photos' => 1)) as $cache) {
            $cachedData[$cache['company_id']] = $cache;
        }

        $hotelsBatch = array();

        foreach ($dom->getElementsByTagName('company') as $companyElement) {
            $hotel = $this->parseHotel($companyElement);

            if (array_key_exists($hotel['company_id'], $cachedData)) {
                $hotel = array_merge($hotel, $cachedData[$hotel['company_id']]);
            }

            $output->writeln($hotel['name']);
            $hotelsBatch[] = $hotel;

            if (count($hotelsBatch) == $this->batchSize) {
                $temporaryCollection->batchInsert($hotelsBatch);
                $hotelsBatch = array();
            }
        }

        if (count($hotelsBatch) > 0) {
            $temporaryCollection->batchInsert($hotelsBatch);
        }

        $collection->drop();
        $this->getContainer()->get('mongodb.connection')->admin->command(array(
            'renameCollection' => (string)$temporaryCollection,
            'to' => (string)$collection
        ));
    }

    private function parseHotel(\DOMElement $hotelElement)
    {
        $hotel = array(
            'name' => trim($hotelElement->getElementsByTagName('name-ru')->item(0)->textContent)
        );

        if ($descriptionElement = $hotelElement->getElementsByTagName('description')->item(0)) {
            $hotel['description'] = str_replace('&nbsp;', ' ', $descriptionElement->textContent);
            $hotel['description'] = html_entity_decode($hotel['description'], ENT_NOQUOTES, 'UTF-8');
            $hotel['description'] = trim($hotel['description']);
        }

        foreach ($this->textTags as $tag) {
            if ($element = $hotelElement->getElementsByTagName($tag)->item(0)) {
                $hotel[str_replace('-', '_', $tag)] = trim($element->textContent);
            }
        }

        foreach ($this->intTags as $tag) {
            if ($element = $hotelElement->getElementsByTagName($tag)->item(0)) {
                $hotel[str_replace('-', '_', $tag)] = (int)$element->textContent;
            }
        }

        $hotel['coordinates'] = array(
            'lon' => (float)$hotelElement->getElementsByTagName('lon')->item(0)->textContent,
            'lat' => (float)$hotelElement->getElementsByTagName('lat')->item(0)->textContent
        );

        $hotel['phones'] = array();
        foreach ($hotelElement->getElementsByTagName('phone') as $phoneElement) {
            $hotel['phones'][] = array(
                'number' => $phoneElement->getElementsByTagName('number')->item(0)->textContent,
                'type' => $phoneElement->getElementsByTagName('type')->item(0)->textContent
            );
        }
        if (count($hotel['phones']) == 0) {
            unset($hotel['phones']);
        }

        $hotel['photos_unprocessed'] = array();
        foreach ($hotelElement->getElementsByTagName('photo') as $photoElement) {
            $hotel['photos_unprocessed'][] = array(
                'url' => $photoElement->getAttribute('url'),
                'alt' => $photoElement->getAttribute('alt'),
                'type' => $photoElement->getAttribute('type')
            );
        }
        if (count($hotel['photos_unprocessed']) == 0) {
            unset($hotel['photos_unprocessed']);
        }

        $hotel['features'] = array();
        foreach ($hotelElement->getElementsByTagName('feature-boolean') as $featureElement) {
            $hotel['features'][$featureElement->getAttribute('name')] = (bool)$featureElement->getAttribute('value');
        }

        foreach ($hotelElement->getElementsByTagName('feature-single') as $featureElement) {
            $value = trim($featureElement->getAttribute('value'));

            if (strlen($value) > 0) {
                $hotel['features'][$featureElement->getAttribute('name')] = $value;
            }
        }

        foreach ($hotelElement->getElementsByTagName('feature-enum-single') as $featureElement) {
            $hotel['features'][$featureElement->getAttribute('name')] = $featureElement->getAttribute('value');
        }

        foreach ($hotelElement->getElementsByTagName('feature-enum-multiple') as $featureElement) {
            $name = $featureElement->getAttribute('name');

            if (!array_key_exists($name, $hotel['features'])) {
                $hotel['features'][$name] = array();
            }

            $hotel['features'][$name][] = $featureElement->getAttribute('value');
        }

        return $hotel;
    }
}
