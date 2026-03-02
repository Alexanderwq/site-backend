<?php

namespace App\Controller\Api\Catalog;

class MassageFormCardView
{
    public function __construct(
        public string $id,
        public string $name,
        public ?int $priceOneHour,
        public string $metroStations,
        public array $photos = [],
    ) {

    }
}
