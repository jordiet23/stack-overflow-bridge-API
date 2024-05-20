<?php

namespace App\Infrastructure\Client\Answer\DTO;

use App\Infrastructure\Client\Question\DTO\QuestionDTO;
use Symfony\Component\Serializer\Attribute\SerializedName;

class AnswerByQuestionResponseDTO
{
    public function __construct(
        private array $items,
        #[SerializedName('has_more')] private bool $hasMore,
        #[SerializedName('quota_max')] private int $quotaMax,
        #[SerializedName('quota_remaining')] private int $quotaRemaining
    ) {}

    public function getItems(): array
    {
        return $this->items;
    }

    public function setItems(array $items): void
    {
        $this->items = $items;
    }

    public function isHasMore(): bool
    {
        return $this->hasMore;
    }

    public function setHasMore(bool $hasMore): void
    {
        $this->hasMore = $hasMore;
    }

    public function getQuotaMax(): int
    {
        return $this->quotaMax;
    }

    public function setQuotaMax(int $quotaMax): void
    {
        $this->quotaMax = $quotaMax;
    }

    public function getQuotaRemaining(): int
    {
        return $this->quotaRemaining;
    }

    public function setQuotaRemaining(int $quotaRemaining): void
    {
        $this->quotaRemaining = $quotaRemaining;
    }
}