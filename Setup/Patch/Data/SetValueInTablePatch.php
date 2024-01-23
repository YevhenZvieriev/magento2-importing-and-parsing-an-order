<?php

declare(strict_types=1);

namespace Jenyamba\ParseXml\Setup\Patch\Data;

use Magento\Framework\Setup\Patch\DataPatchInterface;
use Magento\Framework\Setup\ModuleDataSetupInterface;

class SetValueInTablePatch implements DataPatchInterface
{
    protected $moduleDataSetup;

    public function __construct(ModuleDataSetupInterface $moduleDataSetup)
    {
        $this->moduleDataSetup = $moduleDataSetup;
    }

    public function apply()
    {
        $this->moduleDataSetup->startSetup();
        $this->moduleDataSetup->getConnection()->insert(
            $this->moduleDataSetup->getTable('store_mapping'),
            [
                'xml_value' => 'en_AE',
                'store_code' => 'ksa_en'
            ]
        );
        $this->moduleDataSetup->endSetup();
    }

    public function getAliases()
    {
        return [];
    }

    public static function getDependencies()
    {
        return [];
    }
}
