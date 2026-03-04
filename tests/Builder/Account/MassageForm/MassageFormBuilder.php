<?php

namespace App\Tests\Builder\Account\MassageForm;

use App\Model\Account\Entity\MassageForm\Description;
use App\Model\Account\Entity\MassageForm\Experience;
use App\Model\Account\Entity\MassageForm\Id;
use App\Model\Account\Entity\MassageForm\MassageForm;
use App\Model\Account\Entity\MassageForm\Name;
use App\Model\Account\Entity\MassageForm\Phone;
use App\Model\Account\Entity\MassageForm\UserId;
use DateTimeImmutable;

class MassageFormBuilder
{
    private $id;

    public function __construct()
    {
        $this->id = Id::next();
    }

    public function build(): MassageForm
    {
        return new MassageForm(
            $this->id,
            new UserId('user-id'),
            new Phone('79109688090'),
            new Name('Аня'),
            new Description('description'),
            new DateTimeImmutable('2000-01-01'),
            new Experience(5),
            new DateTimeImmutable(),
        );
    }
}

