<?php

namespace App\Application\Question;

use App\Application\Question\DTO\PaginationResult;
use App\Application\Question\DTO\QuestionsPaginationParams;
use App\Domain\Owner;
use App\Domain\Question;
use App\Domain\QuestionRepositoryInterface;
use App\Infrastructure\Client\Question\DTO\QuestionDTO;
use App\Infrastructure\Client\Question\DTO\QuestionsPaginateResponseDTO;
use Symfony\Contracts\HttpClient\Exception\ClientExceptionInterface;

class QuestionInfoProvider implements QuestionInfoProviderInterface
{
    public function __construct(
        private readonly QuestionRepositoryInterface $questionsClient
    )
    {
    }

    /**
     * @throws ClientExceptionInterface
     */
    public function paginate(QuestionsPaginationParams $params): PaginationResult
    {
        return $this->parseResult($this->questionsClient->paginate($params), $params->pagesize, $params->page);
    }

    private function parseResult(QuestionsPaginateResponseDTO $responseDTO, int $perPage, int $page): PaginationResult
    {
        return new PaginationResult(
            page: $page,
            pagesize: $perPage,
            items: array_map(function (QuestionDTO $item) {
                return new Question(
                    id: $item->questionId,
                    title: $item->title,
                    owner: new Owner(id: $item->owner->userId, displayName: $item->owner->displayName, profileLink: $item->owner->profileImage),
                    viewCount: $item->viewCount,
                    answerCount: $item->answerCount,
                    score: $item->score,
                    link: $item->link,
                    creationDate: new \DateTime('@' . $item->creationDate)
                );
            }, $responseDTO->items)
        );
    }
}
