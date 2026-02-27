<?php

namespace App\Model\User\UseCase\Email\Request;

use Symfony\Component\Validator\Constraints as Assert;

class Command
{
    #[Assert\NotBlank, Assert\Email]
    public string $email;

    public function __construct(
        #[Assert\NotBlank]
        public string $id,
    ) {

    }
}