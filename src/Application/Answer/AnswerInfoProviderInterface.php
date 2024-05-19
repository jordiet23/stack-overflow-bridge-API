<?php

namespace App\Application\Answer;

use Symfony\Contracts\HttpClient\Exception\ClientExceptionInterface;

interface AnswerInfoProviderInterface
{

    /**
     * @throws ClientExceptionInterface
     */
    public function getAnswersByQuestionId(int $questionId): array;
}
