<?php

namespace App\Model\Account\UseCase\MassageForm\Activate;

use Symfony\Component\Validator\Constraints as Assert;

class Command
{
    #[Assert\NotBlank]
    public string $massageForm;
}
