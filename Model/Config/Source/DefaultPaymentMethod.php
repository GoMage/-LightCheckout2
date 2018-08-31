<?php

namespace GoMage\LightCheckout\Model\Config\Source;

use Magento\Framework\App\Config\ScopeConfigInterface;
use Magento\Payment\Model\Method\Factory as PaymentMethodFactory;

class DefaultPaymentMethod
{
    /**
     * @var ScopeConfigInterface
     */
    private $scopeConfig;

    /**
     * @var PaymentMethodFactory
     */
    private $paymentMethodFactory;

    /**
     * @param ScopeConfigInterface $scopeConfig
     * @param PaymentMethodFactory $paymentMethodFactory
     */
    public function __construct(
        ScopeConfigInterface $scopeConfig,
        PaymentMethodFactory $paymentMethodFactory
    ) {
        $this->scopeConfig = $scopeConfig;
        $this->paymentMethodFactory = $paymentMethodFactory;
    }

    /**
     * @inheritdoc
     */
    public function toOptionArray()
    {
        $options = [['label' => __('-- Please select --'), 'value' => '']];
        $paymentMethodsConfig = $this->scopeConfig->getValue('payment');

        foreach ($paymentMethodsConfig as $code => $methodConfig) {
            if (isset($methodConfig['active']) && $methodConfig['active'] == 1 && isset($methodConfig['model'])) {
                $model = $this->paymentMethodFactory->create($methodConfig['model']);
                $options[] = [
                    'label' => $model->getTitle(),
                    'value' => $code
                ];
            }
        }

        return $options;
    }
}
