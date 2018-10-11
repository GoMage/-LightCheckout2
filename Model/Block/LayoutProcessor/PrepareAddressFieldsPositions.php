<?php

namespace GoMage\LightCheckout\Model\Block\LayoutProcessor;

use GoMage\LightCheckout\Model\Config\CheckoutAddressFieldsSorting\FieldsProvider;

class PrepareAddressFieldsPositions
{
    /**
     * @var FieldsProvider
     */
    private $fieldsProvider;

    /**
     * @param FieldsProvider $fieldsProvider
     */
    public function __construct(FieldsProvider $fieldsProvider)
    {
        $this->fieldsProvider = $fieldsProvider;
    }

    /**
     * @param array $jsLayout
     *
     * @return array
     */
    public function execute($jsLayout)
    {
        $billingFields = $jsLayout["components"]["checkout"]["children"]["billingAddress"]["children"]
        ["billing-address-fieldset"]["children"];
        $shippingFields = $jsLayout["components"]["checkout"]["children"]["shippingAddress"]["children"]
        ["shipping-address-fieldset"]["children"];

        $preparedBillingFields = $this->prepareByAddressChildren($billingFields);
        $preparedShippingFields = $this->prepareByAddressChildren($shippingFields);

        if (isset($billingFields['createAccount'])) {
            $preparedBillingFields = array_merge(
                $preparedBillingFields,
                ['createAccount' => $billingFields['createAccount']]
            );
        }

        $jsLayout["components"]["checkout"]["children"]["billingAddress"]["children"]["billing-address-fieldset"]
        ["children"] = $preparedBillingFields;
        $jsLayout["components"]["checkout"]["children"]["shippingAddress"]["children"]["shipping-address-fieldset"]
        ["children"] = $preparedShippingFields;

        return $jsLayout;
    }

    /**
     * @param array $fields
     *
     * @return array
     */
    private function prepareByAddressChildren($fields)
    {
        $preparedFields = [];
        $fieldsDataTransferObject = $this->fieldsProvider->get();
        $visibleFields = $fieldsDataTransferObject->getVisibleFields();

        /** @var \Magento\Customer\Model\Attribute $visibleField */
        foreach ($visibleFields as $visibleField) {
            if (isset($fields[$visibleField->getAttributeCode()])) {
                $attributeCode = $visibleField->getAttributeCode();
                $preparedFields[$attributeCode] = $fields[$attributeCode];

                $presentedAddClasses = isset($preparedFields[$attributeCode]['config']['additionalClasses'])
                    ? $preparedFields[$attributeCode]['config']['additionalClasses']
                    : '';

                if (!$visibleField->getIsWide()) {
                    $preparedFields[$attributeCode]['config']['additionalClasses'] = $presentedAddClasses
                        . ' address-half';
                } else {
                    $preparedFields[$attributeCode]['config']['additionalClasses'] = $presentedAddClasses
                        . ' full';
                }

                $presentedAddClasses = isset($preparedFields[$attributeCode]['config']['additionalClasses'])
                    ? $preparedFields[$attributeCode]['config']['additionalClasses']
                    : '';

                if (!$visibleField->getIsNewRow()) {
                    $preparedFields[$attributeCode]['config']['additionalClasses'] = $presentedAddClasses . ' right';
                } else {
                    $preparedFields[$attributeCode]['config']['additionalClasses'] = $presentedAddClasses . ' left';
                }

                $preparedFields[$attributeCode]['sortOrder'] = $visibleField->getSortOrder();
            }
        }

        return $preparedFields;
    }
}
