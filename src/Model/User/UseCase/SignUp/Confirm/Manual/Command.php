<?php

namespace App\Model\User\UseCase\SignUp\Confirm\Manual;

class Command
{
    public function __construct(public string $id)
    {

    }
}