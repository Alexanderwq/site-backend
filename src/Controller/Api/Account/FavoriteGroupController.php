<?php

namespace App\Controller\Api\Account;

use App\Model\Account\UseCase\FavoriteGroup;
use DomainException;
use Psr\Log\LoggerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class FavoriteGroupController extends AbstractController
{
    public function __construct(
        private readonly LoggerInterface     $logger,
        private readonly SerializerInterface $serializer,
        private readonly ValidatorInterface  $validator,
    ) {
    }

    #[Route(path: '/api/lk/favorites/group', name: 'favorite_groups.create', methods: ['POST'])]
    public function add(Request $request, FavoriteGroup\Create\Handler $handler): JsonResponse
    {
        /** @var FavoriteGroup\Create\Command $command */
        $command = $this->serializer->deserialize($request->getContent(), FavoriteGroup\Create\Command::class, 'json');

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

    #[Route(path: '/api/lk/favorites/group', name: 'favorite_groups.remove', methods: ['DELETE'])]
    public function remove(Request $request, FavoriteGroup\Remove\Handler $handler): JsonResponse
    {
        /** @var FavoriteGroup\Remove\Command $command */
        $command = $this->serializer->deserialize($request->getContent(), FavoriteGroup\Remove\Command::class, 'json');

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
