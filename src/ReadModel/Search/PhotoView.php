<?php

namespace App\ReadModel\Search;

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
