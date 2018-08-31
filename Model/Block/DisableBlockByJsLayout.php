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
        $isEnabledDiscountCodes = $this->checkoutConfigProvider->getIsEnabledDiscountCodes();

        if (!$isEnabledDiscountCodes) {
            unset($jsLayout["components"]["checkout"]["children"]["payment"]["children"]["afterMethods"]
                ["children"]["discount"]);
        }

        return $jsLayout;
    }
}
