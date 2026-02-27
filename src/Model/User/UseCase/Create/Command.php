<?php

namespace App\Model\User\UseCase\Create;

use Symfony\Component\Validator\Constraints as Assert;

class Command
{
    #[Assert\Email, Assert\NotBlank]
    public $email;

    #[Assert\NotBlank]
    public $name;
}
