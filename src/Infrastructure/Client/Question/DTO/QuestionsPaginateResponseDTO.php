<?php

namespace App\Infrastructure\Client\Question\DTO;

class QuestionsPaginateResponseDTO
{
    public function __construct(
        public readonly array $items,
        public readonly bool $hasMore,
        public readonly int $quotaMax,
        public readonly int $quotaRemaining
    ) {}
}