<?php
declare(strict_types=1);

namespace App\Controller;

use Symfony\Component\DependencyInjection\Attribute\When;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Twig\Environment;

class SecurityController
{
    public function __construct(
        private readonly Environment $twig
    ) {
    }

    #[Route("/login", name: "app_login", methods: ["GET"])]
    public function login(): Response
    {
        return new Response($this->twig->render('/security/login.html.twig'));
    }

    #[Route('/logout', name: 'app_logout', methods: ["GET"])]
    public function logout(): void
    {
        //noop
    }

    #[Route('/login/fake-google-oauth', name: 'app_fake_google_oauth')]
    #[When(env: 'dev')]
    public function auth(): Response
    {
        return new Response($this->twig->render('/security/dev/fake-google-oauth.html.twig'));
    }
}
