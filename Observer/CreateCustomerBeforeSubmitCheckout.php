<?php

namespace GoMage\LightCheckout\Observer;

use Magento\Checkout\Model\Session;
use Magento\Checkout\Model\Type\Onepage;
use Magento\Customer\Api\AccountManagementInterface;
use Magento\Framework\Api\DataObjectHelper;
use Magento\Framework\Event\Observer;
use Magento\Framework\Event\ObserverInterface;
use Magento\Quote\Model\CustomerManagement;

class CreateCustomerBeforeSubmitCheckout implements ObserverInterface
{
    /**
     * @var Session
     */
    private $checkoutSession;

    /**
     * @var AccountManagementInterface
     */
    private $accountManagement;

    /**
     * @var CustomerManagement
     */
    private $customerManagement;

    /**
     * @var DataObjectHelper
     */
    private $dataObjectHelper;

    /**
     * @param Session $checkoutSession
     * @param AccountManagementInterface $accountManagement
     * @param \Magento\Quote\Model\CustomerManagement $customerManagement
     * @param DataObjectHelper $dataObjectHelper
     */
    public function __construct(
        Session $checkoutSession,
        AccountManagementInterface $accountManagement,
        CustomerManagement $customerManagement,
        DataObjectHelper $dataObjectHelper
    ) {
        $this->checkoutSession = $checkoutSession;
        $this->accountManagement = $accountManagement;
        $this->customerManagement = $customerManagement;
        $this->dataObjectHelper = $dataObjectHelper;
    }

    /**
     * @param Observer $observer
     *
     * @return void
     */
    public function execute(Observer $observer)
    {
        /** @var \Magento\Quote\Model\Quote $quote */
        $quote = $observer->getEvent()->getQuote();

        $additionalData = $this->checkoutSession->getAdditionalInformation();

        if (isset($additionalData['password']) && $additionalData['password']) {
            $quote->setCheckoutMethod(Onepage::METHOD_REGISTER)
                ->setCustomerIsGuest(false)
                ->setCustomerGroupId(null)
                ->setPasswordHash($this->accountManagement->getPasswordHash($additionalData['password']));

            $billingAddress = $quote->getBillingAddress();
            $shippingAddress = $quote->isVirtual() ? null : $quote->getShippingAddress();

            $customer = $quote->getCustomer();
            $dataArray = $billingAddress->getData();

            $this->dataObjectHelper->populateWithArray(
                $customer,
                $dataArray,
                '\Magento\Customer\Api\Data\CustomerInterface'
            );

            $quote->setCustomer($customer);

            // Create new customer with entered password and email
            $this->customerManagement->populateCustomerInfo($quote);

            $customerBillingData = $billingAddress->exportCustomerAddress();
            $customerBillingData->setIsDefaultBilling(true)->setData('should_ignore_validation', true);

            if ($shippingAddress) {
                $customerShippingData = $shippingAddress->exportCustomerAddress();
                $customerShippingData->setIsDefaultShipping(true)->setData('should_ignore_validation', true);
                $shippingAddress->setCustomerAddressData($customerShippingData);

                $quote->addCustomerAddress($customerShippingData);
            } else {
                $customerBillingData->setIsDefaultShipping(true);
            }
            $billingAddress->setCustomerAddressData($customerBillingData);
            $quote->addCustomerAddress($customerBillingData);

            //add customerId to addresses.
            if ($quote->getCustomerId()) {
                $customerId = $quote->getCustomerId();
                $billingAddress->setCustomerId($customerId);
                if ($shippingAddress) {
                    $shippingAddress->setCustomerId($customerId);
                }
            }

            $this->checkoutSession->setAdditionalInformation(['password' => null]);
        }
    }
}
