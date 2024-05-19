<?php

namespace App\Infrastructure\Event;

use App\Api\Answer\AnswersByQuestionRequestHandler;
use Psr\Log\LoggerInterface;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpClient\Exception\ClientException;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Event\ExceptionEvent;
use Symfony\Component\HttpKernel\KernelEvents;
use Symfony\Component\Validator\Exception\ValidationFailedException;

class KernelExceptionEvent implements EventSubscriberInterface
{

    public function __construct(private readonly LoggerInterface $logger)
    {
    }

    public static function getSubscribedEvents(): array
    {
        return [
            KernelEvents::EXCEPTION => 'onKernelException'
        ];
    }

    // Creates a valid JSON response on Kernel panic exceptions
    public function onKernelException(ExceptionEvent $event): void
    {
        $exception = $event->getThrowable();

        [$statusCode, $message] = $this->getStatusCodeAndMessage($exception);

        $this->logger->error(
            sprintf("KERNEL PANIC ERROR:\n - Code: %d\n - Message: %s", $statusCode, $message),
            ['exception' => $exception]
        );

        $event->setResponse(new JsonResponse(
            data: ['error' => ['code' => $statusCode, 'message' => $message]],
            status: $statusCode
        ));
    }

    /**
     * @return array [status-code, message]
     */
    private function getStatusCodeAndMessage(\Throwable $exception): array
    {
        if ($exception instanceof ValidationFailedException) {
            $violations = $exception->getViolations();
            $errorMessages = [];

            foreach ($violations as $violation) {
                $errorMessages[] = sprintf(
                    '%s: %s',
                    $violation->getPropertyPath(),
                    $violation->getMessage()
                );
            }

            return [
                Response::HTTP_BAD_REQUEST,
                json_encode(['errors' => $errorMessages]),
            ];
        }

        if ($exception instanceof ClientException) {
            try {
                $errorMessage = $exception->getResponse()->getContent(false);
            } catch (\Throwable) {
                $errorMessage = $exception->getMessage();
            }
            return [
                $exception->getCode(),
                $errorMessage,
            ];
        }

        if ($exception instanceof \TypeError) {
            return[
                Response::HTTP_BAD_REQUEST,
                'The provided argument is not of the correct type.'
            ];
        }

        return [
            Response::HTTP_INTERNAL_SERVER_ERROR,
            $exception->getMessage(),
        ];
    }
}
