<?php

namespace GoMage\LightCheckout\Model;

use GoMage\LightCheckout\Model\ConfigProvider\PaymentMethodList;
use Magento\Checkout\Model\ConfigProviderInterface;
use Magento\Checkout\Model\Session as CheckoutSession;

class ConfigProvider implements ConfigProviderInterface
{
    /**
     * @var CheckoutSession
     */
    private $checkoutSession;
    /**
     * @var PaymentMethodList
     */
    private $paymentMethodsProvider;

    /**
     * @param PaymentMethodList $paymentMethodsProvider
     * @param CheckoutSession $session
     */
    public function __construct(
        PaymentMethodList $paymentMethodsProvider,
        CheckoutSession $session
    ) {
        $this->paymentMethodsProvider = $paymentMethodsProvider;
        $this->checkoutSession = $session;
    }

    /**
     * {@inheritdoc}
     */
    public function getConfig()
    {
        $quoteId = $this->checkoutSession->getQuoteId();
        $config = [
            'paymentMethods' => $this->paymentMethodsProvider->getPaymentMethods($quoteId),
        ];

        return $config;
    }
}
