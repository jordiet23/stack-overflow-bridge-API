<?php

namespace App\Infrastructure\Client\DTO;

use Symfony\Component\Serializer\Attribute\SerializedName;

class ItemDTO
{
    public function __construct(
        public array $tags,
        public OwnerDTO $owner,
        #[SerializedName('is_answered')] public bool $isAnswered,
        #[SerializedName('view_count')] public int $viewCount,
        #[SerializedName('accepted_answer_id')] public ?int $acceptedAnswerId = null,
        #[SerializedName('answer_count')] public int $answerCount,
        public int $score,
        #[SerializedName('last_activity_date')] public int $lastActivityDate,
        #[SerializedName('creation_date')] public int $creationDate,
        #[SerializedName('last_edit_date')] public ?int $lastEditDate = null,
        #[SerializedName('question_id')] public int $questionId,
        #[SerializedName('content_license')] public string $contentLicense,
        public string $link,
        public string $title
    ) {}
}