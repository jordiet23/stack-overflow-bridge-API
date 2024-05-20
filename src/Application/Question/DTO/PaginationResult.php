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
        public readonly int $page,
        public readonly int $pagesize,
        public readonly array $items
    ) {}
}
