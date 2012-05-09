<?php

namespace Rithis\AlexisBundle\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class DefaultControllerTest extends WebTestCase
{
    public function testIndex()
    {
        $client = static::createClient();

        $crawler = $client->request('GET', '/hello/world');

        $this->assertTrue($crawler->filter('html:contains("Hello world")')->count() > 0);
    }
}
