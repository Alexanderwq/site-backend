<?php

namespace App\Service\Paginator;

use Knp\Component\Pager\PaginatorInterface;

readonly class Paginator
{
    public function __construct(private PaginatorInterface $paginator) {}

    public function paginate($query, int $page = 1, int $limit = 10): PaginationResult
    {
        $pagination = $this->paginator->paginate($query, $page, $limit);

        $currentPage = $pagination->getCurrentPageNumber();
        $totalPages  = $pagination->getPageCount();
        $perPage     = $pagination->getItemNumberPerPage();
        $totalItems  = $pagination->getTotalItemCount();

        return new PaginationResult(
            items: iterator_to_array($pagination->getItems()),
            currentPage: $currentPage,
            perPage: $perPage,
            totalItems: $totalItems,
            totalPages: $totalPages,
            nextPage: $currentPage < $totalPages ? $currentPage + 1 : null,
            prevPage: $currentPage > 1 ? $currentPage - 1 : null,
        );
    }
}
