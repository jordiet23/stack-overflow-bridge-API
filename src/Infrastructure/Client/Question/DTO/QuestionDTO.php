<?php

namespace App\Infrastructure\Client\Question\DTO;

use Symfony\Component\Serializer\Attribute\SerializedName;

class QuestionDTO
{
    public function __construct(
        public readonly array $tags,
        public readonly ?OwnerDTO $owner,
        #[SerializedName('is_answered')] public readonly bool $isAnswered,
        #[SerializedName('view_count')] public readonly int $viewCount,
        #[SerializedName('accepted_answer_id')] public readonly ?int $acceptedAnswerId,
        #[SerializedName('answer_count')] public readonly int $answerCount,
        public readonly int $score,
        #[SerializedName('last_activity_date')] public readonly int $lastActivityDate,
        #[SerializedName('creation_date')] public readonly int $creationDate,
        #[SerializedName('last_edit_date')] public readonly ?int $lastEditDate,
        #[SerializedName('question_id')] public readonly int $questionId,
        #[SerializedName('content_license')] public readonly ?string $contentLicense,
        public readonly string $link,
        public readonly string $title,
        public readonly ?string $body
    ) {}
}