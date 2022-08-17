<?php
declare(strict_types=1);

namespace App\Controller\API\Chart;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class GetController
{
    #[IsGranted('ROLE_USER')]
    #[Route('/api/chart', name: 'app_api_get_chart', methods: ["GET"])]
    public function get(): JsonResponse
    {
        return new JsonResponse(
            [
                [
                    'sales' => 123,
                    'day' => '2022-10-10',
                ], [
                    'sales' => 321,
                    'day' => '2022-10-11',
                ],
            ],
            Response::HTTP_OK
        );
    }
}
