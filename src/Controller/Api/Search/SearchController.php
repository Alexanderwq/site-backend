<?php

namespace App\Controller\Api\Search;

use App\ReadModel\Search\Filter;
use App\ReadModel\Search\SearchFetcher;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Serializer\SerializerInterface;

class SearchController extends AbstractController
{
    public function __construct(
        private readonly SerializerInterface $serializer,
    ) {}

    #[Route('/api/search', name: 'massage_forms.search', methods: ['POST'])]
    public function index(SearchFetcher $fetcher, Request $request): JsonResponse
    {
        $page = $request->query->getInt('page', 1);
        $filter = $this->serializer->deserialize($request->getContent(), Filter::class, 'json');

        return $this->json($fetcher->fetch($page, $filter));
    }
}
