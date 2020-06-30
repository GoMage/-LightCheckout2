<?php

namespace GoMage\LightCheckout\Plugin;

use GoMage\LightCheckout\Model\ConfigProvider\PaymentMethodsListProvider;
use Magento\Checkout\Model\DefaultConfigProvider;
use Magento\Checkout\Model\Session;
use Magento\Quote\Model\ResourceModel\Quote\Address\CollectionFactory;
use Psr\Log\LoggerInterface;

class DefaultProvider
{
    /**
     * @var Session
     */
    private $session;

    /**
     * @var CollectionFactory
     */
    private $addressFactory;

    /**
     * @var PaymentMethodsListProvider
     */
    private $paymentMethodsListProvider;

    /**
     * @var LoggerInterface
     */
    private $logger;

    /**
     * DefaultProvider constructor.
     * @param Session $session
     * @param CollectionFactory $addressFactory
     * @param PaymentMethodsListProvider $paymentMethodsListProvider
     * @param LoggerInterface $logger
     */
    public function __construct(
        Session $session,
        CollectionFactory $addressFactory,
        PaymentMethodsListProvider $paymentMethodsListProvider,
        LoggerInterface $logger
    )
    {
        $this->addressFactory = $addressFactory;
        $this->session = $session;
        $this->paymentMethodsListProvider = $paymentMethodsListProvider;
        $this->logger = $logger;
    }

    /**
     * @param DefaultConfigProvider $subject
     * @param $result
     * @return mixed
     */
    public function afterGetConfig(DefaultConfigProvider $subject, $result)
    {
        try {
            if (isset($result['customerData']['addresses'])) {
                /** @var \Magento\Quote\Model\ResourceModel\Quote\Address\Collection $addressCollection */
                $addressCollection = $this->addressFactory->create();
                $quoteId = $this->session->getQuote()->getId();
                $addressCollection->addFieldToFilter('quote_id', ['eq' => $quoteId]);
                foreach ($addressCollection as $item) {
                   foreach ($result['customerData']['addresses'] as $key => $address) {
                       if($result['customerData']['addresses'][$key]['id'] == $item->getCustomerAddressId()) {
                           $result['quoteAddressInfo'][$item->getCustomerAddressId()]['addressesType'][$item->getAddressType()] = $item->getAddressType();
                       }
                   }
                }
            }

            // add 'paymentMethods' data if it not added in \Magento\Checkout\Model\DefaultConfigProvider::getConfig
            if (isset($result['paymentMethods'])) {
                if (empty($result['paymentMethods'])) {
                    $quoteId = $this->session->getQuote()->getId();
                    $result['paymentMethods'] = $this->paymentMethodsListProvider->get($quoteId);
                }
            }
        } catch (\Exception $e) {
            $this->logger->critical($e->getMessage(), ['exception' => $e]);
        }

        return $result;
    }
}
