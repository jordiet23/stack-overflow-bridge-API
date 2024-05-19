<?php

namespace App\Application\Question;

use App\Application\Question\DTO\PaginationResult;
use App\Application\Question\DTO\QuestionsPaginationParams;
use App\Domain\Owner;
use App\Domain\Question;
use App\Domain\QuestionRepositoryInterface;
use App\Infrastructure\Client\DTO\ItemDTO;
use App\Infrastructure\Client\DTO\ResponseDTO;

class QuestionInfoProvider implements QuestionInfoProviderInterface
{
    public function __construct(
        private readonly QuestionRepositoryInterface $questionsClient
    )
    {
    }

    public function paginate(QuestionsPaginationParams $params): PaginationResult
    {
        return $this->parseResult($this->questionsClient->paginate($params), $params->pagesize, $params->page);
    }

    private function parseResult(ResponseDTO $responseDTO, int $perPage, int $page): PaginationResult
    {
        return new PaginationResult(
            page: $page,
            perPage: $perPage,
            items: array_map(function (ItemDTO $item) {
                return new Question(
                    id: $item->questionId,
                    title: $item->title,
                    owner: new Owner(id: $item->owner->userId, displayName: $item->owner->displayName, profileLink: $item->owner->profileImage),
                    viewCount: $item->viewCount,
                    answerCount: $item->answerCount,
                    score: $item->score,
                    creationDate: new \DateTime('@' . $item->creationDate)
                );
            }, $responseDTO->items)
        );
    }
}