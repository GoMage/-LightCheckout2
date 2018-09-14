<?php

namespace GoMage\LightCheckout\Model\Config\Source;

use Magento\Framework\Data\OptionSourceInterface;

class VerificationSystem implements OptionSourceInterface
{
    CONST VIES = 0;
    CONST ISVAT = 1;

    /**
     * @inheritdoc
     */
    public function toOptionArray()
    {
        return [
            ['value' => self::VIES, 'label' => __('VIES')],
            ['value' => self::ISVAT, 'label' => __('Isvat')],
        ];
    }
}
