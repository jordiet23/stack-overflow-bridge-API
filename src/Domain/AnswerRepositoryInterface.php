<?php

namespace App\Domain;

use App\Infrastructure\Client\Answer\DTO\AnswerByQuestionResponseDTO;

interface AnswerRepositoryInterface
{

    public function getAnswersByQuestionId(int $questionId): AnswerByQuestionResponseDTO;
}
