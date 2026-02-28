<?php

namespace App\Model\Account\UseCase\FavoriteGroup\Create;

use Symfony\Component\Validator\Constraints as Assert;

class Command
{
    #[Assert\NotBlank, Assert\Type('string')]
    public string $name;

    #[Assert\NotBlank, Assert\Type('string')]
    public string $user;
}

