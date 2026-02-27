<?php

namespace App\Model\User\UseCase\SignUp\Confirm\ByToken;

class Command
{
    public function __construct(public string $token)
    {

    }
}