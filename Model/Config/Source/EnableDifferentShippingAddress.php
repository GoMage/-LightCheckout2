<?php

namespace GoMage\LightCheckout\Model\Config\Source;

class EnableDifferentShippingAddress
{
    /**
     * @inheritdoc
     */
    public function toOptionArray()
    {
        return array(
            array('value' => 0, 'label' => __('No')),
            array('value' => 1, 'label' => __('Yes, checkbox is checked')),
            array('value' => 2, 'label' => __('Yes, checkbox is unchecked')),
        );
    }
}
