<?php

namespace App\Model\Account\UseCase\MassageForm\Create;

use Symfony\Component\Validator\Constraints as Assert;

class Command
{
    #[Assert\NotBlank]
    public string $id;

    #[Assert\NotBlank]
    public string $userId;

    #[Assert\NotBlank]
    public string $phone;

    #[Assert\NotBlank]
    public string $name;

    #[Assert\NotBlank]
    public string $description;

    #[Assert\NotBlank, Assert\DateTime('Y.m.d')]
    public string $dateOfBirth;

    #[Assert\NotBlank]
    public int $experience;

    public ?float $lat = null;

    public ?float $long = null;

    public array $metroList = [];

    public array $districtList = [];
}
