<?php

namespace GoMage\LightCheckout\Model;

use GoMage\LightCheckout\Model\ConfigProvider\AddressFieldsProvider;
use GoMage\LightCheckout\Model\ConfigProvider\PaymentMethodsListProvider;
use Magento\Checkout\Model\ConfigProviderInterface;
use Magento\Checkout\Model\Session as CheckoutSession;

class ConfigProvider implements ConfigProviderInterface
{
    /**
     * @var CheckoutSession
     */
    private $checkoutSession;
    /**
     * @var PaymentMethodsListProvider
     */
    private $paymentMethodsListProvider;

    /**
     * @var AddressFieldsProvider
     */
    private $addressFieldsProvider;

    /**
     * @param PaymentMethodsListProvider $paymentMethodsListProvider
     * @param CheckoutSession $session
     */
    public function __construct(
        PaymentMethodsListProvider $paymentMethodsListProvider,
        CheckoutSession $session,
        AddressFieldsProvider $addressFieldsProvider
    ) {
        $this->paymentMethodsListProvider = $paymentMethodsListProvider;
        $this->checkoutSession = $session;
        $this->addressFieldsProvider = $addressFieldsProvider;
    }

    /**
     * {@inheritdoc}
     */
    public function getConfig()
    {
        $quoteId = $this->checkoutSession->getQuoteId();
        $config = [
            'paymentMethods' => $this->paymentMethodsListProvider->get($quoteId),
            'lightCheckoutConfig' => $this->getLightCheckoutConfig(),
        ];

        return $config;
    }

    private function getLightCheckoutConfig()
    {
        return [
            'addressFields' => $this->addressFieldsProvider->get(),
        ];
    }
}
