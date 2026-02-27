<?php

namespace App\Model\Account\UseCase\FavoriteProfile\Remove;

use Symfony\Component\Validator\Constraints as Assert;

class Command
{
    #[Assert\NotBlank, Assert\Type('string')]
    public string $massageForm;

    #[Assert\NotBlank, Assert\Type('string')]
    public string $user;
}
