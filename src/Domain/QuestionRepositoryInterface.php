<?php

namespace App\Domain;

use App\Application\Question\DTO\QuestionsPaginationParams;
use App\Infrastructure\Client\Question\DTO\QuestionsPaginateResponseDTO;

interface QuestionRepositoryInterface
{

    public function paginate(QuestionsPaginationParams $params): QuestionsPaginateResponseDTO;
}
