<?php

namespace App\Tests\Application;

use App\Service\Cache\FileCache;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\BrowserKit\Request;

class ResourceTest extends WebTestCase
{
    public function testApiResource(): void
    {
        $cache = new FileCache('/var/www/cache/', 10);
        $cache->clear();

        // check is cached ?
        $startTime = time();

        $client = self::createClient();
        $client->request('GET', '/resource/5');
        $this->assertResponseIsSuccessful();
        $jsonResult = json_decode($client->getResponse()->getContent(),true);
        $this->assertEquals(['resource' => 5], $jsonResult);

        $endTime = time();
        $this->assertGreaterThan(4, $endTime - $startTime);

        // check second iteration - is cached ?
        $startTime = time();

        $client->request('GET', '/resource/5');
        $this->assertResponseIsSuccessful();
        $jsonResult = json_decode($client->getResponse()->getContent(),true);
        $this->assertEquals(['resource' => 5], $jsonResult);

        $endTime = time();

        $this->assertEquals($endTime, $startTime);
    }

    public function testApiResourceLRUAlgoritm(): void
    {
        $cache = new FileCache('/var/www/cache/', 10);
        $cache->clear();

        $client = self::createClient();

        for($i=0;$i<20;$i++){

            $client->request('GET', '/resource/'.$i);
            $this->assertResponseIsSuccessful();
            $jsonResult = json_decode($client->getResponse()->getContent(),true);
            $this->assertEquals(['resource' => $i], $jsonResult);
        }

        $this->assertEquals(10, $cache->getCacheResourceCount());
    }
}
