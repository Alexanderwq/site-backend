<?php

namespace App\Controller\Api\Account;

use App\ReadModel\Account\MetroStation\MetroStationFetcher;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Attribute\Route;

class MetroStationsController extends AbstractController
{
    #[Route(path: '/api/metro_list', name: 'metro_stations.list')]
    public function metroStationsList(MetroStationFetcher $fetcher): JsonResponse
    {
        return $this->json($fetcher->getAll());
    }
}
