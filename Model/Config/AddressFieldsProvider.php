<?php

namespace GoMage\LightCheckout\Model\Config;

use Magento\Customer\Helper\Address as CustomerAddressHelper;
use Magento\Customer\Model\AttributeMetadataDataProvider;
use Magento\Eav\Api\Data\AttributeInterface;
use Magento\Framework\Serialize\SerializerInterface;
use Magento\Ui\Component\Form\AttributeMapper;

// make ideal
class AddressFieldsProvider
{
    /**
     * @var AddressFieldsProvider
     */
    private $addressFieldsProvider;

    /**
     * @var CheckoutConfigurationsProvider
     */
    private $checkoutConfigurationsProvider;

    /**
     * @var SerializerInterface
     */
    private $serializer;

    /**
     * @var AttributeMetadataDataProvider
     */
    private $attributeMetadataDataProvider;

    /**
     * @var CustomerAddressHelper
     */
    private $customerAddressHelper;

    /**
     * @var AttributeMapper
     */
    private $attributeMapper;

    public function __construct(
        CheckoutConfigurationsProvider $checkoutConfigurationsProvider,
        SerializerInterface $serializer,
        AttributeMetadataDataProvider $attributeMetadataDataProvider,
        CustomerAddressHelper $customerAddressHelper,
        AttributeMapper $attributeMapper
    ) {
        $this->checkoutConfigurationsProvider = $checkoutConfigurationsProvider;
        $this->serializer = $serializer;
        $this->attributeMetadataDataProvider = $attributeMetadataDataProvider;
        $this->customerAddressHelper = $customerAddressHelper;
        $this->attributeMapper = $attributeMapper;
    }

    public function getVisibleAddressAttributes()
    {
        $addressAttributes = $this->getAddressAttributesForFrontend();
        $configAddressAttributes = $this->getAddressAttributesFromConfiguration();

        $sortOrder = 10;
        $isNewRow = true;
        $lastWasWide = true;
        $visibleFields = [];
        foreach ($configAddressAttributes as $configAddressAttributeCode => $configAddressAttribute) {
            foreach ($addressAttributes as $key => $addressAttribute) {
                if ($configAddressAttributeCode === $addressAttribute->getAttributeCode() &&
                    $addressAttribute->getIsVisible()) {
                    $isNewRow = $this->getIsNewRow($lastWasWide, $isNewRow);
                    $addressAttribute
                        ->setIsWide($configAddressAttribute['isWide'])
                        ->setSortOrder($sortOrder)
                        ->setIsNewRow($isNewRow);
                    $visibleFields[] = $addressAttribute;
                    $lastWasWide = $configAddressAttribute['isWide'];
                    $sortOrder += 10;
                    break;
                }
            }
        }

        return $visibleFields;
    }

    public function getAddressAttributesForFrontend()
    {
        $addressFields = [];
        /** @var AttributeInterface[] $collection */
        $collection = $this->attributeMetadataDataProvider->loadAttributesCollection(
            'customer_address',
            'customer_register_address'
        );
        foreach ($collection as $key => $field) {
            if ($field->getIsUserDefined()) {
                continue;
            }
            if ('vat_id' === $field->getAttributeCode() && !$this->customerAddressHelper->isVatAttributeVisible()) {
                continue;
            } elseif ('region' === $field->getAttributeCode()) {
                continue;
            }
            $addressFields[] = $field;
        }

        return $addressFields;
    }

    /**
     * @param $lastWasWide
     * @param $isNewRow
     *
     * @return bool
     */
    private function getIsNewRow($lastWasWide, $isNewRow)
    {
        if ($lastWasWide == true) {
            $isNewRow = true;
        } elseif (!$lastWasWide && $isNewRow) {
            $isNewRow = false;
        } elseif (!$lastWasWide && !$isNewRow) {
            $isNewRow = true;
        }

        return $isNewRow;
    }

    public function getGridAddressAttributes()
    {
        $addressAttributes = $this->getAddressAttributesForAdminGrid();
        $configAddressAttributes = $this->getAddressAttributesFromConfiguration();

        $availableConfigFields = [];
        foreach ($configAddressAttributes as $configAddressAttributeCode => $configAddressAttribute) {
            foreach ($addressAttributes as $key => $addressAttribute) {
                if ($configAddressAttributeCode === $addressAttribute->getAttributeCode()) {
                    $addressAttribute->setIsWide($configAddressAttribute['isWide'])
                        ->setStoreLabel($configAddressAttribute['label']);
                    $availableConfigFields[] = $addressAttribute;
                    unset($addressAttributes[$key]);
                    break;
                }
            }
        }

        return array_merge($availableConfigFields, $addressAttributes);
    }

    public function getAddressAttributesForAdminGrid()
    {
        $addressFields = [];
        /** @var AttributeInterface[] $collection */
        $collection = $this->attributeMetadataDataProvider->loadAttributesCollection(
            'customer_address',
            'customer_register_address'
        );
        foreach ($collection as $key => $field) {
            if ($field->getIsUserDefined()) {
                continue;
            }
            if ('region' === $field->getAttributeCode()) {
                continue;
            }
            $addressFields[] = $field;
        }

        return $addressFields;
    }

    // добавляем лэйблы
    public function getAddressAttributes()
    {
        /** @var AttributeInterface[] $attributes */
        $attributes = $this->attributeMetadataDataProvider->loadAttributesCollection(
            'customer_address',
            'customer_register_address'
        );
        $configAddressAttributes = $this->getAddressAttributesFromConfiguration();

        $elements = [];
        foreach ($attributes as $attribute) {
            $code = $attribute->getAttributeCode();
            if ($attribute->getIsUserDefined()) {
                continue;
            }
            if (!empty($configAddressAttributes[$code]['label'])) {
                $attribute->setData('store_label', $configAddressAttributes[$code]['label']);
            }
            $elements[$code] = $this->attributeMapper->map($attribute);
            if (isset($elements[$code]['label'])) {
                $label = $elements[$code]['label'];
                $elements[$code]['label'] = __($label);
            }
        }

        return $elements;
    }

    public function getAddressAttributesFromConfiguration()
    {
        try {
            $config = '{"prefix":{"sortOrder":"10","label":"Name Prefix mk","isWide":true},"firstname":{"sortOrder":"40","label":"First Name","isWide":false},"middlename":{"sortOrder":"30","label":"Middle Name\/Initial","isWide":true},"lastname":{"sortOrder":"40","label":"Last Name","isWide":false},"suffix":{"sortOrder":"50","label":"Name Suffix","isWide":true},"company":{"sortOrder":"60","label":"Company","isWide":true},"street":{"sortOrder":"70","label":"Street Address Custom","isWide":true},"country_id":{"sortOrder":"80","label":"Country","isWide":false},"region":{"sortOrder":"90","label":"State\/Province","isWide":true},"region_id":{"sortOrder":"90","label":"State ha-ha Province","isWide":false},"city":{"sortOrder":"100","label":"City","isWide":false},"postcode":{"sortOrder":"110","label":"Zip\/Postal Code","isWide":false},"telephone":{"sortOrder":"120","label":"Phone Number ha-ha","isWide":true},"fax":{"sortOrder":"130","label":"Fax","isWide":true},"vat_id":{"sortOrder":"140","label":"VAT Number mk-mkm-mk","isWide":true}}';
            $configAddressAttributes = $this->serializer->unserialize(
//                $this->checkoutConfigurationsProvider->getAddressFieldsForm()
                $config
            );
        } catch (\InvalidArgumentException $e) {
            $configAddressAttributes = [];
        }
        return $configAddressAttributes;
    }
}
