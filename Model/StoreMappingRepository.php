<?php

declare(strict_types=1);

namespace Jenyamba\ParseXml\Model;

use Jenyamba\ParseXml\Api\StoreMappingRepositoryInterface;
use Jenyamba\ParseXml\Model\ResourceModel\StoreMapping as StoreMappingResource;
use Jenyamba\ParseXml\Model\ResourceModel\StoreMapping\CollectionFactory;

class StoreMappingRepository implements StoreMappingRepositoryInterface
{
    /**
     * @var CollectionFactory
     */
    protected CollectionFactory $collectionFactory;

    protected StoreMappingResource $storeMappingResource;

    public function __construct(
        StoreMappingResource $storeMappingResource,
        CollectionFactory    $collectionFactory
    ) {
        $this->storeMappingResource = $storeMappingResource;
        $this->collectionFactory = $collectionFactory;
    }

    public function getByXmlValue($xmlValue): string
    {
        return $this->storeMappingResource->getByXmlValue($xmlValue);
    }

    /**
     * @param  $xmlValue
     * @return mixed|null
     */
    public function getStoreCodeByXmlValue($xmlValue)
    {
        $collection = $this->collectionFactory->create();
        $collection->addFieldToFilter('xml_value', $xmlValue);

        $item = $collection->getFirstItem();
        if (!$item->getId()) {
            return null;
        }

        return $item->getStoreCode();
    }
}
