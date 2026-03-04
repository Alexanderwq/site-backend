<?php

namespace App\Tests\Builder\Account\MassageForm;

use App\Model\Account\Entity\MassageForm\Description;
use App\Model\Account\Entity\MassageForm\Experience;
use App\Model\Account\Entity\MassageForm\Id;
use App\Model\Account\Entity\MassageForm\MassageForm;
use App\Model\Account\Entity\MassageForm\Name;
use App\Model\Account\Entity\MassageForm\Phone;
use App\Model\Account\Entity\MassageForm\UserId;
use App\Model\Account\UseCase\MassageForm\EditAdditionalInfo\PriceDto;
use App\Model\Account\UseCase\MassageForm\Photos\Add\PhotoDto;
use DateTimeImmutable;

class MassageFormBuilder
{
    private MassageForm $massageForm;

    public function __construct()
    {
        $this->massageForm = new MassageForm(
            Id::next(),
            new UserId('user-id'),
            new Phone('79109688090'),
            new Name('Аня'),
            new Description('description'),
            new DateTimeImmutable('2000-01-01'),
            new Experience(5),
            new DateTimeImmutable(),
        );
    }

    public function build(): MassageForm
    {
        return $this->massageForm;
    }

    public function withPrices(): self
    {
        $this->massageForm->changePrices([
            new PriceDto('home', 1, 2000, 'day'),
            new PriceDto('visitor', 2, 3000, 'night'),
        ]);

        return $this;
    }

    public function withPhotos(): self
    {
        $photos = [
            new PhotoDto('photo3.png', false, false),
            new PhotoDto('photo1.png', false, false),
            new PhotoDto('photo2.png', false, false),
            new PhotoDto('main.jpg', true, false),
            new PhotoDto('preview.jpg', false, true),
        ];

        $this->massageForm->changePhotos($photos);

        return $this;
    }
}

