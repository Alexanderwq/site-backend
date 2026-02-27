<?php

namespace App\Model\User\UseCase\Block;

use Symfony\Component\Validator\Constraints as Assert;

class Command
{
    public function __construct(
        #[Assert\NotBlank]
        public readonly string $id,
    ) {

    }
}