<?php

namespace App\System;


class Logger
{
    /**
     * @var \Monolog\Logger
     */
    protected $logger;

    /**
     * @return \Monolog\Logger
     */
    public function __invoke()
    {
        return $this->logger;
    }

    /**
     * Logger constructor.
     * @throws \Exception
     */
    public function __construct()
    {
        $this->logger = new \Monolog\Logger('my_logger');

        // Error handler, doesn't turn new lines to spaces
        $errorFileHandler = new \Monolog\Handler\StreamHandler(ROOT_PATH . '/var/log/error.log', \Monolog\Logger::ERROR, true, 0660);
        $formatter = new \Monolog\Formatter\LineFormatter(
            null, // Format of message in log, default [%datetime%] %channel%.%level_name%: %message% %context% %extra%\n
            null, // Datetime format
            true, // allowInlineLineBreaks option, default false
            true  // ignoreEmptyContextAndExtra option, default false
        );
        $errorFileHandler->setFormatter($formatter);
        // Info handler
        $infoFileHandler = new \Monolog\Handler\StreamHandler(ROOT_PATH . '/var/log/app.log', \Monolog\Logger::INFO, true, 0660);
        $this->logger->pushHandler($errorFileHandler);
        $this->logger->pushHandler($infoFileHandler);
    }
}