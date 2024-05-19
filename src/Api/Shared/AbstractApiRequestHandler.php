<?php

namespace App\Api\Shared;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;

class AbstractApiRequestHandler extends AbstractController
{
    protected function jsonError(string $message, int $errorCode, mixed $payload = null, int $status = 400): JsonResponse
    {
        return new JsonErrorResponse(message: $message, errorCode: $errorCode, payload: $payload, status: $status);
    }
}
