<?php
/** @noinspection NonAsciiCharacters */
declare(strict_types=1);

namespace App\Tests\Controller\API\Chart;

use App\Entity\User;
use Liip\TestFixturesBundle\Services\DatabaseToolCollection;
use Symfony\Bundle\FrameworkBundle\KernelBrowser;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class GetControllerTest extends WebTestCase
{
    private ?KernelBrowser $client;

    public function setUp(): void
    {
        static::ensureKernelShutdown();
        $this->client = $this::createClient();

        $dataBaseToolCollection = $this->client->getContainer()->get(DatabaseToolCollection::class);
        $databaseTool = $dataBaseToolCollection->get();
        $databaseTool->loadAliceFixture([
            __DIR__ . '/../../../Resources/Fixtures/Controller/API/Chart/get_controller.yml'
        ]);
    }

    protected function tearDown(): void
    {
        $this->client = null;

        parent::tearDown();
    }

    public function test(): void
    {
        $user = $this->client->getContainer()->get('doctrine')
            ->getRepository(User::class)
            ->findOneBy(['username' => 'test@example.com'])
        ;
        $this->client->loginUser($user);
        $this->client->request('GET', '/api/chart');

        $response = $this->client->getResponse();
        $this->assertTrue($response->isOk(), (string) $response->getStatusCode());
        $this->assertSame($response->getContent(), '[{"sales":123,"day":"2022-10-10"},{"sales":321,"day":"2022-10-11"}]');
    }

    public function test_未ログイン_アクセスできないこと(): void
    {
        $this->client->request('GET', '/api/chart');
        $response = $this->client->getResponse();
        $this->assertTrue($response->isRedirect(), (string) $response->getStatusCode());
    }
}
