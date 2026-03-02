<?php

namespace App\Controller\Api\Catalog;

class PhotoView
{
    public function __construct(
        public string $id,
        public string $name,
        public string $massageFormId,
        public bool $isMain,
        public bool $isPreview,
    ) {}
}
