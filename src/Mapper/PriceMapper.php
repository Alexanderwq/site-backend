<?php

namespace App\Mapper;

use App\Model\Account\UseCase\MassageForm\EditAdditionalInfo\PriceDto;

class PriceMapper
{
    public function map(array $prices): array
    {
        $result = [];
        foreach ($prices as $place => $value) {
            foreach ($value as $day => $price) {
                foreach ($price as $hours => $priceItem) {
                    if ($priceItem) {
                        $time = $hours === 'oneHour' ? 1 : 2;
                        $place = $place === 'home' ? 'home' : 'visitor';
                        $amount = (int) $priceItem;
                        $dayTime = $day === 'day' ? 'day' : 'night';
                        $priceDto = new PriceDto(
                            $place,
                            $time,
                            $amount,
                            $dayTime,
                        );

                        $result[] = $priceDto;
                    }
                }
            }
        }

        return $result;
    }
}
