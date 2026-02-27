<?php

namespace App\Tests\Unit\Model\Account\Entity\MassageForm;

use App\Model\Account\Entity\MassageForm\Description;
use App\Model\Account\Entity\MassageForm\Experience;
use App\Model\Account\Entity\MassageForm\Id;
use App\Model\Account\Entity\MassageForm\MassageForm;
use App\Model\Account\Entity\MassageForm\Name;
use App\Model\Account\Entity\MassageForm\Phone;
use App\Model\Account\Entity\MassageForm\UserId;
use App\Model\Account\UseCase\MassageForm\EditAdditionalInfo\PriceDto;
use DateTimeImmutable;
use DomainException;
use PHPUnit\Framework\TestCase;
final class ChangePriceTest extends TestCase
{
    public function testChangePrices(): void
    {
        $form = $this->createForm();

        $dtos = [
            new PriceDto('home', 2, 5000, 'day'),
        ];

        $form->changePrices($dtos);

        $prices = $form->getPrices();

        self::assertCount(1, $prices);
        self::assertSame('home', $prices[0]->getLocation()->getValue());
        self::assertSame(2, $prices[0]->getDuration()->getValue());
        self::assertSame(5000, $prices[0]->getAmount()->getValue());
    }

    public function testReplaceExistingPrices(): void
    {
        $form = $this->createForm();

        $form->changePrices([
            new PriceDto('home', 1, 3000, 'day'),
        ]);

        $form->changePrices([
            new PriceDto('visitor', 2, 6000, 'night'),
        ]);

        $prices = $form->getPrices();

        self::assertCount(1, $prices);
        self::assertSame('visitor', $prices[0]->getLocation()->getValue());
        self::assertSame(6000, $prices[0]->getAmount()->getValue());
    }

    public function testMultiplePrices(): void
    {
        $form = $this->createForm();

        $form->changePrices([
            new PriceDto('home', 1, 3000, 'day'),
            new PriceDto('visitor', 2, 6000, 'night'),
        ]);

        self::assertCount(2, $form->getPrices());
    }

    public function testEmptyPricesThrowsException(): void
    {
        $form = $this->createForm();

        $this->expectException(DomainException::class);
        $this->expectExceptionMessage('Должна быть указана цена');

        $form->changePrices([]);
    }

    private function createForm(): MassageForm
    {
        return new MassageForm(
            Id::next(),
            new UserId('user-id'),
            new Phone('79109688090'),
            new Name('Аня'),
            new Description('description'),
            new DateTimeImmutable('2000-01-01'),
            new Experience(5),
        );
    }
}
