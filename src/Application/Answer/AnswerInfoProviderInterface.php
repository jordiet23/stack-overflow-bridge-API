<?php

namespace App\Application\Answer;

interface AnswerInfoProviderInterface
{

    public function getAnswersByQuestionId(int $questionId): array;
}
