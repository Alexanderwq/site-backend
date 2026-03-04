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
use App\Tests\Builder\Account\MassageForm\MassageFormBuilder;
use DateTimeImmutable;
use DomainException;
use PHPUnit\Framework\TestCase;
final class ChangePriceTest extends TestCase
{
    public function testChangePrices(): void
    {
        $form = new MassageFormBuilder()->build();

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
        $form = new MassageFormBuilder()->build();

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
        $form = new MassageFormBuilder()->build();

        $form->changePrices([
            new PriceDto('home', 1, 3000, 'day'),
            new PriceDto('visitor', 2, 6000, 'night'),
        ]);

        self::assertCount(2, $form->getPrices());
    }

    public function testEmptyPricesThrowsException(): void
    {
        $form = new MassageFormBuilder()->build();

        $this->expectException(DomainException::class);
        $this->expectExceptionMessage('Должна быть указана цена');

        $form->changePrices([]);
    }
}
