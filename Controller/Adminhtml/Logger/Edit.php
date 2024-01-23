<?php

declare(strict_types=1);

namespace Jenyamba\ParseXml\Controller\Adminhtml\Logger;

use Magento\Backend\App\Action;
use Magento\Framework\App\Action\HttpGetActionInterface;
use Magento\Framework\Controller\ResultFactory;
use Magento\Framework\View\Result\Page;

class Edit extends Action implements HttpGetActionInterface
{
    /**
     * @return Page
     */
    public function execute(): Page
    {
        $pageResult = $this->createPageResult();
        $title = $pageResult->getConfig()->getTitle();
        $title->prepend(__('Logger Items'));
        $title->prepend(__('New Item'));
        return $pageResult;
    }

    /**
     * @return Page
     */
    private function createPageResult(): Page
    {
        return $this->resultFactory->create(ResultFactory::TYPE_PAGE);
    }
}
