<?php

namespace App\Domain;

use App\Infrastructure\Client\Answer\DTO\AnswerByQuestionResponseDTO;
use Symfony\Contracts\HttpClient\Exception\ClientExceptionInterface;

interface AnswerRepositoryInterface
{

    /**
     * @throws ClientExceptionInterface
     */
    public function getAnswersByQuestionId(int $questionId): AnswerByQuestionResponseDTO;
}
