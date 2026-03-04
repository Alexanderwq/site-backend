<?php

namespace App\Model\Account\UseCase\MassageForm\Deactivate;

use Symfony\Component\Validator\Constraints as Assert;

class Command
{
    #[Assert\NotBlank]
    public string $massageForm;
}
