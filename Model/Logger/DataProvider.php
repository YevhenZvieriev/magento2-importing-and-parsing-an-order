<?php

declare(strict_types=1);

namespace Jenyamba\ParseXml\Model\Logger;

use Jenyamba\ParseXml\Model\Logger;
use Jenyamba\ParseXml\Model\LoggerFactory;
use Jenyamba\ParseXml\Model\ResourceModel\Logger as LoggerResource;
use Jenyamba\ParseXml\Model\ResourceModel\Logger\CollectionFactory;
use Magento\Framework\App\RequestInterface;
use Magento\Ui\DataProvider\Modifier\PoolInterface;
use Magento\Ui\DataProvider\ModifierPoolDataProvider;

class DataProvider extends ModifierPoolDataProvider
{
    /**
     * @var array
     */
    private array $loadedData;

    /**
     * @var LoggerFactory
     */
    private LoggerFactory $loggerFactory;

    /**
     * @var LoggerResource
     */
    private LoggerResource $resource;

    /**
     * @var RequestInterface
     */
    private RequestInterface $request;

    /**
     * @param string $name
     * @param string $primaryFieldName
     * @param string $requestFieldName
     * @param CollectionFactory $collectionFactory
     * @param LoggerResource $resource
     * @param LoggerFactory $loggerFactory
     * @param RequestInterface $request
     * @param array $meta
     * @param array $data
     * @param PoolInterface|null $pool
     */
    public function __construct(
        $name,
        $primaryFieldName,
        $requestFieldName,
        CollectionFactory $collectionFactory,
        LoggerResource $resource,
        LoggerFactory $loggerFactory,
        RequestInterface $request,
        array $meta = [],
        array $data = [],
        PoolInterface $pool = null)
    {
        $this->request = $request;
        $this->resource = $resource;
        $this->loggerFactory = $loggerFactory;
        $this->collection = $collectionFactory->create();
        parent::__construct(
            $name,
            $primaryFieldName,
            $requestFieldName,
            $meta,
            $data,
            $pool);
    }

    /**
     * @return array
     */
    public function getData(): array
    {
        if (isset($this->loadedData)) {
            return $this->loadedData;
        }

        $item = $this->getCurrentItem();
        $this->loadedData[$item->getId()] = $item->getData();

        return $this->loadedData;
    }

    /**
     * @return Logger
     */
    private function getCurrentItem(): Logger
    {
        $itemId = $this->getItemId();
        $item = $this->loggerFactory->create();
        if (!$itemId) {
            return $item;
        }
        $this->resource->load($item, $itemId);
        return $item;
    }

    /**
     * @return int
     */
    private function getItemId(): int
    {
        return (int)$this->request->getParam($this->getRequestFieldName());
    }
}
