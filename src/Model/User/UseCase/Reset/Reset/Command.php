<?php

namespace App\Model\User\UseCase\Reset\Reset;

class Command
{
    public string $password;

    public function __construct(public string $token)
    {

    }
}