<?php

namespace App\services;

use Psr\Log\LoggerInterface;

class FirstService
{
    public function __construct(private LoggerInterface $logger)
    {
    }

    public function loger(string $message = 'hello'): void {
        $this->logger->info('Logged From First Service');
        $this->logger->log($message);
    }

}