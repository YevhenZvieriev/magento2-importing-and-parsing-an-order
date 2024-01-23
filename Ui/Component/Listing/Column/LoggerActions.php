<?php

declare(strict_types=1);

namespace Jenyamba\ParseXml\Ui\Component\Listing\Column;

use Magento\Framework\Escaper;
use Magento\Framework\UrlInterface;
use Magento\Framework\View\Element\UiComponent\ContextInterface;
use Magento\Framework\View\Element\UiComponentFactory;
use Magento\Ui\Component\Listing\Columns\Column;

class LoggerActions extends Column
{
    private const URL_PATH_EDIT = 'jenyamba_parsexml/logger/edit';

    private const URL_PATH_DELETE = 'jenyamba_parsexml/logger/delete';

    /**
     * @var UrlInterface
     */
    private UrlInterface $urlBuilder;

    /**
     * @var Escaper
     */
    private Escaper $escaper;

    /**
     * @param ContextInterface $context
     * @param UiComponentFactory $uiComponentFactory
     * @param UrlInterface $urlBuilder
     * @param Escaper $escaper
     * @param array $components
     * @param array $data
     */
    public function __construct(
        ContextInterface   $context,
        UiComponentFactory $uiComponentFactory,
        UrlInterface       $urlBuilder,
        Escaper            $escaper,
        array              $components = [],
        array              $data = []
    )
    {
        $this->escaper = $escaper;
        $this->urlBuilder = $urlBuilder;
        parent::__construct($context, $uiComponentFactory, $components, $data);
    }

    /**
     * @param array $dataSource
     * @return array
     */
    public function prepareDataSource(array $dataSource): array
    {
        if (isset($dataSource['data']['items'])) {
            foreach ($dataSource['data']['items'] as &$item) {
                if (isset($item['item_id'])) {
                    $name = $this->getData('name');
                    $item[$name]['edit'] = [
                        'href' => $this->getEditUrl($item),
                        'label' => __('Edit')
                    ];

                    $itemId = $this->escaper->escapeHtml($item['item_id']);
                    $item[$name]['delete'] = [
                        'href' => $this->getDeleteUrl($item),
                        'label' => __('Delete'),
                        'confirm' => [
                            'title' => __('Delete %1', $itemId),
                            'message' => __('Are you sure you want to delete an item id №%1?', $itemId)
                        ],
                        'post' => true
                    ];
                }
            }
        }
        return $dataSource;
    }

    /**
     * @param array $item
     * @return string
     */
    private function getEditUrl(array $item): string
    {
        return $this->urlBuilder->getUrl(self::URL_PATH_EDIT, ['item_id' => $item['item_id']]);
    }

    /**
     * @param array $item
     * @return string
     */
    private function getDeleteUrl(array $item): string
    {
        return $this->urlBuilder->getUrl(self::URL_PATH_DELETE, ['item_id' => $item['item_id']]);
    }
}
