<?php

namespace App\Controller\Api\Account;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Attribute\Route;

class HairColorsController extends AbstractController
{
    #[Route('/api/hair_colors', name: 'account.hair_colors')]
    public function list(): JsonResponse
    {
        return $this->json([
            ['key' => 1, 'name' => 'Блондинка'],
            ['key' => 2, 'name' => 'Брюнетка'],
            ['key' => 3, 'name' => 'Рыжая'],
            ['key' => 4, 'name' => 'Шатенка'],
            ['key' => 5, 'name' => 'Светло-коричневая'],
            ['key' => 6, 'name' => 'Седая'],
        ]);
    }
}
