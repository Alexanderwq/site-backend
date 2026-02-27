<?php

namespace App\Model\User\Service;

use DateInterval;

class ResetTokenizerFactory
{
    public static function create(string $interval): ResetTokenizer
    {
        return new ResetTokenizer(new DateInterval($interval));
    }
}