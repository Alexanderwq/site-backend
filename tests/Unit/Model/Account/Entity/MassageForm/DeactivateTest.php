<?php

namespace App\Tests\Unit\Model\Account\Entity\MassageForm;

use App\Tests\Builder\Account\MassageForm\MassageFormBuilder;
use PHPUnit\Framework\TestCase;

class DeactivateTest extends TestCase
{
    public function testSuccess(): void
    {
        $form = new MassageFormBuilder()->build();

        $form->deactivate();

        self::assertTrue($form->isDeactivated());
    }

    public function testFailed(): void
    {
        $form = new MassageFormBuilder()->build();

        $form->deactivate();

        self::expectExceptionMessage('Massage form is already deactivated');
        $form->deactivate();
    }
}
