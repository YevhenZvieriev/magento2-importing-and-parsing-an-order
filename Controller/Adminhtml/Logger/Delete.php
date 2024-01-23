<?php

declare(strict_types=1);

namespace Jenyamba\ParseXml\Controller\Adminhtml\Logger;

use Magento\Backend\App\Action;
use Magento\Backend\App\Action\Context;
use Magento\Framework\App\Action\HttpPostActionInterface;
use Magento\Framework\Controller\ResultInterface;
use Jenyamba\ParseXml\Model\ResourceModel\Logger as LoggerResource;
use Jenyamba\ParseXml\Model\LoggerFactory;

class Delete extends Action implements HttpPostActionInterface
{
    /**
     * @var LoggerResource
     */
    private LoggerResource $resource;

    /**
     * @var LoggerFactory
     */
    private LoggerFactory $loggerFactory;

    /**
     * @param Context $context
     * @param LoggerResource $resource
     * @param LoggerFactory $loggerFactory
     */
    public function __construct(
        Context        $context,
        LoggerResource $resource,
        LoggerFactory  $loggerFactory
    )
    {
        $this->loggerFactory = $loggerFactory;
        $this->resource = $resource;
        parent::__construct($context);
    }

    /**
     * @return ResultInterface
     */
    public function execute(): ResultInterface
    {
        $itemId = (int)$this->getRequest()->getParam('item_id');
        $resultRedirect = $this->resultRedirectFactory->create();
        if (!$itemId) {
            $this->messageManager->addErrorMessage(__('We can\'t find an item to delete'));
            return $resultRedirect->setPath('*/*/');
        }

        $model = $this->loggerFactory->create();

        try {
            $this->resource->load($model, $itemId);
            $this->resource->delete($model);

            $this->messageManager->addSuccessMessage(__('The item has been deleted.'));
        } catch (\Throwable $exception) {
            $this->messageManager->addErrorMessage($exception->getMessage());
        }
        return $resultRedirect->setPath('*/*/');
    }
}
