<?php
/** @noinspection NonAsciiCharacters */
declare(strict_types=1);

namespace App\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\KernelBrowser;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class LandingPageControllerTest extends WebTestCase
{
    private ?KernelBrowser $client;

    protected function tearDown(): void
    {
        $this->client = null;

        parent::tearDown();
    }

    public function test_ランディングページへアクセスできること(): void
    {
        $crawler = $this->client->request('GET', '/');

        $response = $this->client->getResponse();
        $this->assertTrue($response->isOk(), (string) $response->getStatusCode());
    }
}
