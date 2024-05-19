<?php

namespace App\Infrastructure\Client;

use App\Application\Question\DTO\QuestionsPaginationParams;
use App\Domain\QuestionRepositoryInterface;
use App\Infrastructure\Client\DTO\ItemDTO;
use App\Infrastructure\Client\DTO\ResponseDTO;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Contracts\HttpClient\Exception\ClientExceptionInterface;
use Symfony\Contracts\HttpClient\HttpClientInterface;

class StackExchangeClient implements QuestionRepositoryInterface
{

    public function __construct(
        private readonly HttpClientInterface $stackExchangeClient,
        private readonly SerializerInterface $serializer
    )
    {
    }

    public function paginate(QuestionsPaginationParams $params): ResponseDTO
    {
        try {
            $queryParams = http_build_query($params);

            $content = $this->stackExchangeClient->request(
                method: 'GET',
                url: "questions?{$queryParams}&site=stackoverflow",
            )->getContent();

            $responseDto = $this->deserializeResponseDTO($content);

            $responseDto->items = $this->deserializeItems($responseDto->items);

            return $responseDto;
        } catch (ClientExceptionInterface $e) {
            throw new \RuntimeException('Client error: ' . $e->getMessage());
        } catch (\Throwable $e) {
            throw new \RuntimeException('An error occurred: ' . $e->getMessage());
        }
    }

    private function deserializeResponseDTO(string $content): ResponseDTO
    {
        return $this->serializer->deserialize($content, ResponseDTO::class, 'json');
    }

    private function deserializeItems(array $items): array
    {
        return $this->serializer->denormalize($items, ItemDTO::class . '[]');
    }
}