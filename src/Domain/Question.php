<?php

namespace App\Domain;

class Question
{
    public function __construct(
        public int $id,
        public string $title,
        public Owner $owner,
        public int $viewCount,
        public int $answerCount,
        public int $score,
        public \DateTime $creationDate
    ) {}
}