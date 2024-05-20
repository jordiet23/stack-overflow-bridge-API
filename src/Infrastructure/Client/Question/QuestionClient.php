<?php

namespace App\Infrastructure\Client\Question;

use App\Application\Question\DTO\QuestionsPaginationParams;
use App\Domain\QuestionRepositoryInterface;
use App\Infrastructure\Client\Question\DTO\OwnerDTO;
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

    /**
     * @throws ClientExceptionInterface
     */
    public function paginate(QuestionsPaginationParams $params): QuestionsPaginateResponseDTO
    {
        try {
            $queryParams = http_build_query($params->toArray());

            $content = $this->stackExchangeClient->request(
                method: 'GET',
                url: "questions?{$queryParams}",
            )->getContent();

            return $this->deserializeResponse($content);
        } catch (ClientExceptionInterface $e) {
            throw $e;
        } catch (\Throwable $e) {
            throw new \RuntimeException('An error occurred: ' . $e->getMessage());
        }
    }

    private function deserializeResponse(string $content): QuestionsPaginateResponseDTO
    {
        $responseDto = $this->serializer->deserialize($content, QuestionsPaginateResponseDTO::class, 'json');
        $responseDto->setItems(array_map([$this, 'mapToQuestionDTO'], $responseDto->getItems()));

        return $responseDto;
    }

    private function mapToQuestionDTO(array $item): QuestionDTO
    {
        $ownerDTO = isset($item['owner']) ? new OwnerDTO(
            accountId: $item['owner']['account_id'] ?? null,
            reputation: $item['owner']['reputation'] ?? null,
            userId: isset($item['owner']['user_id']) ? : null,
            userType: $item['owner']['user_type'] ?? null,
            profileImage: $item['owner']['profile_image'] ?? null,
            displayName: $item['owner']['display_name'] ?? null,
            link: $item['owner']['link'] ?? null
        ) : null;

        return new QuestionDTO(
            tags: $item['tags'],
            owner: $ownerDTO,
            isAnswered: $item['is_answered'],
            viewCount: $item['view_count'],
            acceptedAnswerId: $item['accepted_answer_id'] ?? null,
            answerCount: $item['answer_count'],
            score: $item['score'],
            lastActivityDate: $item['last_activity_date'],
            creationDate: $item['creation_date'],
            lastEditDate: $item['last_edit_date'] ?? null,
            questionId: $item['question_id'],
            contentLicense: $item['content_license'] ?? null,
            link: $item['link'],
            title: $item['title'],
            body: $item['body'] ?? null,
        );
    }
}