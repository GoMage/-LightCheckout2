<?php

namespace GoMage\LightCheckout\Model\Config\Source;

class EnableDifferentShippingAddress
{
    /**
     * @inheritdoc
     */
    public function toOptionArray()
    {
        return [
            ['value' => 0, 'label' => __('No')],
            ['value' => 1, 'label' => __('Yes, checkbox is checked')],
            ['value' => 2, 'label' => __('Yes, checkbox is unchecked')],
        ];
    }
}
