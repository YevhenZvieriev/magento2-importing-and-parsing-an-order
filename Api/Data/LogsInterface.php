<?php

declare(strict_types=1);

namespace Jenyamba\ParseXml\Api\Data;

interface LogsInterface
{
    const ID = 'item_id';

    const STATUS = 'status';

    const MESSAGE = 'message';

    const FILE = 'file';

    /**
     * @return string
     */
    public function getStatus(): string;

    /**
     * @param string $status
     * @return $this
     */
    public function setStatus(string $status): self;

    /**
     * @return string
     */
    public function getMessage(): string;

    /**
     * @param string $message
     * @return $this
     */
    public function setMessage(string $message): self;

    /**
     * @return string
     */
    public function getFileName(): string;

    /**
     * @param string $fileName
     * @return $this
     */
    public function setFileName(string $fileName): self;
}
