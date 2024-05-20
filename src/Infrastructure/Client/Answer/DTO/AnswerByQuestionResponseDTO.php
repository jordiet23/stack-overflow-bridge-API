<?php

namespace App\Infrastructure\Client\Answer\DTO;

use App\Infrastructure\Client\Question\DTO\QuestionDTO;
use Symfony\Component\Serializer\Attribute\SerializedName;

class AnswerByQuestionResponseDTO
{
    public function __construct(
        public readonly array $items,
        public readonly bool $hasMore,
        public readonly int $quotaMax,
        public readonly int $quotaRemaining
    )
    {
    }

}