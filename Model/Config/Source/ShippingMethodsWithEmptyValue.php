<?php

namespace GoMage\LightCheckout\Model\Config\Source;

use Magento\Framework\Data\OptionSourceInterface;

/**
 * Return Options array of shipping methods.
 */
class ShippingMethodsWithEmptyValue implements OptionSourceInterface
{
    /**
     * @var ShippingMethods
     */
    private $shippingMethods;

    /**
     * @param ShippingMethods $shippingMethods
     */
    public function __construct(
        ShippingMethods $shippingMethods
    ) {
        $this->shippingMethods = $shippingMethods;
    }

    /**
     * @inheritdoc
     */
    public function toOptionArray()
    {
        $shippingMethodsOptionArray = array_merge(
            [
                [
                    'label' => __('-- Please select --'),
                    'value' => '',
                ],
            ],
            $this->shippingMethods->toOptionArray()
        );

        return $shippingMethodsOptionArray;
    }

    /**
     * @param string $code
     * @param string $title
     *
     * @return string
     */
    private function getShippingMethodLabel($code, $title)
    {
        $label = $this->scopeConfig->getValue('carriers/' . $code . '/title');

        if (!$label) {
            $label = $code;
        }

        return $label . ' - ' . $title;
    }
}
