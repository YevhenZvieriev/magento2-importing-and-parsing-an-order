<?php

declare(strict_types=1);

namespace Jenyamba\ParseXml\Model\ResourceModel\Logger;

use Jenyamba\ParseXml\Model\Logger as Model;
use Jenyamba\ParseXml\Model\ResourceModel\Logger as ResourceModel;
use Magento\Framework\Model\ResourceModel\Db\Collection\AbstractCollection;

class Collection extends AbstractCollection
{
    /**
     * @return void
     */
    protected function _construct()
    {
        $this->_init(Model::class, ResourceModel::class);
    }
}
