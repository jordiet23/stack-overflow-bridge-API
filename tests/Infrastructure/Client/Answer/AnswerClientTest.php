<?php

namespace App\Tests\Infrastructure\Client\Answer;

use App\Infrastructure\Client\Answer\AnswerClient;
use App\Infrastructure\Client\Answer\DTO\AnswerByQuestionResponseDTO;
use App\Infrastructure\Client\Answer\DTO\AnswerDTO;
use App\Infrastructure\Client\Question\DTO\OwnerDTO;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Contracts\HttpClient\Exception\ClientExceptionInterface;
use Symfony\Contracts\HttpClient\HttpClientInterface;
use Symfony\Contracts\HttpClient\ResponseInterface;

class AnswerClientTest extends TestCase
{
    private HttpClientInterface $httpClientMock;
    private SerializerInterface $serializerMock;
    protected function setUp(): void
    {
        parent::setUp();
        $this->httpClientMock = $this->createMock(HttpClientInterface::class);
        $this->serializerMock = $this->createMock(SerializerInterface::class);
    }

    public function testGetAnswersByQuestionId(): void
    {

        $answerClient = new AnswerClient($this->httpClientMock, $this->serializerMock);

        $responseContent = '{"items":[{"owner":{"account_id":32175613,"reputation":1,"user_id":24972994,"user_type":"registered","display_name":"Jordi","link":"www.google.com"},"is_accepted":false,"score":0,"last_activity_date":1716203248,"creation_date":1716203248,"answer_id":78506247,"question_id":78465260,"content_license":"CC BY-SA 4.0","body":"Test Body"}],"has_more":false,"quota_max":10000,"quota_remaining":9766}';

        $responseMock = $this->createMock(ResponseInterface::class);
        $responseMock->method('getContent')->willReturn($responseContent);
        $this->httpClientMock->expects($this->once())
            ->method('request')
            ->willReturn($responseMock);

        $expectedDeserializeDto = new AnswerByQuestionResponseDTO(
            items: [
                [
                    'owner' => [
                        'account_id' => 32175613,
                        'reputation' => 1,
                        'user_id' => 24972994,
                        'user_type' => 'registered',
                        'profile_image' => null,
                        'display_name' => 'Jordi',
                        'link' => 'www.google.com'
                    ],
                    'is_accepted' => false,
                    'score' => 0,
                    'last_activity_date' => 1716203248,
                    'creation_date' => 1716203248,
                    'answer_id' => 78506247,
                    'question_id' => 78465260,
                    'content_license' => 'CC BY-SA 4.0',
                    'body' => "Test Body"
                ]
            ],
            hasMore: false,
            quotaMax: 300,
            quotaRemaining: 280
        );

        $this->serializerMock->expects($this->once())
            ->method('deserialize')
            ->with($responseContent, AnswerByQuestionResponseDTO::class, 'json')
            ->willReturn($expectedDeserializeDto);

        $responseDto = $answerClient->getAnswersByQuestionId(123);

        $this->assertInstanceOf(AnswerByQuestionResponseDTO::class, $responseDto);
        $this->assertCount(1, $responseDto->getItems());

        $answerDto = $responseDto->getItems()[0];
        $this->assertInstanceOf(AnswerDTO::class, $answerDto);
        $this->assertEquals(78506247, $answerDto->answerId);
        $this->assertEquals(78465260, $answerDto->questionId);
        $this->assertFalse($answerDto->isAccepted);
        $this->assertEquals(0, $answerDto->score);
        $this->assertEquals(1716203248, $answerDto->lastActivityDate);
        $this->assertEquals(1716203248, $answerDto->creationDate);
        $this->assertEquals('CC BY-SA 4.0', $answerDto->contentLicense);
        $this->assertEquals('Test Body', $answerDto->body);

        $ownerDto = $answerDto->owner;
        $this->assertInstanceOf(OwnerDTO::class, $ownerDto);
        $this->assertEquals(32175613, $ownerDto->accountId);
        $this->assertEquals(1, $ownerDto->reputation);
        $this->assertEquals(24972994, $ownerDto->userId);
        $this->assertEquals('registered', $ownerDto->userType);
        $this->assertEquals('Jordi', $ownerDto->displayName);
        $this->assertEquals('www.google.com', $ownerDto->link);
    }

    public function testGetAnswersByQuestionIdWithEmptyList(): void
    {
        $answerClient = new AnswerClient($this->httpClientMock, $this->serializerMock);

        $responseContent = '{"items":[],"has_more":false,"quota_max":10000,"quota_remaining":9766}';

        $responseMock = $this->createMock(ResponseInterface::class);
        $responseMock->method('getContent')->willReturn($responseContent);
        $this->httpClientMock->expects($this->once())
            ->method('request')
            ->willReturn($responseMock);

        $expectedDeserializeDto = new AnswerByQuestionResponseDTO(
            items: [],
            hasMore: false,
            quotaMax: 10000,
            quotaRemaining: 9766
        );

        $this->serializerMock->expects($this->once())
            ->method('deserialize')
            ->with($responseContent, AnswerByQuestionResponseDTO::class, 'json')
            ->willReturn($expectedDeserializeDto);


        $responseDto = $answerClient->getAnswersByQuestionId(123);

        $this->assertInstanceOf(AnswerByQuestionResponseDTO::class, $responseDto);
        $this->assertCount(0, $responseDto->getItems());
    }

    public function testGetAnswersByQuestionIdWithError(): void
    {
        $answerClient = new AnswerClient($this->httpClientMock, $this->serializerMock);

        $exception = $this->createMock(ClientExceptionInterface::class);
        $this->httpClientMock
            ->method('request')
            ->willThrowException($exception);


        $this->expectException(ClientExceptionInterface::class);

        $answerClient->getAnswersByQuestionId(123);
    }
}
