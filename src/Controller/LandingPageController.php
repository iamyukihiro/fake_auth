<?php
declare(strict_types=1);

namespace App\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Twig\Environment;

class LandingPageController
{
    public function __construct(
        private readonly Environment $twig
    ) {
    }

    #[Route('/', name: 'app_landing_page')]
    public function index(): Response
    {
        return new Response($this->twig->render('/langing_page.html.twig'));
    }
}
