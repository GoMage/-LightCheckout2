<?php

namespace GoMage\LightCheckout\Model\Config\Source;

use Magento\Framework\Data\OptionSourceInterface;

class DisplayVatTaxField implements OptionSourceInterface
{
    const BILLING_ADDRESS = 1;
    const SHIPPING_ADDRESS = 2;
    const BILLING_SHIPPING_ADDRESS = 3;

    /**
     * @inheritdoc
     */
    public function toOptionArray()
    {
        return [
            ['value' => self::BILLING_ADDRESS, 'label' => __('Billing Address')],
            ['value' => self::SHIPPING_ADDRESS, 'label' => __('Shipping Address')],
            ['value' => self::BILLING_SHIPPING_ADDRESS, 'label' => __('Billing and Shipping Address')],
        ];
    }
}
