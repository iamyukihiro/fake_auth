<?php
/** @noinspection NonAsciiCharacters */
declare(strict_types=1);

namespace App\Tests\Controller;

use App\Entity\User;
use Liip\TestFixturesBundle\Services\DatabaseToolCollection;
use Symfony\Bundle\FrameworkBundle\KernelBrowser;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class LandingPageControllerTest extends WebTestCase
{
    private ?KernelBrowser $client;

    public function setUp(): void
    {
        static::ensureKernelShutdown();
        $this->client = $this::createClient();

        $dataBaseToolCollection = $this->client->getContainer()->get(DatabaseToolCollection::class);
        $databaseTool = $dataBaseToolCollection->get();
        $databaseTool->loadAliceFixture([
            __DIR__ . '/../Resources/Fixtures/Controller/landing_page_controller.yml'
        ]);
    }

    protected function tearDown(): void
    {
        $this->client = null;

        parent::tearDown();
    }

    public function test_未ログイン_アクセスできること(): void
    {
        $this->client->request('GET', '/');
        $response = $this->client->getResponse();
        $this->assertTrue($response->isOk(), (string) $response->getStatusCode());
        $this->assertIsInt(strpos($response->getContent(), '<span>を表示する際は、ログインしてください。</span>'));
    }

    public function test_ログイン中_アクセスできること(): void
    {
        $user = $this->client->getContainer()->get('doctrine')
            ->getRepository(User::class)
            ->findOneBy(['username' => 'test@example.com'])
        ;
        $this->client->loginUser($user);
        $this->client->request('GET', '/');

        $response = $this->client->getResponse();
        $this->assertTrue($response->isOk(), (string) $response->getStatusCode());
        $this->assertIsInt(strpos($response->getContent(), '<a href="/logout">ログアウト</a>'));
    }
}
