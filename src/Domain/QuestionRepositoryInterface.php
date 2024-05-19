<?php

namespace App\Domain;

use App\Application\Question\DTO\QuestionsPaginationParams;
use App\Infrastructure\Client\Question\DTO\QuestionsPaginateResponseDTO;
use Symfony\Contracts\HttpClient\Exception\ClientExceptionInterface;

interface QuestionRepositoryInterface
{

    /**
     * @throws ClientExceptionInterface
     */
    public function paginate(QuestionsPaginationParams $params): QuestionsPaginateResponseDTO;
}
