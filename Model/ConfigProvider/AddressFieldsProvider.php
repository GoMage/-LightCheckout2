<?php

namespace GoMage\LightCheckout\Model\ConfigProvider;

use Magento\Customer\Helper\Address as AddressHelper;
use Magento\Customer\Model\AttributeMetadataDataProvider;
use Magento\Eav\Api\Data\AttributeInterface;

class AddressFieldsProvider
{
    /**
     * @var AttributeMetadataDataProvider
     */
    private $attributeMetadataDataProvider;

    /**
     * @var AddressHelper
     * */
    private $addressHelper;

    /**
     * @param AttributeMetadataDataProvider $attributeMetadataDataProvider
     * @param AddressHelper $addressHelper
     */
    public function __construct(
        AttributeMetadataDataProvider $attributeMetadataDataProvider,
        AddressHelper $addressHelper
    ) {
        $this->attributeMetadataDataProvider = $attributeMetadataDataProvider;
        $this->addressHelper = $addressHelper;
    }

    /**
     * @return array
     */
    public function get()
    {
        $availableFields = [];

        /** @var AttributeInterface[] $collection */
        $collection = $this->attributeMetadataDataProvider->loadAttributesCollection(
            'customer_address',
            'customer_register_address'
        );
        foreach ($collection as $key => $field) {
            if (!$this->isAddressAttributeVisible($field)) {
                continue;
            }
            $availableFields[] = $field;
        }

        /** @var AttributeInterface[] $collection */
        $collection = $this->attributeMetadataDataProvider->loadAttributesCollection(
            'customer',
            'customer_account_create'
        );
        foreach ($collection as $key => $field) {
            if (!$this->isCustomerAttributeVisible($field)) {
                continue;
            }
            $availableFields[] = $field;
        }

        $fieldsNames = [];
        foreach ($availableFields as $availableField) {
            $fieldsNames[] = $availableField->getAttributeCode();
        }

        return $fieldsNames;
    }

    /**
     * Check if address attribute should be visible on frontend.
     *
     * @param $attribute
     *
     * @return bool
     */
    public function isAddressAttributeVisible($attribute)
    {
        $code = $attribute->getAttributeCode();
        $result = $attribute->getIsVisible();
        switch ($code) {
            case 'vat_id':
                $result = $this->addressHelper->isVatAttributeVisible();
                break;
            case 'region':
                $result = false;
                break;
        }

        return $result;
    }

    /**
     * Check if customer attribute should be visible on frontend.
     *
     * @param AttributeInterface $attribute
     *
     * @return bool
     */
    public function isCustomerAttributeVisible($attribute)
    {
        $code = $attribute->getAttributeCode();
        if (in_array($code, ['gender', 'taxvat', 'dob'])) {
            return $attribute->getIsVisible();
        } else {
            if (!$attribute->getIsUserDefined()) {
                return false;
            }
        }

        return true;
    }
}
