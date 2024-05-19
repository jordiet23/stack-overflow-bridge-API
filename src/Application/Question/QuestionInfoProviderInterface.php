<?php

namespace App\Application\Question;

use App\Application\Question\DTO\PaginationResult;
use App\Application\Question\DTO\QuestionsPaginationParams;
use Symfony\Contracts\HttpClient\Exception\ClientExceptionInterface;

interface QuestionInfoProviderInterface
{

    /**
     * @throws ClientExceptionInterface
     */
    public function paginate(QuestionsPaginationParams $params): PaginationResult;
}
