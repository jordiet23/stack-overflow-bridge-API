<?php

namespace App\Domain;

class Answer
{
    public function __construct(
        public int $id,
        public int $questionId,
        public Owner $owner,
        public bool $isAccepted,
        public \DateTime $creationDate
    ) {}
}