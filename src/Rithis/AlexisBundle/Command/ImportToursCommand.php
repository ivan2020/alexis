<?php

namespace Rithis\AlexisBundle\Command;

use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class ImportToursCommand extends ContainerAwareCommand
{
    const NULL_TIMESTAMP = "0x0000000000000000";
    const MIN_INT = -2147483647;

    private $collection;
    private $token;
    private $delTimestamp;

    protected function configure()
    {
        $this->setName("alexis:import:tours");
        $this->setDescription("Import tours from Pegas Touristik");
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $this->collection = $this->getContainer()->get('mongodb')->samo;
        $this->token = $this->getContainer()->getParameter('alexis_samo_token');

        $this->delTimestamp = $this->getTimestamp('del');
        if ($this->delTimestamp == self::NULL_TIMESTAMP) {
            $this->delTimestamp = $this->fetchCurrentTimestamp();
        }

        foreach ($this->getReferenceDescriptions() as $reference) {
            $this->importReference($reference[0], $reference[1], $reference[2]);
        }

        $this->importDirections();

        $this->setTimestamp('del', $this->fetchCurrentTimestamp());
    }

    private function importReference($type, $collection, $fields)
    {
        $timestamp = $this->getTimestamp($type);
        $xml = simplexml_load_file($this->generateUrl($type, $timestamp, $this->delTimestamp));

        $collection->ensureIndex('inc', array('unique' => true));

        $forUpdate = array();
        $forRemove = array();

        foreach ($xml->Data->{$type} as $row) {
            $attributes = $row->attributes();

            $inc = (int)$attributes->inc;
            $status = (string)$attributes->status;

            if ($status == "D") {
                $forRemove[] = $inc;
            } elseif ($inc != self::MIN_INT) {
                $forUpdate[$inc] = $this->convertRow($attributes, $fields);
                $timestamp = (string)$attributes->stamp;
            }
        }

        foreach ($forUpdate as $inc => $row) {
            $collection->update(array('inc' => $inc), array(
                '$set' => $row
            ), array('upsert' => true));
        }

        foreach ($forRemove as $inc) {
            $collection->remove(array('inc' => $inc));
        }

        $this->setTimestamp($type, $timestamp);
    }

    private function importDirections()
    {
        $collection = $this->collection->directions;
        $temporaryCollection = $collection->temporary;
        $temporaryCollection->drop();

        $xml = simplexml_load_file($this->generateUrl('townstate'));
        foreach ($xml->Data->townstate as $row) {
            $attributes = $row->attributes();
            $temporaryCollection->insert(array('town' => (int)$attributes->town, 'state' => (int)$attributes->state));
        }

        $collection->drop();
        $this->getContainer()->get('mongodb.connection')->admin->command(array(
            'renameCollection' => (string)$temporaryCollection,
            'to' => (string)$collection
        ));
    }

    private function convertRow($attributes, $fields)
    {
        $result = array();

        foreach ($fields as $field) {
            if (is_string($field[0])) {
                $source = $destination = $field[0];
            } else {
                list($source, $destination) = $field[0];
            }

            $value = (string)$attributes->{$source};

            if ($value == self::MIN_INT) {
                $value = null;
            } elseif (empty($value)) {
                continue;
            }

            if (isset($field[1])) {
                $value = $field[1]($value);
            }

            $result[$destination] = $value;
        }

        return $result;
    }

    private function fetchCurrentTimestamp()
    {
        $xml = simplexml_load_file($this->generateUrl('currentstamp'));

        return (string)$xml->Data->currentstamp->attributes()->stamp;
    }

    private function getTimestamp($name)
    {
        $timestamp = $this->collection->stamps->findOne(array("_id" => $name));

        if ($timestamp) {
            return $timestamp['value'];
        }

        return self::NULL_TIMESTAMP;
    }

    private function setTimestamp($name, $timestamp)
    {
        $this->collection->stamps->save(array('_id' => $name, 'value' => $timestamp));
    }

    private function generateUrl($type, $lastTimestamp = null, $delTimestamp = null)
    {
        $url = 'http://agency.pegast.ru/samo5/export/default.php?samo_action=reference';

        $url .= '&oauth_token=' . $this->token;
        $url .= '&type=' . $type;

        if ($lastTimestamp) {
            $url .= '&laststamp=' . $lastTimestamp;
        }

        if ($delTimestamp) {
            $url .= '&delstamp=' . $delTimestamp;
        }

        return $url;
    }

    private function getReferenceDescriptions()
    {
        $int = function ($x) { return (int)$x; };
        $float = function ($x) { return (float)$x; };
        $date = function ($x) {
            $time = strptime($x, '%FT%T');

            return new \MongoDate(mktime($time['tm_hour'], $time['tm_min'], $time['tm_sec'], $time['tm_mon'] + 1, $time['tm_mday'], $time['tm_year'] + 1900));
        };

        return array(
            array('state', $this->collection->states, array(
                array(array('name', 'name_ru')),
                array(array('lname', 'name_en')),
            )),
            array('region', $this->collection->regions, array(
                array(array('name', 'name_ru')),
                array(array('lname', 'name_en')),
                array('state', $int),
            )),
            array('town', $this->collection->towns, array(
                array(array('name', 'name_ru')),
                array(array('lname', 'name_en')),
                array('state', $int),
                array('region', $int),
            )),
            array('star', $this->collection->stars, array(
                array(array('name', 'name_ru')),
                array(array('lname', 'name_en')),
            )),
            array('hotel', $this->collection->hotels, array(
                array(array('name', 'name_ru')),
                array(array('lname', 'name_en')),
                array('star', $int),
                array('town', $int),
            )),
            array('room', $this->collection->rooms, array(
                array(array('name', 'name_ru')),
                array(array('lname', 'name_en')),
            )),
            array('htplace', $this->collection->places, array(
                array(array('name', 'name_ru')),
                array(array('lname', 'name_en')),
                array('pcount', $int),
                array('adult', $int),
                array('child', $int),
                array('infant', $int),
                array('age1min', $float),
                array('age1max', $float),
                array('age2min', $float),
                array('age2max', $float),
                array('age3min', $float),
                array('age3max', $float),
            )),
            array('meal', $this->collection->meals, array(
                array(array('name', 'name_ru')),
                array(array('lname', 'name_en')),
            )),
            array('class', $this->collection->flight_classes, array(
                array(array('name', 'name_ru')),
                array(array('lname', 'name_en')),
                array('alias'),
            )),
            array('port', $this->collection->airports, array(
                array(array('name', 'name_ru')),
                array(array('lname', 'name_en')),
                array('alias'),
                array('town', $int),
            )),
            array('freight', $this->collection->freights, array(
                array(array('name', 'name_ru')),
                array(array('lname', 'name_en')),
                array('trantype', $int),
                array('source', $int),
                array(array('srcport', 'source_airport'), $int),
                array(array('target', 'destination'), $int),
                array(array('trgport', 'destination_airport'), $int),
            )),
            array('servtype', $this->collection->service_types, array(
                array(array('name', 'name_ru')),
                array(array('lname', 'name_en')),
            )),
            array('service', $this->collection->services, array(
                array(array('name', 'name_ru')),
                array(array('lname', 'name_en')),
                array(array('servtype', 'service_type'), $int),
            )),
            array('insure', $this->collection->insurance_types, array(
                array(array('name', 'name_ru')),
                array(array('lname', 'name_en')),
                array('state', $int),
            )),
            array('visapr', $this->collection->visa_types, array(
                array(array('name', 'name_ru')),
                array(array('lname', 'name_en')),
                array('state', $int),
            )),
            array('currency', $this->collection->currencies, array(
                array(array('name', 'name_ru')),
                array(array('lname', 'name_en')),
            )),
            array('tour', $this->collection->tours, array(
                array(array('name', 'name_ru')),
                array(array('lname', 'name_en')),
                array('state', $int),
                array(array('townfrom', 'town'), $int),
            )),
            array('spog', $this->collection->spo, array(
                array(array('fullnumber', 'number')),
                array('tour', $int),
                array(array('spodate', 'date'), $date),
                array(array('datebeg', 'begin'), $date),
                array(array('dateend', 'end'), $date),
                array(array('rqdatebeg', 'rq_begin'), $date),
                array(array('rqdateend', 'rq_end'), $date),
                array('note'),
            )),
            array('stopsale', $this->collection->stopsale, array(
                array(array('datebeg', 'begin'), $date),
                array(array('dateend', 'end'), $date),
                array('hotel', $int),
                array('room', $int),
                array(array('htplace', 'place'), $int),
                array('meal', $int),
                array('checkin', $int),
                array('nights', $int),
                array(array('spog', 'spo'), $int),
                array(array('townfrom', 'town'), $int),
            )),
            array('freighttime', $this->collection->freights_schedule, array(
                array('freight', $int),
                array('source', $int),
                array(array('srcport', 'source_airport'), $int),
                array(array('target', 'destination'), $int),
                array(array('trgport', 'destination_airport'), $int),
                array('trantype', $int),
            )),
        );
    }
}
