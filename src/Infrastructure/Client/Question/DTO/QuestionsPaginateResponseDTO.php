<?php

namespace App\Infrastructure\Client\Question\DTO;

use Symfony\Component\Serializer\Attribute\SerializedName;

class QuestionsPaginateResponseDTO
{
    public function __construct(
        public array $items,
        #[SerializedName('has_more')] public bool $hasMore,
        #[SerializedName('quota_max')] public int $quotaMax,
        #[SerializedName('quota_remaining')] public int $quotaRemaining
    ) {}

    public function getItem(int $index = 0): ?QuestionDTO
    {
        return $this->items[$index] ?? null;
    }
}