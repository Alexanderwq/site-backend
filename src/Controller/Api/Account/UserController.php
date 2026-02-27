<?php

namespace App\Controller\Api\Account;

use App\ReadModel\User\UserFetcher;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Attribute\Route;

class UserController extends AbstractController
{
    #[Route(path: '/api/lk/user_info', name: 'user_info')]
    public function info(UserFetcher $userFetcher): JsonResponse
    {
        $userEmail = $this->getUser()->getUserIdentifier();

        $user = $userFetcher->getUserInfoForAccount($userEmail);

        return $this->json(
            [
                'name' => $user->name,
                'email' => $user->email,
                'avatar' => null,
                'verified_email' => $user->status === 'active',
            ]
        );
    }
}
