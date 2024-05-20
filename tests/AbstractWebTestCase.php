<?php

namespace App\Tests;

    use Symfony\Bundle\FrameworkBundle\KernelBrowser;
    use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
    use Symfony\Component\HttpFoundation\Response;

enum Method
{
    case GET;
    case POST;
    case DELETE;
    case PATCH;
}

class AbstractWebTestCase extends WebTestCase
{
    protected KernelBrowser $client;

    protected function setUp(): void
    {
        parent::setUp();

        $this->client = static::createClient();
        $this->client->followRedirects();
    }

    protected function request(Method $method, string $resource, array $content = [], array $parameters = []): Response
    {
        try {
            $this->client->request(
                $method->name,
                $resource,
                $parameters,
                [],
                ($method !== Method::GET) ? ['CONTENT_TYPE' => 'application/json'] : [],
                ($method !== Method::GET) ? json_encode($content, JSON_THROW_ON_ERROR) : null
            );
        } catch (\JsonException $e) {
            $this->fail(sprintf('Error encoding request body: %s', $e->getMessage()));
        }

        return $this->client->getResponse();
    }

    protected function decodeJsonResponse(Response $response): array
    {
        try {
            return json_decode($response->getContent(), true, 512, JSON_THROW_ON_ERROR);
        } catch (\Exception $e) {
            $this->fail(sprintf('Error decoding response: %s', $e->getMessage()));
        }
    }

    protected function assertArrayContains(array $array, array $content): void
    {
        foreach ($content as $key => $value) {
            $this->assertArrayHasKey($key, $array);
            if (is_array($value)) {
                $this->assertArrayContains($array[$key], $value);
                continue;
            }

            $this->assertEquals($value, $array[$key], sprintf('The key `%s` has different value' . PHP_EOL, $key));
        }
    }

    protected function assertResponseOk(Response $response): void
    {
        $this->assertResponseCode($response, Response::HTTP_OK);
    }

    protected function assertResponseBadRequest(Response $response): void
    {
        $this->assertResponseCode($response, Response::HTTP_BAD_REQUEST);
    }

    private function assertResponseCode(Response $response, int $code): void
    {
        self::assertEquals($code, $response->getStatusCode());
    }


    protected function assertResponseErrorContains(
        Response $response,
        int $code,
        string $message
    ): void
    {
        $content = $this->decodeJsonResponse($response);

        $this->assertArrayHasKey('error', $content);
        $this->assertArrayContains(
            $content['error'],
            [
                'code' => $code,
                'message' => $message
            ]
        );
    }

    protected function getJsonResponse(string $filename): array
    {
        try {
            return json_decode(
                file_get_contents(
                    sprintf(
                        '%s/Asset/%s.json',
                        dirname(
                            (new \ReflectionClass($this))->getFileName()
                        ),
                        $filename
                    )
                ),
                true,
                512,
                JSON_THROW_ON_ERROR
            );
        } catch (\JsonException $e) {
            $this->fail(
                sprintf('Could not retrieve Response from Assets. Error: %s', $e->getMessage())
            );
        }
    }

}