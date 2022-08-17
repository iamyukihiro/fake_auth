<?php
declare(strict_types=1);

namespace App\Controller;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class LandingPageController
{
    #[Route('/', name: 'app_landing_page')]
    public function index(): Response
    {
        return new Response('<h1>ランディングページ</h1>');
    }
}
