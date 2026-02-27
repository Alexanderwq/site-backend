<?php

namespace App\Controller\Api\Account;

use App\ReadModel\Account\District\DistrictFetcher;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Attribute\Route;

class DistrictController extends AbstractController
{
    #[Route('/api/district_list', name: 'account.district_list')]
    public function list(DistrictFetcher $fetcher): JsonResponse
    {
        return $this->json($fetcher->listAll());
    }
}
