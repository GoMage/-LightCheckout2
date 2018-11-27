<?php

namespace GoMage\LightCheckout\Setup;

use GoMage\LightCheckout\Model\Config\CheckoutConfigurationsProvider;
use Magento\Config\Model\ResourceModel\Config;
use Magento\Framework\Setup\ModuleContextInterface;
use Magento\Framework\Setup\ModuleDataSetupInterface;
use Magento\Framework\Setup\UpgradeDataInterface;

class UpgradeData implements UpgradeDataInterface
{
    /**
     * @var Config
     */
    private $config;

    /**
     * @param Config $config
     */
    public function __construct(Config $config)
    {
        $this->config = $config;
    }

    /**
     * {@inheritdoc}
     */
    public function upgrade(ModuleDataSetupInterface $setup, ModuleContextInterface $context)
    {
        $setup->startSetup();

        if (version_compare($context->getVersion(), '1.1.4', '<')) {
            $addressFields = [
                [
                    'code' => 'firstname',
                    'isWide' => false,
                ],
                [
                    'code' => 'lastname',
                    'isWide' => false,
                ],
                [
                    'code' => 'postcode',
                    'isWide' => false,
                ],
                [
                    'code' => 'country_id',
                    'isWide' => false,
                ],
                [
                    'code' => 'region_id',
                    'isWide' => false,
                ],
                [
                    'code' => 'city',
                    'isWide' => false,
                ],
                [
                    'code' => 'street',
                    'isWide' => true,
                ],
                [
                    'code' => 'telephone',
                    'isWide' => true,
                ],
            ];
            $this->config->saveConfig(
                CheckoutConfigurationsProvider::XML_PATH_LIGHT_CHECKOUT_ADDRESS_FIELDS_FORM,
                json_encode($addressFields),
                'default',
                0
            );
        }

        $setup->endSetup();
    }
}
