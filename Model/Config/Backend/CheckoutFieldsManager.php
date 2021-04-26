<?php

namespace GoMage\LightCheckout\Model\Config\Backend;

use Magento\Config\Block\System\Config\Form;
use Magento\Framework\App\Cache\TypeListInterface;
use Magento\Framework\App\Config\ScopeConfigInterface;
use Magento\Framework\App\Config\Value;
use Magento\Framework\Data\Collection\AbstractDb;
use Magento\Framework\Model\Context;
use Magento\Framework\Model\ResourceModel\AbstractResource;
use Magento\Framework\Registry;
use Magento\Framework\Serialize\SerializerInterface;
use Magento\Store\Model\StoreManagerInterface;
use Magento\Eav\Model\Config;
use Magento\Framework\App\Config\ConfigResource\ConfigInterface as ConfigurationSettings;
use Magento\Customer\Helper\Address;
use GoMage\LightCheckout\Model\Config\AddressFieldsProvider;

class CheckoutFieldsManager extends Value
{


    /**
     * @var SerializerInterface
     */
    private $serializer;

    /**
     * @var StoreManagerInterface
     */
    private $storeManager;

    /**
     * @var Config
     */
    private $eavConfig;

    /**
     * @var ConfigurationSettings
     */
    private $configurationSettings;

    /**
     * CheckoutFieldsManager constructor.
     * @param SerializerInterface $serializer
     * @param StoreManagerInterface $storeManager
     * @param Config $eavConfig
     * @param ConfigurationSettings $configurationSettings
     * @param Context $context
     * @param Registry $registry
     * @param ScopeConfigInterface $config
     * @param TypeListInterface $cacheTypeList
     * @param AbstractResource|null $resource
     * @param AbstractDb|null $resourceCollection
     * @param array $data
     */
    public function __construct(
        SerializerInterface $serializer,
        StoreManagerInterface $storeManager,
        Config $eavConfig,
        ConfigurationSettings $configurationSettings,
        Context $context,
        Registry $registry,
        ScopeConfigInterface $config,
        TypeListInterface $cacheTypeList,
        AbstractResource $resource = null,
        AbstractDb $resourceCollection = null,
        array $data = []
    ) {
        parent::__construct($context, $registry, $config, $cacheTypeList, $resource, $resourceCollection, $data);
        $this->serializer = $serializer;
        $this->storeManager = $storeManager;
        $this->eavConfig = $eavConfig;
        $this->configurationSettings = $configurationSettings;
    }

    /**
     * @inheritdoc
     */
    public function beforeSave()
    {
        $result = parent::beforeSave();

        $atributesToSave = $this->serializer->unserialize($this->getValue());
        foreach ($atributesToSave as $attribute) {
            if (in_array($attribute['attributeCode'], AddressFieldsProvider::$requiredFields)) {
                continue;
            } elseif (in_array($attribute['attributeCode'], AddressFieldsProvider::$allowToEditFields)) {
                if ($this->getScope() == 'websites') {
                    $website = $this->storeManager->getWebsite($this->getScopeCode());
                    $dataFieldPrefix = 'scope_';
                } else {
                    $website = null;
                    $dataFieldPrefix = '';
                }
                // vat_id is always optional in the core configuration settings (only visible or not visible)
                if ($attribute['attributeCode'] === 'vat_id') {
                    $this->configurationSettings->saveConfig(
                        Address::XML_PATH_VAT_FRONTEND_VISIBILITY,
                        $attribute['is_enabled'],
                        $website ? Form::SCOPE_WEBSITES : ScopeConfigInterface::SCOPE_TYPE_DEFAULT,
                        $website ? $website->getId() : 0
                    );
                } else {
                    $attributeObject = $this->eavConfig->getAttribute('customer_address', $attribute['attributeCode']);
                    if ($website) {
                        $attributeObject->setWebsite($website);
                        $attributeObject->load($attributeObject->getId());
                    }
                    // middlename is always optional in the core configuration settings (only visible or not visible)
                    if ($attribute['attributeCode'] === 'middlename') {
                        $attributeObject->setData($dataFieldPrefix . 'is_visible', $attribute['is_enabled']);
                    } else {
                        $attributeObject->setData($dataFieldPrefix . 'is_required', $attribute['is_required']);
                        $attributeObject->setData($dataFieldPrefix . 'is_visible', $attribute['is_enabled']);
                    }
                    $attributeObject->save();

                    // saving in core_config_data table
                    if ($attribute['is_enabled'] && $attribute['is_required']) {
                        $configValue = 'req';
                    } elseif ($attribute['is_enabled'] && !$attribute['is_required']) {
                        $configValue = 'opt';
                    } elseif ($attribute['is_enabled'] && $attribute['attributeCode'] === 'middlename') {
                        $configValue = '1';
                    } else {
                        $configValue = '';
                    }
                    $this->configurationSettings->saveConfig(
                        'customer/address/' . $attribute['attributeCode'] . '_show',
                        $configValue,
                        $website ? Form::SCOPE_WEBSITES : ScopeConfigInterface::SCOPE_TYPE_DEFAULT,
                        $website ? $website->getId() : 0
                    );
                }
            } else {
                if ($this->getScope() == 'websites') {
                    $website = $this->storeManager->getWebsite($this->getScopeCode());
                    $dataFieldPrefix = 'scope_';
                } else {
                    $website = null;
                    $dataFieldPrefix = '';
                }
                $attributeObject = $this->eavConfig->getAttribute('customer_address', $attribute['attributeCode']);
                if ($website) {
                    $attributeObject->setWebsite($website);
                    $attributeObject->load($attributeObject->getId());
                }
                $attributeObject->setData($dataFieldPrefix . 'is_required', $attribute['is_required']);
                $attributeObject->setData($dataFieldPrefix . 'is_visible', $attribute['is_enabled']);
                $attributeObject->save();
            }
        }

        return $result;
    }
}
