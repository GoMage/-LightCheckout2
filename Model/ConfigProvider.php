<?php

namespace GoMage\LightCheckout\Model;

use GoMage\LightCheckout\Model\Config\CheckoutConfigurationsProvider;
use GoMage\LightCheckout\Model\ConfigProvider\AddressFieldsProvider;
use GoMage\LightCheckout\Model\ConfigProvider\DeliveryDateConfigProvider;
use GoMage\LightCheckout\Model\ConfigProvider\PasswordSettingProvider;
use GoMage\LightCheckout\Model\ConfigProvider\PaymentMethodsListProvider;
use Magento\Checkout\Model\ConfigProviderInterface;
use Magento\Checkout\Model\Session as CheckoutSession;
use Magento\Quote\Api\Data\CartInterface;
use Magento\Quote\Api\PaymentMethodManagementInterface;
use Magento\Quote\Model\Cart\ShippingMethodConverter;
use Magento\Directory\Helper\Data as DirectoryHelper;
use Magento\Quote\Model\Quote\TotalsCollector;

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
     * @var CheckoutConfigurationsProvider
     */
    private $checkoutConfigurationsProvider;

    /**
     * @var PaymentMethodManagementInterface
     */
    private $paymentMethodManagement;

    /**
     * @var ShippingMethodConverter
     */
    private $shippingMethodConverter;

    /**
     * @var DirectoryHelper
     */
    private $directoryHelper;

    /**
     * @var TotalsCollector
     */
    private $totalsCollector;

    /**
     * @var PasswordSettingProvider
     */
    private $passwordSettingProvider;

    /**
     * @var DeliveryDateConfigProvider
     */
    private $deliveryDateConfigProvider;

    /**
     * @param PaymentMethodsListProvider $paymentMethodsListProvider
     * @param CheckoutSession $session
     * @param AddressFieldsProvider $addressFieldsProvider
     * @param CheckoutConfigurationsProvider $checkoutConfigurationsProvider
     * @param PaymentMethodManagementInterface $paymentMethodManagement
     * @param ShippingMethodConverter $shippingMethodConverter
     * @param DirectoryHelper $directoryHelper
     * @param TotalsCollector $totalsCollector
     * @param PasswordSettingProvider $passwordSettingProvider
     * @param DeliveryDateConfigProvider $deliveryDateConfigProvider
     */
    public function __construct(
        PaymentMethodsListProvider $paymentMethodsListProvider,
        CheckoutSession $session,
        AddressFieldsProvider $addressFieldsProvider,
        CheckoutConfigurationsProvider $checkoutConfigurationsProvider,
        PaymentMethodManagementInterface $paymentMethodManagement,
        ShippingMethodConverter $shippingMethodConverter,
        DirectoryHelper $directoryHelper,
        TotalsCollector $totalsCollector,
        PasswordSettingProvider $passwordSettingProvider,
        DeliveryDateConfigProvider $deliveryDateConfigProvider
    ) {
        $this->paymentMethodsListProvider = $paymentMethodsListProvider;
        $this->checkoutSession = $session;
        $this->addressFieldsProvider = $addressFieldsProvider;
        $this->checkoutConfigurationsProvider = $checkoutConfigurationsProvider;
        $this->paymentMethodManagement = $paymentMethodManagement;
        $this->shippingMethodConverter = $shippingMethodConverter;
        $this->directoryHelper = $directoryHelper;
        $this->totalsCollector = $totalsCollector;
        $this->passwordSettingProvider = $passwordSettingProvider;
        $this->deliveryDateConfigProvider = $deliveryDateConfigProvider;
    }

    /**
     * @inheritdoc
     */
    public function getConfig()
    {
        $quoteId = $this->checkoutSession->getQuoteId();
        $config = [
            'paymentMethods' => $this->paymentMethodsListProvider->get($quoteId),
            'addressFields' => $this->addressFieldsProvider->get(),
            'general' => $this->getGeneralConfig(),
            'passwordSettings' => $this->passwordSettingProvider->get(),
            'registration' => $this->getRegistrationConfig(),
            'deliveryDate' => $this->deliveryDateConfigProvider->get(),
        ];

        return $config;
    }

    /**
     * @param CartInterface $quote
     *
     * @return string|null
     */
    private function getDefaultPaymentMethod(CartInterface $quote)
    {
        $defaultActivePaymentMethod = null;

        if (!$quote->getPayment()->getMethod()) {
            $defaultPaymentMethod = $this->checkoutConfigurationsProvider->getDefaultPaymentMethod();

            $paymentMethods = $this->paymentMethodManagement->getList($quote->getId());

            foreach ($paymentMethods as $paymentMethod) {
                if ($paymentMethod->getCode() === $defaultPaymentMethod) {
                    $defaultActivePaymentMethod = $defaultPaymentMethod;
                    break;
                }
            }
        }

        return $defaultActivePaymentMethod;
    }

    /**
     * @param CartInterface $quote
     *
     * @return string|null
     */
    private function getDefaultShippingMethod(CartInterface $quote)
    {
        $defaultActiveShippingMethod = null;

        /** @var \Magento\Quote\Model\Quote\Address $shippingAddress */
        $shippingAddress = $quote->getShippingAddress();

        if (!$shippingAddress->getCountryId()) {
            $defaultCountryId = $this->directoryHelper->getDefaultCountry();
            $shippingAddress->setCountryId($defaultCountryId)->setCollectShippingRates(true);
        }

        if (!$shippingAddress->getShippingMethod() && !$quote->getIsVirtual()) {
            $defaultShippingMethod = $this->checkoutConfigurationsProvider->getDefaultShippingMethod();

            if ($defaultShippingMethod) {
                $this->totalsCollector->collectAddressTotals($quote, $shippingAddress);

                $allowedShippingMethods = $this->getAllowedShippingMethodsByQuote($quote);

                if (in_array($defaultShippingMethod, $allowedShippingMethods)) {
                    $defaultActiveShippingMethod = $defaultShippingMethod;
                }
            }
        }

        return $defaultActiveShippingMethod;
    }

    /**
     * @param CartInterface $quote
     *
     * @return array
     */
    private function getAllowedShippingMethodsByQuote(CartInterface $quote)
    {
        $allowedShippingMethods = [];
        $currencyCode = $quote->getQuoteCurrencyCode();

        /** @var \Magento\Quote\Model\Quote\Address $shippingAddress */
        $shippingAddress = $quote->getShippingAddress();
        $shippingRates = $shippingAddress->getGroupedAllShippingRates();

        foreach ($shippingRates as $carrierRates) {
            foreach ($carrierRates as $rate) {
                $shippingMethod = $this->shippingMethodConverter->modelToDataObject($rate, $currencyCode);
                $allowedShippingMethods[] = $shippingMethod->getCarrierCode() . '_' . $shippingMethod->getMethodCode();
            }
        }

        return $allowedShippingMethods;
    }

    /**
     * @return array
     */
    private function getGeneralConfig()
    {
        return [
            'pageContent' => $this->checkoutConfigurationsProvider->getPageContent(),
            'enableDifferentShippingAddress' =>
                $this->checkoutConfigurationsProvider->getEnableDifferentShippingAddress(),
            'defaultPaymentMethod' => $this->getDefaultPaymentMethod($this->checkoutSession->getQuote()),
            'defaultShippingMethod' => $this->getDefaultShippingMethod($this->checkoutSession->getQuote()),
        ];
    }

    /**
     * @return array
     */
    private function getRegistrationConfig()
    {
        return [
            'checkoutMode' => $this->checkoutConfigurationsProvider->getCheckoutMode(),
            'isCreateAnAccountCheckboxChecked' => $this->checkoutConfigurationsProvider->getCreateAnAccountCheckbox(),
            'autoRegistration' => $this->checkoutConfigurationsProvider->getIsAutoRegistration(),
        ];
    }
}
