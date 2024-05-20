<?php

namespace App\Domain;

class Answer
{
    public function __construct(
        public readonly int $id,
        public readonly int $questionId,
        public readonly Owner $owner,
        public readonly bool $isAccepted,
        public readonly \DateTime $creationDate,
        public readonly ?string $body
    ) {}
}