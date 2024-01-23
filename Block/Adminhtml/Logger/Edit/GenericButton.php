<?php

declare(strict_types=1);

namespace Jenyamba\ParseXml\Block\Adminhtml\Logger\Edit;

use Magento\Framework\UrlInterface;

class GenericButton
{
    /**
     * @var UrlInterface
     */
    private UrlInterface $url;

    /**
     * @param UrlInterface $url
     */
    public function __construct(
        UrlInterface $url
    )
    {
        $this->url = $url;
    }

    /**
     * @param string $route
     * @param array $params
     * @return string
     */
    public function getUrl(string $route = '', array $params = []): string
    {
        return $this->url->getUrl($route, $params);
    }
}
