<?php

namespace App\Application\Answer;

use App\Domain\Answer;
use App\Domain\AnswerRepositoryInterface;
use App\Domain\Owner;
use App\Infrastructure\Client\Answer\DTO\AnswerByQuestionResponseDTO;
use App\Infrastructure\Client\Answer\DTO\AnswerDTO;
use Symfony\Contracts\HttpClient\Exception\ClientExceptionInterface;

class AnswerInfoProvider implements AnswerInfoProviderInterface
{
    public function __construct(
        private readonly AnswerRepositoryInterface $answerRepository
    )
    {
    }

    /**
     * @throws ClientExceptionInterface
     */
    public function getAnswersByQuestionId(int $questionId): array
    {
        return $this->parseResult($this->answerRepository->getAnswersByQuestionId($questionId));
    }

    private function parseResult(AnswerByQuestionResponseDTO $responseDTO): array
    {
        return array_map(function (AnswerDTO $item) {
                return new Answer(
                    id: $item->answerId,
                    questionId: $item->questionId,
                    owner: new Owner(id: $item->owner->userId, displayName: $item->owner->displayName, profileLink: $item->owner->profileImage),
                    isAccepted: $item->isAccepted,
                    creationDate: new \DateTime('@' . $item->creationDate)
                );
            }, $responseDTO->items);
    }
}
