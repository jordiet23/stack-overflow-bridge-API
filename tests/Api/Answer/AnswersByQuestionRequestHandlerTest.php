<?php

namespace App\Tests\Api\Answer;

use App\Tests\AbstractWebTestCase;
use App\Tests\Method;

class AnswersByQuestionRequestHandlerTest extends AbstractWebTestCase
{
    public function testGetAnswersByQuestionId(): void
    {
        $response = $this->request(Method::GET, '/questions/78507892/answers');
        $this->assertResponseOk($response);
        $answers = $this->decodeJsonResponse($response);
        $answer = $answers[0];
        $this->assertArrayHasKey('id', $answer);
        $this->assertArrayHasKey('questionId', $answer);
        $this->assertArrayHasKey('owner', $answer);
        $this->assertArrayHasKey('isAccepted', $answer);
        $this->assertArrayHasKey('creationDate', $answer);
        $this->assertArrayHasKey('body', $answer);
    }
}