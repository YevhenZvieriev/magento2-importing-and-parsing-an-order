<?php

declare(strict_types=1);

namespace Jenyamba\ParseXml\Model;

use Magento\Framework\Model\AbstractModel;

class StoreMapping extends AbstractModel
{
    protected function _construct()
    {
        $this->_init(\Jenyamba\ParseXml\Model\ResourceModel\StoreMapping::class);
    }
}
