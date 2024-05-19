<?php

namespace App\Api\Shared;

use Symfony\Component\HttpFoundation\JsonResponse;

class JsonErrorResponse extends JsonResponse
{
    public function __construct(string $message, int $errorCode, mixed $payload = null, int $status = 400)
    {
        assert($status >= 400 && $status < 500, 'Status code must be between 400 and 499');

        $data = [
            'message' => $message,
            'code' => $errorCode,
        ];

        if (null !== $payload) {
            $data['payload'] = $payload;
        }

        parent::__construct(data: $data, status: $status);
    }
}
