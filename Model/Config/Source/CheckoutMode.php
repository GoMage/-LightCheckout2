<?php

namespace GoMage\LightCheckout\Model\Config\Source;

class CheckoutMode
{
    /**
     * @inheritdoc
     */
    public function toOptionArray()
    {
        return array(
            array('value' => 0, 'label' => __('Registered and guest customers')),
            array('value' => 1, 'label' => __('Only registered customers')),
            array('value' => 2, 'label' => __('Only guest customers')),
        );
    }
}
