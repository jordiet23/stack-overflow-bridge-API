<?php

namespace App\Api\Answer;

use App\Application\Answer\AnswerInfoProviderInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Contracts\HttpClient\Exception\ClientExceptionInterface;

#[Route('/questions/{questionId}/answers', name: 'api_answers_by_question', methods: ['GET'])]
class AnswersByQuestionRequestHandler extends AbstractController
{

    public function __construct(
        private readonly AnswerInfoProviderInterface $answerInfoProvider
    )
    {
    }

    /**
     * @throws ClientExceptionInterface
     */
    public function __invoke(int $questionId, Request $request): JsonResponse
    {
        return new JsonResponse($this->answerInfoProvider->getAnswersByQuestionId($questionId));
    }
}
