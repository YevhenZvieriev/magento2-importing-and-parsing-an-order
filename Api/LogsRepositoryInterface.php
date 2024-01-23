<?php

declare(strict_types=1);

namespace Jenyamba\ParseXml\Api;

use Jenyamba\ParseXml\Api\Data\LogsInterface;

interface LogsRepositoryInterface
{
    /**
     * @param LogsInterface $logs
     * @return LogsInterface
     */
    public function save(LogsInterface $logs): LogsInterface;
}
