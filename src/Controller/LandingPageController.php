<?php
declare(strict_types=1);

namespace App\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Component\Routing\Annotation\Route;

class LandingPageController
{
    #[IsGranted('ROLE_USER')]
    #[Route('/', name: 'app_landing_page')]
    public function index(): array
    {
        return [];
    }
}
