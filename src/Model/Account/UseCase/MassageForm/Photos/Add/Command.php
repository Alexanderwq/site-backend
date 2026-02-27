<?php

namespace App\Model\Account\UseCase\MassageForm\Photos\Add;

use Symfony\Component\Validator\Constraints as Assert;

class Command
{
    #[Assert\NotBlank]
    public string $massageForm;

    /**
     * @var PhotoDto[]
     */
    #[Assert\NotBlank]
    public array $photos;

    public array $removePhotos;
}
