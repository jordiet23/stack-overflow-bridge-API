<?php

namespace App\Infrastructure\Client\Answer;

use App\Domain\AnswerRepositoryInterface;
use App\Infrastructure\Client\Answer\DTO\AnswerByQuestionResponseDTO;
use App\Infrastructure\Client\Answer\DTO\AnswerDTO;
use App\Infrastructure\Client\Question\DTO\OwnerDTO;
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
        $responseDto->setItems(array_map([$this, 'mapToAnswerDTO'], $responseDto->getItems()));
        return $responseDto;
    }

    private function mapToAnswerDTO(array $item): AnswerDTO
    {
        $ownerDTO = new OwnerDTO(
            accountId: $item['owner']['account_id'] ?? null,
            reputation: $item['owner']['reputation'] ?? null,
            userId: $item['owner']['user_id'] ?? null,
            userType: $item['owner']['user_type'] ?? null,
            profileImage: $item['owner']['profile_image'] ?? null,
            displayName: $item['owner']['display_name'] ?? null,
            link: $item['owner']['link'] ?? null
        );

        return new AnswerDTO(
            owner: $ownerDTO,
            isAccepted: $item['is_accepted'],
            score: $item['score'],
            lastActivityDate: $item['last_activity_date'],
            creationDate: $item['creation_date'],
            answerId: $item['answer_id'],
            questionId: $item['question_id'],
            contentLicense: $item['content_license'] ?? null,
            body: $item['body'] ?? null,
        );
    }
}