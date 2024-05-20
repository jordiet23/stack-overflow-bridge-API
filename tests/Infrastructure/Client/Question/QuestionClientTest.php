<?php

namespace App\Tests\Infrastructure\Client\Question;

use App\Application\Question\DTO\QuestionsPaginationParams;
use App\Infrastructure\Client\Question\DTO\OwnerDTO;
use App\Infrastructure\Client\Question\DTO\QuestionDTO;
use App\Infrastructure\Client\Question\DTO\QuestionsPaginateResponseDTO;
use App\Infrastructure\Client\Question\QuestionClient;
use PHPUnit\Framework\TestCase;
use Symfony\Contracts\HttpClient\Exception\ClientExceptionInterface;
use Symfony\Contracts\HttpClient\HttpClientInterface;
use Symfony\Contracts\HttpClient\ResponseInterface;

class QuestionClientTest extends TestCase
{
    private HttpClientInterface $httpClientMock;
    protected function setUp(): void
    {
        parent::setUp();
        $this->httpClientMock = $this->createMock(HttpClientInterface::class);
    }

    public function testGetAnswersByQuestionId(): void
    {
        $questionClient = new QuestionClient($this->httpClientMock);

        $responseContent = '{"items":[{"tags":["symfony"],"owner":{"account_id":1,"user_id":1,"user_type":"registered","display_name":"Jordi","link":"www.google.com"},"is_answered":true,"view_count":77289,"protected_date":1442965259,"accepted_answer_id":7,"answer_count":13,"community_owned_date":1351701767,"score":807,"last_activity_date":1662613646,"creation_date":1217540572,"last_edit_date":1662613646,"question_id":4,"content_license":"CC BY-SA 4.0","link":"https://stackoverflow.com/questions/4/how-to-convert-decimal-to-double-in-c","title":"How to convert Decimal to Double in C#?"}],"has_more":true,"quota_max":10000,"quota_remaining":9708}';

        $responseMock = $this->createMock(ResponseInterface::class);
        $responseMock->method('getContent')->willReturn($responseContent);
        $this->httpClientMock->expects($this->once())
            ->method('request')
            ->willReturn($responseMock);

        $responseDto = $questionClient->paginate(new QuestionsPaginationParams(null, 1, 'asc', 'creation'));

        $this->assertInstanceOf(QuestionsPaginateResponseDTO::class, $responseDto);
        $this->assertCount(1, $responseDto->items);

        $questionDto = $responseDto->items[0];
        $this->assertInstanceOf(QuestionDTO::class, $questionDto);
        $this->assertEquals(true, $questionDto->isAnswered);
        $this->assertEquals(77289, $questionDto->viewCount);
        $this->assertEquals(7, $questionDto->acceptedAnswerId);
        $this->assertEquals(13, $questionDto->answerCount);
        $this->assertEquals(807, $questionDto->score);
        $this->assertEquals(1662613646, $questionDto->lastActivityDate);
        $this->assertEquals(1217540572, $questionDto->creationDate);
        $this->assertEquals(4, $questionDto->questionId);
        $this->assertEquals('CC BY-SA 4.0', $questionDto->contentLicense);
        $this->assertEquals('https://stackoverflow.com/questions/4/how-to-convert-decimal-to-double-in-c', $questionDto->link);
        $this->assertEquals('How to convert Decimal to Double in C#?', $questionDto->title);
        $this->assertEquals(null , $questionDto->body);

        $ownerDto = $questionDto->owner;
        $this->assertInstanceOf(OwnerDTO::class, $ownerDto);
        $this->assertEquals(1, $ownerDto->accountId);
        $this->assertNull($ownerDto->reputation);
        $this->assertEquals(1, $ownerDto->userId);
        $this->assertEquals('registered', $ownerDto->userType);
        $this->assertEquals('Jordi', $ownerDto->displayName);
        $this->assertEquals('www.google.com', $ownerDto->link);
    }

    public function testGetAnswersByQuestionIdWithEmptyList(): void
    {
        $questionClient = new QuestionClient($this->httpClientMock);

        $responseContent = '{"items":[],"has_more":false,"quota_max":10000,"quota_remaining":9766}';

        $responseMock = $this->createMock(ResponseInterface::class);
        $responseMock->method('getContent')->willReturn($responseContent);
        $this->httpClientMock->expects($this->once())
            ->method('request')
            ->willReturn($responseMock);


        $responseDto = $questionClient->paginate(new QuestionsPaginationParams(null, 1, 'asc', 'creation'));

        $this->assertInstanceOf(QuestionsPaginateResponseDTO::class, $responseDto);
        $this->assertCount(0, $responseDto->items);
    }

    public function testGetAnswersByQuestionIdWithError(): void
    {
        $questionClient = new QuestionClient($this->httpClientMock);

        $exception = $this->createMock(ClientExceptionInterface::class);
        $this->httpClientMock
            ->method('request')
            ->willThrowException($exception);


        $this->expectException(ClientExceptionInterface::class);

        $questionClient->paginate(new QuestionsPaginationParams(null, 1, 'asc', 'creation'));
    }
}
