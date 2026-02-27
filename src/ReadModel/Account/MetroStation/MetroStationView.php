<?php

namespace App\ReadModel\Account\MetroStation;

class MetroStationView
{
    public function __construct(
        public int $id,
        public string $name,
        public string $descriptor,
        public ?string $color,
    ) {

    }
}
