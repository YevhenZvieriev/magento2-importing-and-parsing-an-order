<?php

declare(strict_types=1);

namespace Jenyamba\ParseXml\Api;

interface StoreMappingRepositoryInterface
{

    public function getByXmlValue($xmlValue);

    /**
     * Get store code by XML value
     * @param $xmlValue
     * @return mixed
     */
    public function getStoreCodeByXmlValue($xmlValue);
}
