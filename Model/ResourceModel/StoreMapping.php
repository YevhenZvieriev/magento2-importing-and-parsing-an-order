<?php

declare(strict_types=1);

namespace Jenyamba\ParseXml\Model\ResourceModel;

use Magento\Framework\Exception\LocalizedException;
use Magento\Framework\Model\ResourceModel\Db\AbstractDb;

class StoreMapping extends AbstractDb
{
    protected function _construct()
    {
        $this->_init('store_mapping', 'item_id');
    }

    /**
     * @param $xmlValue
     * @return string
     * @throws LocalizedException
     */
    public function getByXmlValue($xmlValue)
    {
        $connection = $this->getConnection();

        $select = $connection->select()
            ->from($this->getMainTable(), ['store_code'])
            ->where('xml_value = ?', $xmlValue)
            ->limit(1);

        return $connection->fetchOne($select);
    }
}
