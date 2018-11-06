<?php

namespace GoMage\LightCheckout\Model;

use GoMage\LightCheckout\Api\CheckoutAdditionalManagementInterface;
use GoMage\LightCheckout\Model\CheckoutAdditionalManagement\DeliveryDateSaverToQuote;
use Magento\Checkout\Model\Session;

class CheckoutAdditionalManagement implements CheckoutAdditionalManagementInterface
{
    /**
     * @var Session
     */
    private $checkoutSession;

    /**
     * @var DeliveryDateSaverToQuote
     */
    private $deliveryDateSaverToQuote;

    /**
     * @var CheckoutCustomerSubscriber
     */
    private $checkoutCustomerSubscriber;

    /**
     * @param Session $checkoutSession
     * @param DeliveryDateSaverToQuote $deliveryDateSaverToQuote
     * @param CheckoutCustomerSubscriber $checkoutCustomerSubscriber
     */
    public function __construct(
        Session $checkoutSession,
        DeliveryDateSaverToQuote $deliveryDateSaverToQuote,
        CheckoutCustomerSubscriber $checkoutCustomerSubscriber
    ) {
        $this->checkoutSession = $checkoutSession;
        $this->deliveryDateSaverToQuote = $deliveryDateSaverToQuote;
        $this->checkoutCustomerSubscriber = $checkoutCustomerSubscriber;
    }

    /**
     * @inheritdoc
     */
    public function saveAdditionalInformation($additionInformation)
    {
        $this->checkoutSession->setAdditionalInformation($additionInformation);

        $this->deliveryDateSaverToQuote->execute($additionInformation);

        if (isset($additionInformation['subscribe'])) {
            $email = null;
            if (isset($additionInformation['customerEmail']) && $additionInformation['customerEmail']) {
                $email = $additionInformation['customerEmail'];
            }

            $this->checkoutCustomerSubscriber->execute($email);
        }

        return true;
    }
}
