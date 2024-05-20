<?php

namespace App\Api\Answer;

use App\Application\Answer\AnswerInfoProviderInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use OpenApi\Attributes as OA;

#[OA\Get(
    path: '/questions/{questionId}/answers',
    summary: 'Get answers by question ID',
    parameters: [
        new OA\Parameter(
            name: 'questionId',
            description: 'ID of the question',
            in: 'path',
            required: true,
            schema: new OA\Schema(type: 'integer')
        ),
    ],
    responses: [
        new OA\Response(
            response: 200,
            description: 'Successful response',
            content: new OA\JsonContent(
                type: 'array',
                items: new OA\Items(ref: '#/components/schemas/Answer')
            )
        )
    ]
)]
#[Route('/questions/{questionId}/answers', name: 'api_answers_by_question', methods: ['GET'])]
class AnswersByQuestionRequestHandler extends AbstractController
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
