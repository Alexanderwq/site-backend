<?php

namespace App\Controller\Api\Auth;

use App\Model\User\UseCase\SignUp as SignUp;
use App\ReadModel\User\UserFetcher;
use App\Security\UserIdentity;
use DomainException;
use Lexik\Bundle\JWTAuthenticationBundle\Services\JWTTokenManagerInterface;
use Psr\Log\LoggerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Serializer\Exception\ExceptionInterface;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class SignUpController extends AbstractController
{
    public function __construct(
        private readonly LoggerInterface            $logger,
        private readonly UserFetcher                $userFetcher,
        private readonly ValidatorInterface         $validator,
        private readonly SerializerInterface $serializer,
        private readonly JWTTokenManagerInterface $jwtManager,
    )
    {
    }

    /**
     * @throws ExceptionInterface
     */
    #[Route('/api/sign-up', name: 'auth.signup', methods: ['POST'])]
    public function request(Request $request, SignUp\Request\Handler $handler): Response
    {
        $command = $this->serializer->deserialize($request->getContent(), SignUp\Request\Command::class, 'json');

        $errors = $this->validator->validate($command);

        if (count($errors)) {
            $json = $this->serializer->serialize($errors, 'json');
            return new JsonResponse($json, 400, [], true);
        }

        try {
            $handler->handle($command);

            $user = $this->userFetcher->findForAuth($command->email);
            $userIdentity = new UserIdentity(
                $user->id,
                $user->email,
                $user->password_hash,
                $user->name,
                $user->role,
                $user->status,
            );

            $token = $this->jwtManager->create($userIdentity);

            return $this->json([
                'token' => $token,
            ]);
        } catch (DomainException $exception) {
            $this->logger->error($exception->getMessage(), ['exception' => $exception]);
            return $this->json(['message' => $exception->getMessage()], 400);
        }
    }

    #[Route('/signup/{token}', name: 'auth.signup.confirm')]
    public function confirm(string $token, SignUp\Confirm\ByToken\Handler $handler): Response
    {
        $command = new SignUp\Confirm\ByToken\Command($token);

        try {
            $handler->handle($command);
            return $this->json([]);
        } catch (DomainException $exception) {
            $this->logger->error($exception->getMessage(), ['exception' => $exception]);
            return $this->json(['message' => $exception->getMessage()], 400);
        }
    }

    #[Route('/api/check-email', name: 'auth.check-email', methods: ['POST'])]
    public function checkEmail(Request $request): Response
    {
        $command = $this->serializer->deserialize($request->getContent(), SignUp\Request\Command::class, 'json');

        $errors = $this->validator->validateProperty($command, 'email');

        if (count($errors)) {
            $json = $this->serializer->serialize($errors, 'json');
            return new JsonResponse($json, 400, [], true);
        }

        return $this->json([]);
    }
}
