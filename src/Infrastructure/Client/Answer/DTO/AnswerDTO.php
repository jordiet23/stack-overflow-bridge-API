<?php

namespace App\Infrastructure\Client\Answer\DTO;

use App\Infrastructure\Client\Question\DTO\OwnerDTO;
use Symfony\Component\Serializer\Attribute\SerializedName;

class AnswerDTO
{
    public function __construct(
        public readonly OwnerDTO $owner,
        public readonly bool $isAccepted,
        public readonly int $score,
        public readonly int $lastActivityDate,
        public readonly int $creationDate,
        public readonly int $answerId,
        public readonly int $questionId,
        public readonly ?string $contentLicense,
        public readonly ?string $body,
    ) {}
}