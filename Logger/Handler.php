<?php

declare(strict_types=1);

namespace Jenyamba\ParseXml\Logger;
use Magento\Framework\Logger\Handler\Base;

class Handler extends Base
{
    /**
     * @var string
     */
    protected $fileName = 'var/log/custom.log';
}
