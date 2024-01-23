<?php

declare(strict_types=1);

namespace Jenyamba\ParseXml\Logger;

class Logger extends \Monolog\Logger
{
    /**
     * Array with messages log
     * @var array
     */
    private array $messages = [];

    /**
     * Array with statuses og
     * @var array
     */
    private array $statuses = [];

    /**
     * @param string $name
     * @param array $handlers
     * @param array $processors
     */
    public function __construct(string $name, array $handlers = array(), array $processors = array())
    {
        parent::__construct($name, $handlers, $processors);
        $this->pushProcessor(function ($record) {
            $this->messages[] = $record['message'];
            $this->statuses[] = $record['level'];
            return $record;
        });
    }

    /**
     * @return array
     */
    public function getMessages(): array
    {
        return $this->messages;
    }

    /**
     * @return array
     */
    public function getStatuses():array
    {
        return $this->statuses;
    }
}
