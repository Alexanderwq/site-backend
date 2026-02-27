<?php

namespace App\Service\WatermarkService;

enum ImageTypes: string
{
    case png = 'image/png';

    case jpg = 'image/jpg';

    case jpeg = 'image/jpeg';
}
