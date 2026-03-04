<?php

namespace App\Tests\Unit\Model\Account\Entity\MassageForm;

use App\Model\Account\Entity\MassageForm\Description;
use App\Model\Account\Entity\MassageForm\Experience;
use App\Model\Account\Entity\MassageForm\Id;
use App\Model\Account\Entity\MassageForm\MassageForm;
use App\Model\Account\Entity\MassageForm\Name;
use App\Model\Account\Entity\MassageForm\Phone;
use App\Model\Account\Entity\MassageForm\UserId;
use App\Tests\Builder\Account\MassageForm\MassageFormBuilder;
use DateTimeImmutable;
use PHPUnit\Framework\TestCase;
use App\Model\Account\Entity\AdditionalOption\AdditionalOption as AdditionalOptionRoot;

final class ChangeAdditionalOptionsTest extends TestCase
{
    public function testAddAdditionalOptions(): void
    {
        $form = new MassageFormBuilder()->build();

        $option = $this->createOption();

        $form->changeAdditionalOptions([
            [
                'option' => $option,
                'price' => 2000,
                'description' => 'test desc',
            ],
        ]);

        $options = $form->getAdditionalOptions();

        self::assertCount(1, $options);
        self::assertSame(2000, $options[0]->getPrice()->getValue());
        self::assertSame('test desc', $options[0]->getDescription()->getValue());
    }

    public function testReplaceExistingOptions(): void
    {
        $form = new MassageFormBuilder()->build();

        $option = $this->createOption();

        $form->changeAdditionalOptions([
            [
                'option' => $option,
                'price' => 1000,
                'description' => 'old',
            ],
        ]);

        $form->changeAdditionalOptions([
            [
                'option' => $option,
                'price' => 3000,
                'description' => 'new',
            ],
        ]);

        $options = $form->getAdditionalOptions();

        self::assertCount(1, $options);
        self::assertSame(3000, $options[0]->getPrice()->getValue());
        self::assertSame('new', $options[0]->getDescription()->getValue());
    }

    public function testMultipleOptions(): void
    {
        $form = new MassageFormBuilder()->build();

        $option1 = $this->createOption();
        $option2 = $this->createOption();

        $form->changeAdditionalOptions([
            ['option' => $option1, 'price' => 1000, 'description' => 'a'],
            ['option' => $option2, 'price' => 2000, 'description' => 'b'],
        ]);

        self::assertCount(2, $form->getAdditionalOptions());
    }

    public function testEmptyOptions(): void
    {
        $form = new MassageFormBuilder()->build();

        $form->changeAdditionalOptions([]);

        self::assertCount(0, $form->getAdditionalOptions());
    }

    private function createOption(): AdditionalOptionRoot
    {
        return $this->createMock(AdditionalOptionRoot::class);
    }
}

