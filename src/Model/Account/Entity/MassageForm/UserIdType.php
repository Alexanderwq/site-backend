<?php

namespace App\Model\Account\Entity\MassageForm;

use Doctrine\DBAL\Platforms\AbstractPlatform;
use Doctrine\DBAL\Types\GuidType;

class UserIdType extends GuidType
{
    public const string NAME = 'account_massage_form_user_id';

    public function convertToDatabaseValue($value, AbstractPlatform $platform): mixed
    {
        return $value instanceof UserId ? $value->getValue() : $value;
    }

    public function convertToPHPValue($value, AbstractPlatform $platform): ?UserId
    {
        return !empty($value) ? new UserId($value) : null;
    }

    public function getName(): string
    {
        return self::NAME;
    }
}
