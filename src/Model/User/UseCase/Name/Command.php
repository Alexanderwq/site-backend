<?php

namespace App\Model\User\UseCase\Name;

use Symfony\Component\Validator\Constraints as Assert;

class Command
{
    #[Assert\NotBlank]
    public string $name;

    public function __construct(
        #[Assert\NotBlank]
        public readonly string $id,
    ) {

    }
}
