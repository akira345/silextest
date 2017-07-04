<?php

use Silex\WebTestCase;

class controllersTest extends WebTestCase
{
    public function testGetHomepage()
    {
        $client = $this->createClient();
        $client->followRedirects(true);
        $crawler = $client->request('GET', '/');

        $this->assertTrue($client->getResponse()->isOk());
        $this->assertContains('Default', $crawler->filter('body')->text());
    }

    public function createApplication()
    {
        require_once __DIR__ . './../vendor/autoload.php';
        $app = new Testapp\Application();
        $app['session.test'] = true;

        return $this->app = $app;
    }
}
