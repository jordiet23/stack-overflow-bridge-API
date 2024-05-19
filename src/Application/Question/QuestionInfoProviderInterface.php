<?php

namespace App\Application\Question;

use App\Application\Question\DTO\PaginationResult;
use App\Application\Question\DTO\QuestionsPaginationParams;

interface QuestionInfoProviderInterface
{

    public function paginate(QuestionsPaginationParams $params): PaginationResult;
}
