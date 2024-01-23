<?php

declare(strict_types=1);

namespace Jenyamba\ParseXml\Model;

use Jenyamba\ParseXml\Api\Data\LogsInterface;
use Jenyamba\ParseXml\Api\LogsRepositoryInterface;
use Jenyamba\ParseXml\Model\ResourceModel\Logger as LogsResource;
use Magento\Framework\Exception\AlreadyExistsException;

class LogsRepository implements LogsRepositoryInterface
{
    /**
     * @var LogsResource
     */
    private LogsResource $logsResource;

    /**
     * @param LogsResource $logsResource
     */
    public function __construct(
    LogsResource  $logsResource
    ) {
        $this->logsResource = $logsResource;
    }

    /**
     * @param LogsInterface $logs
     * @return LogsInterface
     * @throws AlreadyExistsException
     */
    public function save(LogsInterface $logs): LogsInterface
    {
        $this->logsResource->save($logs);
        return $logs;
    }
}
