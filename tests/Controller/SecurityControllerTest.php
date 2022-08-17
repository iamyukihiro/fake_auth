<?php
/** @noinspection NonAsciiCharacters */
declare(strict_types=1);

namespace App\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\KernelBrowser;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class SecurityControllerTest extends WebTestCase
{
    private ?KernelBrowser $client;

    public function setUp(): void
    {
        static::ensureKernelShutdown();
        $this->client = static::createClient();
    }

    protected function tearDown(): void
    {
        $this->client = null;

        parent::tearDown();
    }

    public function test_ログインページへアクセスできること(): void
    {
        $this->client->request('GET', '/login');
        $response = $this->client->getResponse();
        $this->assertTrue($response->isOk(), (string) $response->getStatusCode());
    }
}
