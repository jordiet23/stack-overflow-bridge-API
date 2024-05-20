<?php

namespace App\Infrastructure\Client\Answer;

use App\Domain\AnswerRepositoryInterface;
use App\Infrastructure\Client\Answer\DTO\AnswerByQuestionResponseDTO;
use App\Infrastructure\Client\Answer\DTO\AnswerDTO;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Contracts\HttpClient\Exception\ClientExceptionInterface;
use Symfony\Contracts\HttpClient\HttpClientInterface;

class AnswerClient implements AnswerRepositoryInterface
{

    public function __construct(
        private readonly HttpClientInterface $stackExchangeClient,
        private readonly SerializerInterface $serializer
    )
    {
    }

    /**
     * @throws ClientExceptionInterface
     */
    public function getAnswersByQuestionId(int $questionId): AnswerByQuestionResponseDTO
    {
        try {
            $content = $this->stackExchangeClient->request(
                method: 'GET',
                url: "questions/{$questionId}/answers",
            )->getContent();

            return $this->deserializeResponse($content);
        } catch (ClientExceptionInterface $e) {
            throw $e;
        } catch (\Throwable $e) {
            throw new \RuntimeException('An error occurred: ' . $e->getMessage());
        }
    }

    private function deserializeResponse(string $content): AnswerByQuestionResponseDTO
    {
        $responseDto = $this->serializer->deserialize($content, AnswerByQuestionResponseDTO::class, 'json');
        $responseDto->setItems($this->serializer->denormalize($responseDto->getItems(), AnswerDTO::class . '[]'));

        return $responseDto;
    }
}