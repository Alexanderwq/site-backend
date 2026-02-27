<?php

namespace App\Model\Account\UseCase\MassageForm\EditAdditionalInfo;

use Symfony\Component\Validator\Constraints as Assert;

class Command
{
    #[Assert\NotBlank]
    public string $massageForm;

    /**
     * @var PriceDto[]
     */
    #[Assert\NotBlank]
    public array $prices;

    /**
     * @var OptionDto[]
     */
    public array $options;
}
