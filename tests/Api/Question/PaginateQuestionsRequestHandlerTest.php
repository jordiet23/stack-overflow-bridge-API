<?php

namespace App\Tests\Api\Question;

use App\Application\Question\DTO\QuestionsPaginationParams;
use App\Tests\AbstractWebTestCase;
use App\Tests\Method;

class PaginateQuestionsRequestHandlerTest extends AbstractWebTestCase
{
    private string $url = '/questions';

    public function testPaginate(): void
    {
        $params = new QuestionsPaginationParams(
            page: 1, pagesize: 1, order: null, sort: null
        );

        $response = $this->request(Method::GET, $this->url . '?' . http_build_query($params->toArray()));
        $this->assertResponseOk($response);
        $questions = $this->decodeJsonResponse($response);
        $this->assertArrayContains($questions, ['page' => 1, 'pagesize' => 1]);
        $this->assertCount(3, $questions);
        $question = $questions['items'][0];
        $this->assertArrayHasKey('id', $question);
        $this->assertArrayHasKey('title', $question);
        $this->assertArrayHasKey('owner', $question);
        $this->assertArrayHasKey('viewCount', $question);
        $this->assertArrayHasKey('answerCount', $question);
        $this->assertArrayHasKey('score', $question);
        $this->assertArrayHasKey('link', $question);
        $this->assertArrayHasKey('creationDate', $question);
        $this->assertArrayHasKey('body', $question);
    }

    /** @dataProvider testPaginateFailedDataProvider */
    public function testPaginateFailed(QuestionsPaginationParams $params, string $message): void
    {
        $response = $this->request(Method::GET, $this->url . '?' . http_build_query($params->toArray()));
        $this->assertResponseBadRequest($response);
        $this->assertResponseErrorContains($response, 400, $message);
    }

    private function testPaginateFailedDataProvider(): array
    {
        return [
            [
                'params' => new QuestionsPaginationParams(
                    page: 0, pagesize: 1, order: null, sort: null
                ),
                'message' => '{"errors":["page: page must be greater than 0"]}'
            ],
            [
                'params' => new QuestionsPaginationParams(
                    page: 1, pagesize: 101, order: null, sort: null
                ),
                'message' => '{"errors":["pagesize: length must be fewer than 100"]}'
            ],
            [
                'params' => new QuestionsPaginationParams(
                    page: 1, pagesize: 1, order: 'test', sort: null
                ),
                'message' => '{"errors":["order: Invalid order value"]}'
            ],
            [
                'params' => new QuestionsPaginationParams(
                    page: 1, pagesize: 1, order: null, sort: 'test'
                ),
                'message' => '{"errors":["sort: Invalid sort value"]}'
            ],
        ];
    }
}