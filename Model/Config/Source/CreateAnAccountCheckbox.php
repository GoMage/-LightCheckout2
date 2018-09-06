<?php

namespace GoMage\LightCheckout\Model\Config\Source;

class CreateAnAccountCheckbox
{
    /**
     * @inheritdoc
     */
    public function toOptionArray()
    {
        return array(
            array('value' => 0, 'label' => __('Unchecked')),
            array('value' => 1, 'label' => __('Checked')),
        );
    }
}
