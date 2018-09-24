<?php

namespace GoMage\LightCheckout\Model\Config\CheckoutAddressFieldsSorting;

use GoMage\LightCheckout\Model\Config\CheckoutConfigurationsProvider;
use Magento\Customer\Helper\Address;
use Magento\Customer\Model\AttributeMetadataDataProvider;
use Magento\Eav\Api\Data\AttributeInterface;

class FieldsProvider
{
    /**
     * @var AttributeMetadataDataProvider
     */
    private $attributeMetadataDataProvider;

    /**
     * @var Address
     */
    private $address;

    /**
     * @var CheckoutConfigurationsProvider
     */
    private $checkoutConfigurationsProvider;

    /**
     * @var FieldsDataTransferObjectFactory
     */
    private $fieldsDataTransferObjectFactory;

    /**
     * @param AttributeMetadataDataProvider $attributeMetadataDataProvider
     * @param Address $address
     * @param CheckoutConfigurationsProvider $checkoutConfigurationsProvider
     * @param FieldsDataTransferObjectFactory $fieldsDataTransferObjectFactory
     */
    public function __construct(
        AttributeMetadataDataProvider $attributeMetadataDataProvider,
        Address $address,
        CheckoutConfigurationsProvider $checkoutConfigurationsProvider,
        FieldsDataTransferObjectFactory $fieldsDataTransferObjectFactory
    ) {
        $this->attributeMetadataDataProvider = $attributeMetadataDataProvider;
        $this->address = $address;
        $this->checkoutConfigurationsProvider = $checkoutConfigurationsProvider;
        $this->fieldsDataTransferObjectFactory = $fieldsDataTransferObjectFactory;
    }

    /**
     * @return FieldsDataTransferObject
     */
    public function get()
    {
        $fieldsDataTransferObject = $this->fieldsDataTransferObjectFactory->create();
        $notVisibleFields = [];
        $visibleFields = [];

        /** @var AttributeInterface[] $collection */
        $collection = $this->attributeMetadataDataProvider->loadAttributesCollection(
            'customer_address',
            'customer_register_address'
        );
        foreach ($collection as $key => $field) {
            if (!$this->isAddressAttributeVisible($field)) {
                continue;
            }
            $notVisibleFields[] = $field;
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
            $notVisibleFields[] = $field;
        }

        $fieldsConfig = json_decode($this->checkoutConfigurationsProvider->getAddressFieldsForm(), true);
        $sortOrder = 1;
        $isNewRow = true;
        $lastWasWide = true;
        foreach ($fieldsConfig as $fieldConfig) {
            foreach ($notVisibleFields as $key => $visibleField) {
                if ($fieldConfig['code'] == $visibleField->getAttributeCode()) {
                    $isNewRow = $this->getIsNewRow($lastWasWide, $isNewRow);
                    $visibleField->setIsWide($fieldConfig['isWide'])
                        ->setSortOrder($sortOrder)
                        ->setIsNewRow($isNewRow);
                    $visibleFields[] = $visibleField;
                    unset($notVisibleFields[$key]);
                    $lastWasWide = $fieldConfig['isWide'];
                    $sortOrder += 5;
                    break;
                }
            }
        }

        $fieldsDataTransferObject->setVisibleFields($visibleFields);
        $fieldsDataTransferObject->setNotVisibleFields($notVisibleFields);

        return $fieldsDataTransferObject;
    }

    /**
     * Check if address attribute can be visible on frontend
     *
     * @param $attribute
     *
     * @return bool|null|string
     */
    private function isAddressAttributeVisible($attribute)
    {
        $code = $attribute->getAttributeCode();
        $result = $attribute->getIsVisible();
        switch ($code) {
            case 'vat_id':
                $result = $this->address->isVatAttributeVisible();
                break;
            case 'region':
                $result = false;
                break;
        }

        return $result;
    }

    /**
     * @param AttributeInterface $attribute
     *e
     * @return bool
     */
    private function isCustomerAttributeVisible(AttributeInterface $attribute)
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
}
