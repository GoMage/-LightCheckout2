<?php

namespace GoMage\LightCheckout\Model\Config\CheckoutAddressFieldsSorting;

use GoMage\LightCheckout\Model\Config\AddressFieldsProvider;
use GoMage\LightCheckout\Model\Config\CheckoutConfigurationsProvider;

class FieldsProvider
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
     * @var FieldsDataTransferObjectFactory
     */
    private $fieldsDataTransferObjectFactory;

    /**
     * @param AddressFieldsProvider $addressFieldsProvider
     * @param CheckoutConfigurationsProvider $checkoutConfigurationsProvider
     * @param FieldsDataTransferObjectFactory $fieldsDataTransferObjectFactory
     */
    public function __construct(
        AddressFieldsProvider $addressFieldsProvider,
        CheckoutConfigurationsProvider $checkoutConfigurationsProvider,
        FieldsDataTransferObjectFactory $fieldsDataTransferObjectFactory
    ) {
        $this->addressFieldsProvider = $addressFieldsProvider;
        $this->checkoutConfigurationsProvider = $checkoutConfigurationsProvider;
        $this->fieldsDataTransferObjectFactory = $fieldsDataTransferObjectFactory;
    }

    /**
     * @return FieldsDataTransferObject
     */
    public function get()
    {
        $fieldsDataTransferObject = $this->fieldsDataTransferObjectFactory->create();
        $visibleFields = [];
        $notVisibleFields = $this->addressFieldsProvider->get();

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
