<?php

namespace App\Api\Question;

use App\Api\Shared\AbstractApiRequestHandler;
use App\Application\Question\DTO\QuestionsPaginationParams;
use App\Application\Question\QuestionInfoProviderInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\Normalizer\AbstractObjectNormalizer;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\Validator\Exception\ValidationFailedException;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use Symfony\Contracts\HttpClient\Exception\ClientExceptionInterface;

#[Route('/questions', name: 'api.questions', methods: ['GET'])]
class PaginateQuestionsRequestHandler extends AbstractApiRequestHandler
{

    public function __construct(
        private readonly SerializerInterface $serializer,
        private readonly ValidatorInterface $validator,
        private readonly QuestionInfoProviderInterface $questionInfoProvider
    ) {
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
