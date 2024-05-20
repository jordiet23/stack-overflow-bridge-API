<?php

declare(strict_types=1);

namespace App\Tests\Application\Question;

use App\Application\Question\DTO\PaginationResult;
use App\Application\Question\DTO\QuestionsPaginationParams;
use App\Application\Question\QuestionInfoProvider;
use App\Domain\Owner;
use App\Domain\Question;
use App\Domain\QuestionRepositoryInterface;
use App\Infrastructure\Client\Question\DTO\OwnerDTO;
use App\Infrastructure\Client\Question\DTO\QuestionDTO;
use App\Infrastructure\Client\Question\DTO\QuestionsPaginateResponseDTO;
use PHPUnit\Framework\TestCase;
use Symfony\Contracts\HttpClient\Exception\ClientExceptionInterface;

class QuestionInfoProviderTest extends TestCase
{
    private QuestionRepositoryInterface $questionRepository;
    protected function setUp(): void
    {
        parent::setUp();
        $this->questionRepository = $this->getMockBuilder(QuestionRepositoryInterface::class)
            ->getMock();
    }

    public function testPaginate(): void
    {
        $this->questionRepository = $this->createMock(QuestionRepositoryInterface::class);

        $this->questionRepository->expects($this->once())
            ->method('paginate')
            ->willReturn(new QuestionsPaginateResponseDTO(
                items: [
                    new QuestionDTO(
                        tags: ['Symfony'],
                        owner: new OwnerDTO(
                            accountId: null,
                            reputation: null,
                            userId: 1,
                            userType: null,
                            profileImage: null,
                            displayName: 'Jordi',
                            link: 'www.google.com'),
                        isAnswered: false,
                        viewCount: 100,
                        acceptedAnswerId: null,
                        answerCount: 3,
                        score: 5,
                        lastActivityDate: time(),
                        creationDate: time(),
                        lastEditDate: null,
                        questionId: 123,
                        contentLicense: 'CC BY-SA 4.0',
                        link: 'http://test.com/123',
                        title: 'Sample Question',
                        body: "<p>This is a Jordi's Test Question</p>"
                    )
                ],
                hasMore: false,
                quotaMax: 300,
                quotaRemaining: 280
            ));

        $questionInfoProvider = new QuestionInfoProvider($this->questionRepository);

        $paginationParams = new QuestionsPaginationParams(
            page: 1,
            pagesize: 10,
            order: 'desc',
            sort: 'activity'
        );

        $paginationResult = $questionInfoProvider->paginate($paginationParams);

        $this->assertInstanceOf(PaginationResult::class, $paginationResult);
        $this->assertEquals(1, $paginationResult->page);
        $this->assertEquals(10, $paginationResult->pagesize);
        $this->assertCount(1, $paginationResult->items);

        $question = $paginationResult->items[0];
        $this->assertInstanceOf(Question::class, $question);
        $this->assertEquals(123, $question->id);
        $this->assertEquals('Sample Question', $question->title);
        $this->assertInstanceOf(Owner::class, $question->owner);
        $this->assertEquals(1, $question->owner->id);
        $this->assertEquals('Jordi', $question->owner->displayName);
        $this->assertEquals('www.google.com', $question->owner->profileLink);
        $this->assertEquals(100, $question->viewCount);
        $this->assertEquals(3, $question->answerCount);
        $this->assertEquals(5, $question->score);
        $this->assertEquals('http://test.com/123', $question->link);
        $this->assertInstanceOf(\DateTime::class, $question->creationDate);
        $this->assertEquals("<p>This is a Jordi's Test Question</p>", $question->body);
    }

    public function testPaginateWithEmptyList(): void
    {
        $this->questionRepository = $this->createMock(QuestionRepositoryInterface::class);

        $this->questionRepository->expects($this->once())
            ->method('paginate')
            ->willReturn(new QuestionsPaginateResponseDTO(
                items: [],
                hasMore: false,
                quotaMax: 300,
                quotaRemaining: 280
            ));

        $questionInfoProvider = new QuestionInfoProvider($this->questionRepository);

        $paginationParams = new QuestionsPaginationParams(
            page: 1,
            pagesize: 10,
            order: 'desc',
            sort: 'activity'
        );

        $paginationResult = $questionInfoProvider->paginate($paginationParams);

        $this->assertInstanceOf(PaginationResult::class, $paginationResult);
        $this->assertEquals(1, $paginationResult->page);
        $this->assertEquals(10, $paginationResult->pagesize);
        $this->assertCount(0, $paginationResult->items);
    }

    public function testPaginateWithError(): void
    {
        $this->questionRepository = $this->createMock(QuestionRepositoryInterface::class);

        $this->questionRepository->expects($this->once())
            ->method('paginate')
            ->willThrowException($this->createMock(ClientExceptionInterface::class));

        $questionInfoProvider = new QuestionInfoProvider($this->questionRepository);

        $paginationParams = new QuestionsPaginationParams(
            page: 1,
            pagesize: 10,
            order: 'desc',
            sort: 'activity'
        );

        $this->expectException(ClientExceptionInterface::class);

        $questionInfoProvider->paginate($paginationParams);
    }
}
