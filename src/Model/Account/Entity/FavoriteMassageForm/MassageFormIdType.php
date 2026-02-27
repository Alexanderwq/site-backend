<?php

namespace App\Model\Account\Entity\FavoriteMassageForm;

use Doctrine\DBAL\Platforms\AbstractPlatform;
use Doctrine\DBAL\Types\GuidType;

class MassageFormIdType extends GuidType
{
    public const string NAME = 'account_favorite_profile_profile_id';

    public function convertToDatabaseValue($value, AbstractPlatform $platform): mixed
    {
        return $value instanceof MassageFormId ? $value->getValue() : $value;
    }

    public function convertToPHPValue($value, AbstractPlatform $platform): ?MassageFormId
    {
        return !empty($value) ? new MassageFormId($value) : null;
    }

    public function getName(): string
    {
        return self::NAME;
    }
}
