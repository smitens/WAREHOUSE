<?php
namespace Warehouse\App;

use Monolog\Logger;
use Monolog\Handler\StreamHandler;
use Monolog\Formatter\LineFormatter;

class LogService
{
    private Logger $logger;

    public function __construct()
    {
        $formatter = new LineFormatter("%message%\n");
        $this->logger = new Logger('warehouse_app');
        $this->logger->pushHandler(new StreamHandler(__DIR__.'warehouse.log', Logger::INFO));
        $handler = $this->logger->getHandlers()[0]; // Assuming there's only one handler
        $handler->setFormatter($formatter);
    }

    public function log(string $message): void
    {
        $this->logger->info($message);
    }
}
