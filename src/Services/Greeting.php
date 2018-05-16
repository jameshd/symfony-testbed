<?php

namespace App\Services;

use Psr\Log\LoggerInterface;

class Greeting
{
    /**
     * @LoggerInterface
     */
    private $logger;

    /**
     * @var string
     */
    private $message;

    public function __construct(LoggerInterface $logger, string $message)
    {
        $this->logger = $logger;
        $this->message = $message;
    }

    public function greet($name)
    {
        $this->logger->info("greeted $name");
        return $this->message . ", " . $name;
    }
}
