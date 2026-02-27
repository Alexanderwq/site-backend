<?php

namespace App\Controller\Api\Auth;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;

class LoginController extends AbstractController
{
    public function login(Request $request): JsonResponse
    {
        return new JsonResponse(['ok' => true]);
    }
}
