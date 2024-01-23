<?php

declare(strict_types=1);

namespace Jenyamba\ParseXml\Model\ResourceModel;

use Magento\Framework\Model\ResourceModel\Db\AbstractDb;
use Magento\Framework\Model\ResourceModel\Db\Context;

class Logger extends AbstractDb
{
    private const TABLE_NAME = 'logger';
    private const PRIMARY_KEY = 'item_id';

    public function __construct(
        Context $context, $connectionName = null
    ) {
        parent::__construct($context, $connectionName);
    }

    /**
     * @return void
     */
    protected function _construct()
    {
        $this->_init(self::TABLE_NAME, self::PRIMARY_KEY);
    }
}
