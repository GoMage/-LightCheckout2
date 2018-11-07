<?php

namespace GoMage\LightCheckout\Model\Config\Source;

use Magento\Framework\Data\OptionSourceInterface;

class AddressFieldStateOptions implements OptionSourceInterface
{

    /**
     * @inheritdoc
     */
    public function toOptionArray()
    {
        return [
            ['value' => 0, 'label' => __('No')],
            ['value' => 1, 'label' => __('Yes')],
            ['value' => 2, 'label' => __('Use Magento Settings')],
        ];
    }
}
