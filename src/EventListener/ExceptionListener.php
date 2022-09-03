<?php

namespace App\EventListener;

use Symfony\Component\EventDispatcher\Attribute\AsEventListener;
use Symfony\Component\HttpKernel\Event\ExceptionEvent;

use Symfony\Component\HttpFoundation\JsonResponse;
use Psr\Log\LoggerInterface;
use App\Interface\CraftedRequestException;
use Symfony\Component\HttpFoundation\Response;

#[AsEventListener]
# TODO it's Listener
final class ExceptionListener
{
    private $logger;
    private $errorMessage;
    private $environment;

    public function __construct(LoggerInterface $craftedrequestLogger, string $errorMessage, string $environment)
    {
        $this->logger = $craftedrequestLogger;
        $this->errorMessage = $errorMessage;
        $this->environment = $environment;
    }

    public function __invoke(ExceptionEvent $event)
    {
        if ($this->environment == 'dev')
            return;

		$exception = $event->getThrowable();

        if ($exception instanceof CraftedRequestException) 
            $this->logEvent($event, $exception->getMessage());

        $event->setResponse(new JsonResponse($this->errorMessage, Response::HTTP_BAD_REQUEST));
    }

    private function logEvent(ExceptionEvent $event, string $message)
    {
		$ip = $event->getRequest()->getClientIp();
		$ips = $event->getRequest()->getClientIps();

        $this->logger->error("The requested is unatended : ".json_encode([$ip, $ips, $message]));
    }
}
