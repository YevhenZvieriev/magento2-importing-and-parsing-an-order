<?php

declare(strict_types=1);

namespace Jenyamba\ParseXml\Model;

use Jenyamba\ParseXml\Api\Data\LogsInterface;
use Jenyamba\ParseXml\Model\ResourceModel\Logger as ResourceModel;
use Magento\Framework\Model\AbstractModel;

class Logger extends AbstractModel implements LogsInterface
{
    /**
     * @return void
     */
    protected function _construct()
    {
        $this->_init(ResourceModel::class);
    }

    /**
     * @inheritDoc
     */
    public function getStatus(): string
    {
         return $this->getData(LogsInterface::STATUS);
    }

    /**
     * @inheritDoc
     */
    public function setStatus(string $status): Logger
    {
        return $this->setData(LogsInterface::STATUS,$status);
    }

    /**
     * @inheritDoc
     */
    public function getMessage(): string
    {
        return $this->getData(LogsInterface::MESSAGE);
    }

    /**
     * @inheritDoc
     */
    public function setMessage(string $message): Logger
    {
        return $this->setData(LogsInterface::MESSAGE,$message);
    }

    /**
     * @inheritDoc
     */
    public function getFileName(): string
    {
        return $this->getData(LogsInterface::FILE);
    }

    /**
     * @inheritDoc
     *
     */
    public function setFileName(string $fileName): Logger
    {
        return $this->setData(LogsInterface::FILE,$fileName);
    }
}
