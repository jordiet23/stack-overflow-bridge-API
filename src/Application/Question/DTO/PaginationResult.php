<?php

namespace App\Application\Question\DTO;

/**
 * @template T
 */
class PaginationResult
{
    /**
     * @param T[] $items
     */
    public function __construct(
        public int $page,
        public int $pagesize,
        public array $items
    ) {}
}
