<?php

declare(strict_types=1);

namespace Jenyamba\ParseXml\Model\ResourceModel\StoreMapping;

use Magento\Framework\Model\ResourceModel\Db\Collection\AbstractCollection;
use Jenyamba\ParseXml\Model\ResourceModel\StoreMapping as StoreMappingResource;
use Jenyamba\ParseXml\Model\StoreMapping  as StoreMappingModel;

class Collection extends AbstractCollection
{
    protected $_idFieldName = 'item_id';
    protected $_eventPrefix = 'jenyamba_parsexml_store_mapping_collection';
    protected $_eventObject = 'store_mapping_collection';

    protected function _construct()
    {
        $this->_init(StoreMappingModel::class, StoreMappingResource::class);
    }
}
