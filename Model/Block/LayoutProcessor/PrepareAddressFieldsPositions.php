<?php

namespace GoMage\LightCheckout\Model\Block\LayoutProcessor;

use GoMage\LightCheckout\Model\Config\AddressFieldsProvider;
use GoMage\LightCheckout\Model\Config\CheckoutConfigurationsProvider;

class PrepareAddressFieldsPositions
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
     * PrepareAddressFieldsPositions constructor.
     * @param AddressFieldsProvider $addressFieldsProvider
     * @param CheckoutConfigurationsProvider $checkoutConfigurationsProvider
     */
    public function __construct(
        AddressFieldsProvider $addressFieldsProvider,
        CheckoutConfigurationsProvider $checkoutConfigurationsProvider
    ) {
        $this->addressFieldsProvider = $addressFieldsProvider;
        $this->checkoutConfigurationsProvider = $checkoutConfigurationsProvider;
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

        $visibleAddressAttributes = $this->addressFieldsProvider->getVisibleAddressAttributes();
        $preparedBillingFields = $this->prepareByAddressChildren($billingFields, $visibleAddressAttributes);
        $preparedShippingFields = $this->prepareByAddressChildren($shippingFields, $visibleAddressAttributes);

        $jsLayout["components"]["checkout"]["children"]["billingAddress"]["children"]["billing-address-fieldset"]
        ["children"] = $preparedBillingFields;
        $jsLayout["components"]["checkout"]["children"]["shippingAddress"]["children"]["shipping-address-fieldset"]
        ["children"] = $preparedShippingFields;

        return $jsLayout;
    }

    /**
     * @param $fields
     * @param $visibleAddressAttributes
     * @return array
     */
    private function prepareByAddressChildren($fields, $visibleAddressAttributes)
    {
        $preparedFields = [];

        /** @var \Magento\Customer\Model\Attribute $visibleField */
        foreach ($visibleAddressAttributes as $visibleField) {
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

                if ($this->checkoutConfigurationsProvider->getAddressFieldsKeepInside()) {
                    $presentedAddClasses = isset($preparedFields[$attributeCode]['config']['additionalClasses'])
                        ? $preparedFields[$attributeCode]['config']['additionalClasses']
                        : '';
                    if (isset($preparedFields[$attributeCode]['config']['template'])
                        && $preparedFields[$attributeCode]['config']['template'] !== 'ui/group/group'
                    ) {
                        if ($attributeCode === 'region_id') {
                            $preparedFields[$attributeCode]['config']['inputPlaceholder'] =
                                $preparedFields[$attributeCode]['label'];
                        } else {
                            $preparedFields[$attributeCode]['config']['placeholder'] =
                                $preparedFields[$attributeCode]['label'];
                        }

                        $preparedFields[$attributeCode]['label'] = '';
                        $preparedFields[$attributeCode]['config']['additionalClasses'] = $presentedAddClasses . ' inside';
                    }

                    if (isset($preparedFields[$attributeCode]['config']['template'])
                        && $preparedFields[$attributeCode]['config']['template'] === 'ui/group/group'
                    ) {
                        if (isset($preparedFields[$attributeCode]['children'])) {
                            foreach ($preparedFields[$attributeCode]['children'] as $key => $street) {
                                $preparedFields[$attributeCode]['children'][$key]['config']['placeholder'] =
                                    $preparedFields[$attributeCode]['label'];
                                $preparedFields[$attributeCode]['children'][$key]['config']['additionalClasses'] = $presentedAddClasses . ' inside';
                            }
                        }
                        $preparedFields[$attributeCode]['label'] = '';
                    }
                } else {
                    if ($attributeCode === 'region_id') {
                        $preparedFields[$attributeCode]['config']['inputPlaceholder'] = '';
                    }
                }
            }
        }

        return $preparedFields;
    }
}
