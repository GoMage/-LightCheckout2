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
     * @param Session $checkoutSession
     * @param DeliveryDateSaverToQuote $deliveryDateSaverToQuote
     */
    public function __construct(
        Session $checkoutSession,
        DeliveryDateSaverToQuote $deliveryDateSaverToQuote
    ) {
        $this->checkoutSession = $checkoutSession;
        $this->deliveryDateSaverToQuote = $deliveryDateSaverToQuote;
    }

    /**
     * @inheritdoc
     */
    public function saveAdditionalInformation($additionInformation)
    {
        $this->checkoutSession->setAdditionalInformation($additionInformation);

        $this->deliveryDateSaverToQuote->execute($additionInformation);

        return true;
    }
}
