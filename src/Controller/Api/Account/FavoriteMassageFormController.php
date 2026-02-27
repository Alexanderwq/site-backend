<?php

namespace App\Controller\Api\Account;

use App\Model\Account\UseCase\FavoriteProfile;
use DomainException;
use Psr\Log\LoggerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class FavoriteMassageFormController extends AbstractController
{
    public function __construct(
        private readonly LoggerInterface     $logger,
        private readonly SerializerInterface $serializer,
        private readonly ValidatorInterface  $validator,
    ) {
    }

    #[Route(path: '/api/lk/favorites/profiles/categories', name: 'favorite_profiles.add', methods: ['POST'])]
    public function add(Request $request, FavoriteProfile\Add\Handler $handler): JsonResponse
    {
        /** @var FavoriteProfile\Add\Command $command */
        $command = $this->serializer->deserialize($request->getContent(), FavoriteProfile\Add\Command::class, 'json');

        $command->user = $this->getUser()->getId();

        $errors = $this->validator->validate($command);

        if (count($errors)) {
            $json = $this->serializer->serialize($errors, 'json');
            return new JsonResponse($json, 400, [], true);
        }

        try {
            $handler->handle($command);
        } catch (DomainException $exception) {
            $this->logger->error($exception->getMessage(), ['exception' => $exception]);
            return $this->json(['message' => $exception->getMessage()], 400);
        }

        return $this->json(['success' => true]);
    }

    #[Route(path: '/api/lk/favorites/profiles/categories', name: 'favorite_profiles.remove', methods: ['DELETE'])]
    public function remove(Request $request, FavoriteProfile\Remove\Handler $handler): JsonResponse
    {
        /** @var FavoriteProfile\Remove\Command $command */
        $command = $this->serializer->deserialize($request->getContent(), FavoriteProfile\Remove\Command::class, 'json');

        $command->user = $this->getUser()->getId();

        $errors = $this->validator->validate($command);

        if (count($errors)) {
            $json = $this->serializer->serialize($errors, 'json');
            return new JsonResponse($json, 400, [], true);
        }

        try {
            $handler->handle($command);
        } catch (DomainException $exception) {
            $this->logger->error($exception->getMessage(), ['exception' => $exception]);
            return $this->json(['message' => $exception->getMessage()], 400);
        }

        return $this->json(['success' => true]);
    }
}
