<?php

namespace App\Domain;

use App\Application\Question\DTO\QuestionsPaginationParams;
use App\Infrastructure\Client\DTO\ResponseDTO;

interface QuestionRepositoryInterface
{

    public function paginate(QuestionsPaginationParams $params): ResponseDTO;
}
