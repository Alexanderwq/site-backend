<?php

namespace App\Controller\Api\MassageForm\Comment;

use App\Model\Account\Entity\MassageForm\MassageForm;
use App\Model\Comment\Entity\Comment\Comment;
use App\Model\Comment\UseCase;
use App\Security\Voter\CommentVoter;
use DomainException;
use Psr\Log\LoggerInterface;
use Symfony\Bridge\Doctrine\Attribute\MapEntity;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;

#[Route('/api/massage_forms')]
class CommentController extends AbstractController
{
    public function __construct(
        private readonly SerializerInterface $serializer,
        private readonly ValidatorInterface  $validator,
        private readonly LoggerInterface     $logger,
    )
    {
    }

    #[Route('/{id}/comments', name: 'comments.create', methods: ['POST'])]
    public function create(MassageForm $massageForm, Request $request, UseCase\Comment\Create\Handler $handler): JsonResponse
    {
        /** @var UseCase\Comment\Create\Command $command */
        $command = $this->serializer->deserialize($request->getContent(), UseCase\Comment\Create\Command::class, 'json');

        $command->author = $this->getUser()->getId();
        $command->entityId = $massageForm->getId()->getValue();
        $command->entityType = MassageForm::class;

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

    #[Route('/{massageFormId}/comments/{commentId}', name: 'comments.delete', methods: ['DELETE'])]
    public function remove(#[MapEntity(id: 'commentId')] Comment $comment, UseCase\Comment\Remove\Handler $handler): JsonResponse
    {
        $this->denyAccessUnlessGranted(CommentVoter::DELETE, $comment);

        $command = new UseCase\Comment\Remove\Command();
        $command->id = $comment->getId()->getValue();

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
