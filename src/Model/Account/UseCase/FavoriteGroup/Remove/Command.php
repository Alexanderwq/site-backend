<?php

namespace App\Model\Account\UseCase\FavoriteGroup\Remove;

use Symfony\Component\Validator\Constraints as Assert;

class Command
{
    #[Assert\NotBlank, Assert\Type('string')]
    public string $group;

    #[Assert\NotBlank, Assert\Type('string')]
    public string $user;
}

