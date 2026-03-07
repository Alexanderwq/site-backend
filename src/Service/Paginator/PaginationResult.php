<?php

namespace App\Service\Paginator;

class PaginationResult
{
    public function __construct(
        public array $items,
        public int $currentPage,
        public int $perPage,
        public int $totalItems,
        public int $totalPages,
        public ?int $nextPage,
        public ?int $prevPage,
    ) {}
}
