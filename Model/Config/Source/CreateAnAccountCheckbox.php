<?php

namespace GoMage\LightCheckout\Model\Config\Source;

class CreateAnAccountCheckbox
{
    /**
     * @inheritdoc
     */
    public function toOptionArray()
    {
        return [
            ['value' => 0, 'label' => __('Unchecked')],
            ['value' => 1, 'label' => __('Checked')],
        ];
    }
}
