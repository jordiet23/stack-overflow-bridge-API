<?php

namespace App\Infrastructure\Client\Question;

use App\Application\Question\DTO\QuestionsPaginationParams;
use App\Domain\QuestionRepositoryInterface;
use App\Infrastructure\Client\Question\DTO\QuestionDTO;
use App\Infrastructure\Client\Question\DTO\QuestionsPaginateResponseDTO;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Contracts\HttpClient\Exception\ClientExceptionInterface;
use Symfony\Contracts\HttpClient\HttpClientInterface;

class QuestionClient implements QuestionRepositoryInterface
{

    public function __construct(
        private readonly HttpClientInterface $stackExchangeClient,
        private readonly SerializerInterface $serializer
    )
    {
    }

    public function paginate(QuestionsPaginationParams $params): QuestionsPaginateResponseDTO
    {
        try {
            $queryParams = http_build_query($params);

            $content = $this->stackExchangeClient->request(
                method: 'GET',
                url: "questions?{$queryParams}",
            )->getContent();

            return $this->deserializeResponse($content);
        } catch (ClientExceptionInterface $e) {
            throw new \RuntimeException('Client error: ' . $e->getMessage());
        } catch (\Throwable $e) {
            throw new \RuntimeException('An error occurred: ' . $e->getMessage());
        }
    }

    private function deserializeResponse(string $content): QuestionsPaginateResponseDTO
    {
        $responseDto = $this->serializer->deserialize($content, QuestionsPaginateResponseDTO::class, 'json');
        $responseDto->items = $this->serializer->denormalize($responseDto->items, QuestionDTO::class . '[]');

        return $responseDto;
    }
}