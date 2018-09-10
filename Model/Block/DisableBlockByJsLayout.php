<?php

namespace GoMage\LightCheckout\Model\Block;

use GoMage\LightCheckout\Model\Config\CheckoutConfigurationsProvider;

/**
 * Unset blocks according to configuration.
 */
class DisableBlockByJsLayout
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
            unset($jsLayout["components"]["checkout"]["children"]["payment"]["children"]["afterMethods"]
                ["children"]["discount"]);
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
            unset($jsLayout["components"]["checkout"]["children"]["sidebar"]["children"]["summary"]
                ["children"]["cart_items"]["children"]["details"]["children"]["delete_item"]);
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
            unset($jsLayout["components"]["checkout"]["children"]["sidebar"]["children"]["summary"]
                ["children"]["cart_items"]["children"]["details"]["children"]["increase_item_qty"]);
            unset($jsLayout["components"]["checkout"]["children"]["sidebar"]["children"]["summary"]
                ["children"]["cart_items"]["children"]["details"]["children"]["decrease_item_qty"]);
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
            unset($jsLayout["components"]["checkout"]["children"]["deliveryDate"]);
        }

        return $jsLayout;
    }
}
