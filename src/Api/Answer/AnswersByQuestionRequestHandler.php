<?php

namespace App\Api\Answer;

use App\Api\Shared\AbstractApiRequestHandler;
use App\Application\Answer\AnswerInfoProviderInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/questions/{questionId}/answers', name: 'api_answers_by_question', methods: ['GET'])]
class AnswersByQuestionRequestHandler extends AbstractApiRequestHandler
{

    public function __construct(
        private readonly AnswerInfoProviderInterface $answerInfoProvider
    )
    {
    }

    public function __invoke(int $questionId, Request $request): JsonResponse
    {
        return new JsonResponse($this->answerInfoProvider->getAnswersByQuestionId($questionId));
    }
}