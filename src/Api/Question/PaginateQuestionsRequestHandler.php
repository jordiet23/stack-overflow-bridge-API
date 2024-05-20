<?php

namespace App\Api\Question;

use App\Application\Question\DTO\QuestionsPaginationParams;
use App\Application\Question\QuestionInfoProviderInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\Normalizer\AbstractObjectNormalizer;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\Validator\Exception\ValidationFailedException;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use Symfony\Contracts\HttpClient\Exception\ClientExceptionInterface;
use OpenApi\Attributes as OA;

#[OA\Get(
    path: "/questions",
    description: "Retrieve a paginated list of questions from Stack Overflow. 
                   This endpoint allows you to specify page number, page size, 
                   sort order, and filter criteria to customize the list of questions returned.",
    summary: "Paginate questions",
    tags:["Questions"],
    parameters: [
        new OA\Parameter(name: "page", description: "Page number", in: "query", required: false, schema: new OA\Schema(type: "integer", minimum: 1)),
        new OA\Parameter(name: "pagesize", description: "Number of items per page", in: "query", required: false, schema: new OA\Schema(type: "integer", maximum: 100, minimum: 1)),
        new OA\Parameter(name: "order", description: "Order either ascending or descending", in: "query", required: false, schema: new OA\Schema(type: "string", enum:["asc","desc"])),
        new OA\Parameter(name: "sort", description: "Sort by", in: "query", required: false, schema: new OA\Schema(type: "string", enum:["activity","votes","creation","hot","week","month"]))
    ],
    responses: [
        new OA\Response(
            response: 200,
            description: 'Successful response',
            content: new OA\JsonContent(
                type: 'array',
                items: new OA\Items(ref: '#/components/schemas/PaginatedQuestionsResponse')
            )
        ),
        new OA\Response(
            response: 400,
            description: "Validation error",
            content: new OA\JsonContent(
                ref: '#/components/schemas/PaginatedQuestionsErrorResponse',
                type: 'object'
            )
        ),
    ]
)]
#[Route('/questions', name: 'api.questions', methods: ['GET'])]
class PaginateQuestionsRequestHandler extends AbstractController
{

    public function __construct(
        private readonly SerializerInterface $serializer,
        private readonly ValidatorInterface $validator,
        private readonly QuestionInfoProviderInterface $questionInfoProvider
    )
    {
    }

    /**
     * @throws ClientExceptionInterface
     */
    public function __invoke(Request $request): JsonResponse
    {

        /** @var QuestionsPaginationParams $params */
        $params = $this->serializer->deserialize(
            json_encode($request->query->all()),
            QuestionsPaginationParams::class,
            'json',
            [AbstractObjectNormalizer::DISABLE_TYPE_ENFORCEMENT => true]);

        $errors = $this->validator->validate($params);

        if (count($errors) > 0) {
            throw new ValidationFailedException($params, $errors);
        }

        $result = $this->questionInfoProvider->paginate($params);

        return new JsonResponse($result);
    }
}
