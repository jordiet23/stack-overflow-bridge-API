<?php

namespace App\Tests\Application\Answer;

use App\Application\Answer\AnswerInfoProvider;
use App\Domain\Answer;
use App\Domain\AnswerRepositoryInterface;
use App\Domain\Owner;
use App\Infrastructure\Client\Answer\DTO\AnswerByQuestionResponseDTO;
use App\Infrastructure\Client\Answer\DTO\AnswerDTO;
use App\Infrastructure\Client\Question\DTO\OwnerDTO;
use PHPUnit\Framework\TestCase;
use Symfony\Contracts\HttpClient\Exception\ClientExceptionInterface;

class AnswerInfoProviderTest extends TestCase
{
    private AnswerRepositoryInterface $answerRepositoryMock;
    protected function setUp(): void
    {
        $this->answerRepositoryMock = $this->getMockBuilder(AnswerRepositoryInterface::class)
            ->getMock();
    }

    public function testGetAnswersByQuestionId(): void
    {
        $this->answerRepositoryMock = $this->getMockBuilder(AnswerRepositoryInterface::class)
            ->getMock();
        $this->answerRepositoryMock->method('getAnswersByQuestionId')
            ->willReturn(new AnswerByQuestionResponseDTO(
                items: [
                    new AnswerDTO(
                        owner: new OwnerDTO(
                            accountId: null,
                            reputation: null,
                            userId: 100,
                            userType: null,
                            profileImage: null,
                            displayName: "Jordi",
                            link: "www.google.es"
                        ),
                        isAccepted: false,
                        score: 0,
                        lastActivityDate: 1716222529,
                        creationDate: 1716222529,
                        answerId: 78507743,
                        questionId: 60975540,
                        contentLicense: "CC BY-SA 4.0",
                        body: "<p>This is a Jordi's Test Answer</p>"
                    )
                ],
                hasMore: true,
                quotaMax: 300,
                quotaRemaining: 280
            ));

        $answerInfoProvider = new AnswerInfoProvider($this->answerRepositoryMock);

        $answers = $answerInfoProvider->getAnswersByQuestionId(1);


        /** @var Answer $answer */
        $answer = $answers[0];
        $this->assertInstanceOf(Answer::class, $answer);
        $this->assertInstanceOf(Owner::class, $answer->owner);
        $this->assertEquals(78507743, $answer->id);
        $this->assertEquals(60975540, $answer->questionId);
        $this->assertFalse($answer->isAccepted);
        $this->assertEquals(100, $answer->owner->id);
        $this->assertEquals("<p>This is a Jordi's Test Answer</p>", $answer->body);
        $this->assertInstanceOf(\DateTime::class, $answer->creationDate);
    }

    public function testGetAnswersByQuestionIdWithEmptyResponse(): void
    {
        $this->answerRepositoryMock = $this->getMockBuilder(AnswerRepositoryInterface::class)
            ->getMock();
        $this->answerRepositoryMock->method('getAnswersByQuestionId')
            ->willReturn(new AnswerByQuestionResponseDTO(
                items: [],
                hasMore: false,
                quotaMax: 300,
                quotaRemaining: 280
            ));

        $answerInfoProvider = new AnswerInfoProvider($this->answerRepositoryMock);

        $answers = $answerInfoProvider->getAnswersByQuestionId(1);

        $this->assertEmpty($answers);
    }

    public function testGetAnswersByQuestionIdFailed(): void
    {
        $this->answerRepositoryMock = $this->createMock(AnswerRepositoryInterface::class);
        $exception = $this->createMock(ClientExceptionInterface::class);
        $this->answerRepositoryMock
            ->method('getAnswersByQuestionId')
            ->willThrowException($exception);

        $answerInfoProvider = new AnswerInfoProvider($this->answerRepositoryMock);

        $this->expectException(ClientExceptionInterface::class);

        $answerInfoProvider->getAnswersByQuestionId(123);
    }

    public function testParseResultWithIncompleteOwnerData(): void
    {
        $this->answerRepositoryMock = $this->getMockBuilder(AnswerRepositoryInterface::class)
            ->getMock();
        $this->answerRepositoryMock->method('getAnswersByQuestionId')
            ->willReturn(new AnswerByQuestionResponseDTO(
                items: [
                    new AnswerDTO(
                        owner: new OwnerDTO(
                            accountId: null,
                            reputation: null,
                            userId: 100,
                            userType: null,
                            profileImage: null,
                            displayName: null,
                            link: null
                        ),
                        isAccepted: false,
                        score: 0,
                        lastActivityDate: 1716222529,
                        creationDate: 1716222529,
                        answerId: 78507743,
                        questionId: 60975540,
                        contentLicense: "CC BY-SA 4.0",
                        body: "<p>This is a Jordi's Test</p>"
                    )
                ],
                hasMore: true,
                quotaMax: 300,
                quotaRemaining: 280
            ));
        $answerInfoProvider = new AnswerInfoProvider($this->answerRepositoryMock);

        $answers = $answerInfoProvider->getAnswersByQuestionId(1);

        $this->assertCount(1, $answers);
        $this->assertInstanceOf(Answer::class, $answers[0]);
        $this->assertNull($answers[0]->owner->displayName);
        $this->assertNull($answers[0]->owner->profileLink);
    }
}
