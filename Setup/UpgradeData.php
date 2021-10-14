<?php

namespace GoMage\LightCheckout\Setup;

use GoMage\LightCheckout\Model\Config\CheckoutConfigurationsProvider;
use Magento\Config\Model\ResourceModel\Config;
use Magento\Framework\DB\Ddl\Table;
use Magento\Framework\Setup\ModuleContextInterface;
use Magento\Framework\Setup\ModuleDataSetupInterface;
use Magento\Framework\Setup\SchemaSetupInterface;
use Magento\Framework\Setup\UpgradeDataInterface;
use Magento\Quote\Setup\QuoteSetupFactory;
use Magento\Sales\Setup\SalesSetupFactory;
use Magento\Framework\Serialize\SerializerInterface;
use GoMage\LightCheckout\Model\Config\AddressFieldsProvider;

class UpgradeData implements UpgradeDataInterface
{
    /**
     * @var Config
     */
    private $config;

    /**
     * @var QuoteSetupFactory
     */
    private $quoteSetupFactory;

    /**
     * @var SalesSetupFactory
     */
    private $salesSetupFactory;

    /**
     * @var SerializerInterface
     */
    private $serializer;

    /**
     * @var AddressFieldsProvider
     */
    private $addressFieldsProvider;

    /**
     * UpgradeData constructor.
     * @param Config $config
     * @param QuoteSetupFactory $quoteSetupFactory
     * @param SalesSetupFactory $salesSetupFactory
     * @param SerializerInterface $serializer
     * @param AddressFieldsProvider $addressFieldsProvider
     */
    public function __construct(
        Config $config,
        QuoteSetupFactory $quoteSetupFactory,
        SalesSetupFactory $salesSetupFactory,
        SerializerInterface $serializer,
        AddressFieldsProvider $addressFieldsProvider
    ) {
        $this->quoteSetupFactory = $quoteSetupFactory;
        $this->salesSetupFactory = $salesSetupFactory;
        $this->config = $config;
        $this->serializer = $serializer;
        $this->addressFieldsProvider = $addressFieldsProvider;
    }

    /**
     * {@inheritdoc}
     */
    public function upgrade(ModuleDataSetupInterface $setup, ModuleContextInterface $context)
    {
        $setup->startSetup();

        if (version_compare($context->getVersion(), '1.0.0', '<')) {
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

        if (version_compare($context->getVersion(), '1.0.1', '<')) {
            $this->updateTo101($setup);
        }

        if (version_compare($context->getVersion(), '1.0.2', '<')) {
            $widthTrueSettingArray = ['street', 'telephone'];
            $addressFields = [];
            foreach ($this->addressFieldsProvider->getAddressAttributesForAdminGrid() as $attribute) {
                $addressFields[] = [
                    'attributeCode' => $attribute->getAttributeCode(),
                    'label' => $attribute->getData('store_label'),
                    'isWide' => in_array($attribute->getAttributeCode(), $widthTrueSettingArray),
                    'is_enabled' => '0',
                    'is_required' => '0'
                ];
            }
            $this->config->saveConfig(
                CheckoutConfigurationsProvider::XML_PATH_LIGHT_CHECKOUT_ADDRESS_FIELDS_FORM,
                $this->serializer->serialize($addressFields)
            );
        }

        $setup->endSetup();
    }

    /**
     * @param SchemaSetupInterface $setup
     */
    private function updateTo101(ModuleDataSetupInterface $setup)
    {
        $setup->startSetup();

        $quoteSetup = $this->quoteSetupFactory->create(['setup' => $setup]);
        $quoteSetup
            ->addAttribute(
                'quote',
                'comment_order',
                ['type' => Table::TYPE_TEXT, 'required' => false]
            );


        $salesSetup = $this->salesSetupFactory->create(['setup' => $setup]);
        $salesSetup->addAttribute(
            'order',
            'comment_order',
            ['type' => Table::TYPE_TEXT, 'required' => false]
        );
    }
}
