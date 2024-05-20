<?php

namespace App\Infrastructure\Client\Answer\DTO;

use App\Infrastructure\Client\Question\DTO\OwnerDTO;
use Symfony\Component\Serializer\Attribute\SerializedName;

class AnswerDTO
{
    public function __construct(
        public readonly OwnerDTO $owner,
        #[SerializedName('is_accepted')] public readonly bool $isAccepted,
        #[SerializedName('score')] public readonly int $score,
        #[SerializedName('last_activity_date')] public readonly int $lastActivityDate,
        #[SerializedName('creation_date')] public readonly int $creationDate,
        #[SerializedName('answer_id')] public readonly int $answerId,
        #[SerializedName('question_id')] public readonly int $questionId,
        #[SerializedName('content_license')] public readonly ?string $contentLicense,
        public readonly ?string $body,
    ) {}
}