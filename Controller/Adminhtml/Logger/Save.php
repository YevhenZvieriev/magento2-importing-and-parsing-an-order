<?php

declare(strict_types=1);

namespace Jenyamba\ParseXml\Controller\Adminhtml\Logger;

use Jenyamba\ParseXml\Model\LoggerFactory;
use Jenyamba\ParseXml\Model\ResourceModel\Logger as LoggerResource;
use Magento\Backend\App\Action;
use Magento\Backend\App\Action\Context;
use Magento\Framework\App\Action\HttpPostActionInterface;
use Magento\Framework\Controller\ResultInterface;
use Magento\Framework\Exception\LocalizedException;

class Save extends Action implements HttpPostActionInterface
{
    private LoggerResource $resource;
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
    ) {
        $this->resource = $resource;
        $this->loggerFactory = $loggerFactory;
        parent::__construct($context);
    }

    /**
     * @return ResultInterface
     */
    public function execute(): ResultInterface
    {
        $data = $this->getRequest()->getPostValue();

        $resultRedirect = $this->resultRedirectFactory->create();
        if ($data) {
            $model = $this->loggerFactory->create();
            if (empty($data['item_id'])) {
                $data['item_id'] = null;
            }

            $model->setData($data);

            try {
                $this->resource->save($model);
                $this->messageManager->addSuccessMessage(__('You saved the item.'));
                return $resultRedirect->setPath('*/*/');
            } catch (LocalizedException $exception) {
                $this->messageManager->addExceptionMessage($exception);
            } catch (\Throwable $e) {
                $this->messageManager->addErrorMessage(__('Something went wrong while saving the item.'));
            }
        }
        return $resultRedirect->setPath('*/*/');
    }
}
