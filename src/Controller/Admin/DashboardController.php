<?php
declare(strict_types=1);

namespace App\Controller\Admin;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;
use Twig\Environment;

class DashboardController
{
    public function __construct(
        private readonly Environment $twig
    ) {
    }

    #[IsGranted('ROLE_USER')]
    #[Route('/admin/dashboard', name: 'app_admin_dashboard')]
    public function index(): Response
    {
        return new Response($this->twig->render('/admin/dashboard.html.twig'));
    }
}
