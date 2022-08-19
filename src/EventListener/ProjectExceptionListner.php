<?php

namespace App\EventListener;

use Symfony\Component\EventDispatcher\Attribute\AsEventListener;
use Symfony\Component\HttpKernel\Event\ExceptionEvent;

use App\Exception\CustomValidationException;
use App\Exception\CustomNotFoundException;
use Symfony\Component\HttpFoundation\JsonResponse;
use Psr\Log\LoggerInterface;
use App\Interface\CustomExceptionInterface;

#[AsEventListener]
final class ProjectExceptionListner
{
    private $logger;

    public function __construct(LoggerInterface $logger)
    {
        $this->logger = $logger;
    }

    public function __invoke(ExceptionEvent $event)
    {
		$exception = $event->getThrowable();
		
		if ($exception instanceof CustomValidationException) 
		{
      		$response = $this->handleApiException($event, $exception);
			$event->setResponse($response);
		}
		if ($exception instanceof CustomNotFoundException) 
        {
      		$response = $this->handleApiException($event, $exception);
			$event->setResponse($response);
        }
    }
	
	private function handleApiException(
		ExceptionEvent $event,
		CustomExceptionInterface $exception
	): JsonResponse
	{
		$ip = $event->getRequest()->getClientIp();
		$ips = $event->getRequest()->getClientIps();
		$message = $exception->getMessage();

        $this->logger->error("The requested is unatended : ".json_encode([$ip, $ips, $message]));
		
		return new JsonResponse("You request failed", 400);	
	} 
}