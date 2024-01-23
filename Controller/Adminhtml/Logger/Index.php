<?php

declare(strict_types=1);

namespace Jenyamba\ParseXml\Controller\Adminhtml\Logger;

use Magento\Backend\App\Action;
use Magento\Framework\App\Action\HttpGetActionInterface;
use Magento\Framework\Controller\ResultFactory;
use Magento\Framework\View\Result\Page;

class Index extends Action implements HttpGetActionInterface
{
    /**
     * @return Page
     */
    public function execute(): Page
    {
        /** @var Page $resultPage */
        $resultPage = $this->resultFactory->create(ResultFactory::TYPE_PAGE);
        $resultPage->setActiveMenu('Jenyamba_ParseXml::item');
        $resultPage->getConfig()->getTitle()->prepend(__('Logger Grid'));
        return $resultPage;
    }
}
