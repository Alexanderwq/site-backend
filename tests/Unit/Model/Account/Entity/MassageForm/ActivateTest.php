<?php

namespace App\Tests\Unit\Model\Account\Entity\MassageForm;

use App\Tests\Builder\Account\MassageForm\MassageFormBuilder;
use PHPUnit\Framework\TestCase;

class ActivateTest extends TestCase
{
    public function testSuccess(): void
    {
        $form = new MassageFormBuilder()
            ->withPhotos()
            ->withPrices()
            ->build();

        $form->deactivate();

        $form->activate();

        self::assertTrue($form->isActive());
    }

    public function testFailed(): void
    {
        $form = new MassageFormBuilder()
            ->withPhotos()
            ->withPrices()
            ->build();

        self::expectExceptionMessage('Massage form is already activated');
        $form->activate();
    }

    public function testIsNotCompleted()
    {
        $form = new MassageFormBuilder()->build();

        self::expectExceptionMessage('Massage form is not complete');
        $form->activate();
    }
}
