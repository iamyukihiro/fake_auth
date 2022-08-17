<?php
/** @noinspection NonAsciiCharacters */
declare(strict_types=1);

namespace App\Tests\Controller;

use App\Entity\User;
use Liip\TestFixturesBundle\Services\DatabaseToolCollection;
use Symfony\Bundle\FrameworkBundle\KernelBrowser;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;

class SecurityControllerTest extends WebTestCase
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

    public function test_未ログイン_ログインページへアクセスできること(): void
    {
        $this->client->request('GET', '/login');
        $response = $this->client->getResponse();
        $this->assertTrue($response->isOk(), (string) $response->getStatusCode());
        $this->assertIsInt(strpos($response->getContent(), '<span>Googleアカウントでログイン</span>'));
    }

    public function test_ログイン中_ログインページへアクセスできないこと(): void
    {
        $user = $this->client->getContainer()->get('doctrine')
            ->getRepository(User::class)
            ->findOneBy(['username' => 'test@example.com'])
        ;
        $this->client->loginUser($user);
        $this->client->request('GET', '/login');

        $response = $this->client->getResponse();
        $this->assertTrue($response->isRedirect(), (string) $response->getStatusCode());
    }

    public function test_ログインできること(): void
    {
        $this->markTestSkipped('クローラーでFormにUsernameを入力して、/admin/dashboard へ遷移するテストを書く');
    }

    public function test_ログアウトできること(): void
    {
        $router = $this->client->getContainer()->get('router');
        $this->client->followRedirects();

        $user = $this->client->getContainer()->get('doctrine')
            ->getRepository(User::class)
            ->findOneBy(['username' => 'test@example.com'])
        ;
        $this->client->loginUser($user);
        $crawler = $this->client->request('GET', '/logout');

        $response = $this->client->getResponse();
        $this->assertTrue($response->isOk(), (string) $response->getStatusCode());
        $this->assertSame($router->generate('app_landing_page', [], UrlGeneratorInterface::ABSOLUTE_URL), $crawler->getUri());
    }
}
