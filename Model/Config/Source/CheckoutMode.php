<?php

namespace GoMage\LightCheckout\Model\Config\Source;

class CheckoutMode
{
    /**
     * @inheritdoc
     */
    public function toOptionArray()
    {
        return [
            ['value' => 0, 'label' => __('Registered and guest customers')],
            ['value' => 1, 'label' => __('Only registered customers')],
            ['value' => 2, 'label' => __('Only guest customers')],
        ];
    }
}
