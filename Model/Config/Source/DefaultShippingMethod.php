<?php

namespace GoMage\LightCheckout\Model\Config\Source;

use Magento\Framework\App\Config\ScopeConfigInterface;
use Magento\Shipping\Model\Config as ShippingConfig;

class DefaultShippingMethod
{
    /**
     * @var ScopeConfigInterface
     */
    private $scopeConfig;

    /**
     * @var ShippingConfig
     */
    private $shippingConfig;

    /**
     * @param ScopeConfigInterface $scopeConfig
     * @param ShippingConfig $shippingConfig
     */
    public function __construct(
        ScopeConfigInterface $scopeConfig,
        ShippingConfig $shippingConfig
    ) {
        $this->scopeConfig = $scopeConfig;
        $this->shippingConfig = $shippingConfig;
    }

    /**
     * @inheritdoc
     */
    public function toOptionArray()
    {
        $shippingMethodsOptionArray = [
            [
                'label' => __('-- Please select --'),
                'value' => '',
            ],
        ];
        $carrierMethodsList = $this->shippingConfig->getActiveCarriers();

        foreach ($carrierMethodsList as $carrierMethodCode => $carrierModel) {
            foreach ($carrierModel->getAllowedMethods() as $shippingMethodCode => $shippingMethodTitle) {
                $shippingMethodsOptionArray[] = [
                    'label' => $this->getShippingMethodLabel($shippingMethodCode, $shippingMethodTitle),
                    'value' => $carrierMethodCode . '_' . $shippingMethodCode,
                ];
            }
        }

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
