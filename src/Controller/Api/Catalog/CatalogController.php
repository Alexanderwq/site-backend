<?php

namespace App\Controller\Api\Catalog;

use App\ReadModel\Catalog\CatalogFetcher;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Attribute\Route;

class CatalogController extends AbstractController
{
    #[Route(path: '/api/catalog/feed', name: 'catalog.feed', methods: ['GET'])]
    public function feed(CatalogFetcher $fetcher): JsonResponse
    {
        return $this->json($fetcher->fetchFeed());
    }

    #[Route(path: '/api/catalog/new', name: 'catalog.new', methods: ['GET'])]
    public function new(CatalogFetcher $fetcher): JsonResponse
    {
        return $this->json($fetcher->fetchNewMassageForms());
    }
}
