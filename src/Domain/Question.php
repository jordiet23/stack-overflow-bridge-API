<?php

namespace App\Domain;

class Question
{
    public function __construct(
        public readonly int $id,
        public readonly string $title,
        public readonly Owner $owner,
        public readonly int $viewCount,
        public readonly int $answerCount,
        public readonly int $score,
        public readonly string $link,
        public readonly \DateTime $creationDate,
        public readonly ?string $body
    ) {}
}