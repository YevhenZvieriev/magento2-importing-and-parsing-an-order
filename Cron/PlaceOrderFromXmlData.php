<?php

declare(strict_types=1);

namespace Jenyamba\ParseXml\Cron;

use Jenyamba\ParseXml\Service\OrderService;
use \Jenyamba\ParseXml\Logger\Logger;

class PlaceOrderFromXmlData
{
    /**
     * @var OrderService
     */
    private OrderService $orderService;

    /**
     * @var Logger
     */
    private Logger $logger;

    /**
     * @param OrderService $orderService
     * @param Logger $logger
     */
    public function __construct(
        OrderService $orderService,
        Logger       $logger
    ) {
        $this->logger = $logger;
        $this->orderService = $orderService;
    }

    /**
     * @return void
     */
    public function execute(): void
    {
        $this->orderService->createOrderFromXmlData();
        $this->orderService->insertDataToDb();
        $this->logger->info("Data was  inserted  to table successfully");
    }
}
