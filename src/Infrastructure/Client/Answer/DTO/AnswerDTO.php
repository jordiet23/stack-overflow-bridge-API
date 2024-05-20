<?php

namespace App\Infrastructure\Client\Answer\DTO;

use App\Infrastructure\Client\Question\DTO\OwnerDTO;
use Symfony\Component\Serializer\Attribute\SerializedName;

class AnswerDTO
{
    public function __construct(
        public OwnerDTO $owner,
        #[SerializedName('is_accepted')] public bool $isAccepted,
        #[SerializedName('score')] public int $score,
        #[SerializedName('last_activity_date')] public int $lastActivityDate,
        #[SerializedName('creation_date')] public int $creationDate,
        #[SerializedName('answer_id')] public int $answerId,
        #[SerializedName('question_id')] public int $questionId,
        #[SerializedName('content_license')] public ?string $contentLicense,
        public ?string $body,
    ) {}
}