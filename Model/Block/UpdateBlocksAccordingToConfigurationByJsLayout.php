<?php

namespace GoMage\LightCheckout\Model\Block;

use GoMage\LightCheckout\Model\Config\CheckoutConfigurationsProvider;

/**
 * Unset blocks according to configuration.
 */
class UpdateBlocksAccordingToConfigurationByJsLayout
{
    /**
     * @var CheckoutConfigurationsProvider
     */
    private $checkoutConfigProvider;

    /**
     * @param CheckoutConfigurationsProvider $checkoutConfigurationsProvider
     */
    public function __construct(CheckoutConfigurationsProvider $checkoutConfigurationsProvider)
    {
        $this->checkoutConfigProvider = $checkoutConfigurationsProvider;
    }

    /**
     * @param array $jsLayout
     *
     * @return array
     */
    public function execute($jsLayout)
    {
        $jsLayout = $this->disableDiscountCodesAccordingToTheConfiguration($jsLayout);
        $jsLayout = $this->disableDeletingItemOnCheckoutAccordingToTheConfiguration($jsLayout);
        $jsLayout = $this->disableChangingQtyOnCheckoutAccordingToTheConfiguration($jsLayout);
        $jsLayout = $this->disableDeliveryDateOnCheckoutAccordingToTheConfiguration($jsLayout);
        $jsLayout = $this->disableAddressFieldsAccordingToTheConfiguration($jsLayout);
        $jsLayout = $this->updateTemplateForPostcodeFieldAccordingToTheConfiguration($jsLayout);

        return $jsLayout;
    }

    /**
     * @param array $jsLayout
     *
     * @return array
     */
    private function disableDiscountCodesAccordingToTheConfiguration($jsLayout)
    {
        $isEnabledDiscountCodes = $this->checkoutConfigProvider->getIsEnabledDiscountCodes();

        if (!$isEnabledDiscountCodes) {
            unset($jsLayout['components']['checkout']['children']['payment']['children']['afterMethods']
                ['children']['discount']);
        }

        return $jsLayout;
    }

    /**
     * @param array $jsLayout
     *
     * @return array
     */
    private function disableDeletingItemOnCheckoutAccordingToTheConfiguration($jsLayout)
    {
        $isEnabledRemoveItemFromCheckout = $this->checkoutConfigProvider->getIsAllowedToRemoveItemFromCheckout();

        if (!$isEnabledRemoveItemFromCheckout) {
            unset($jsLayout['components']['checkout']['children']['sidebar']['children']['summary']
                ['children']['cart_items']['children']['details']['children']['delete_item']);
        }

        return $jsLayout;
    }

    /**
     * @param array $jsLayout
     *
     * @return array
     */
    private function disableChangingQtyOnCheckoutAccordingToTheConfiguration($jsLayout)
    {
        $isEnabledChangeQty = $this->checkoutConfigProvider->getIsAllowedToChangeQty();

        if (!$isEnabledChangeQty) {
            unset($jsLayout['components']['checkout']['children']['sidebar']['children']['summary']
                ['children']['cart_items']['children']['details']['children']['increase_item_qty']);
            unset($jsLayout['components']['checkout']['children']['sidebar']['children']['summary']
                ['children']['cart_items']['children']['details']['children']['decrease_item_qty']);
        }

        return $jsLayout;
    }

    /**
     * @param array $jsLayout
     *
     * @return array
     */
    private function disableDeliveryDateOnCheckoutAccordingToTheConfiguration($jsLayout)
    {
        $isEnabledDeliveryDate = $this->checkoutConfigProvider->getIsEnabledDeliveryDate();

        if (!$isEnabledDeliveryDate) {
            unset($jsLayout['components']['checkout']['children']['deliveryDate']);
        } else {
            $isShowTime = $this->checkoutConfigProvider->getIsShowTime();
            if (!$isShowTime) {
                unset($jsLayout['components']['checkout']['children']['deliveryDate']['children']['selectTime']);
            }
        }

        return $jsLayout;
    }

    /**
     * @param array $jsLayout
     *
     * @return array
     */
    private function disableAddressFieldsAccordingToTheConfiguration($jsLayout)
    {
        $isEnabledAutofill = $this->checkoutConfigProvider->getIsEnabledAutoFillByZipCode();
        $idDisableAddressFields = $this->checkoutConfigProvider->getAutoFillByZipCodeIsDisabledAddressFields();

        if ($isEnabledAutofill && $idDisableAddressFields) {
            $jsLayout['components']['checkout']['children']['billingAddress']['children']
            ['billing-address-fieldset']['children']['region_id']['disabled'] = true;
            $jsLayout['components']['checkout']['children']['billingAddress']['children']
            ['billing-address-fieldset']['children']['city']['disabled'] = true;
            $jsLayout['components']['checkout']['children']['billingAddress']['children']
            ['billing-address-fieldset']['children']['country_id']['disabled'] = true;

            $jsLayout['components']['checkout']['children']['shippingAddress']['children']
            ['shipping-address-fieldset']['children']['region_id']['disabled'] = true;
            $jsLayout['components']['checkout']['children']['shippingAddress']['children']
            ['shipping-address-fieldset']['children']['city']['disabled'] = true;
            $jsLayout['components']['checkout']['children']['shippingAddress']['children']
            ['shipping-address-fieldset']['children']['country_id']['disabled'] = true;
        }
        return $jsLayout;
    }

    /**
     * @param array $jsLayout
     *
     * @return array
     */
    private function updateTemplateForPostcodeFieldAccordingToTheConfiguration($jsLayout)
    {
        if ($this->checkoutConfigProvider->getIsEnabledAutoFillByZipCode()) {
            $jsLayout['components']['checkout']['children']['billingAddress']['children']['billing-address-fieldset']
            ['children']['postcode']['config']['elementTmpl'] = 'GoMage_LightCheckout/element/element-with-blur-template';
            $jsLayout['components']['checkout']['children']['billingAddress']['children']['billing-address-fieldset']
            ['children']['postcode']['component'] = 'GoMage_LightCheckout/js/view/post-code';

            $jsLayout['components']['checkout']['children']['shippingAddress']['children']['shipping-address-fieldset']
            ['children']['postcode']['config']['elementTmpl'] = 'GoMage_LightCheckout/element/element-with-blur-template';
            $jsLayout['components']['checkout']['children']['shippingAddress']['children']['shipping-address-fieldset']
            ['children']['postcode']['component'] = 'GoMage_LightCheckout/js/view/post-code';
        }

        return $jsLayout;
    }
}
