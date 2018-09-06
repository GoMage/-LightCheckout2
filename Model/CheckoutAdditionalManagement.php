<?php

namespace GoMage\LightCheckout\Model;

use GoMage\LightCheckout\Api\CheckoutAdditionalManagementInterface;
use Magento\Checkout\Model\Session;

class CheckoutAdditionalManagement implements CheckoutAdditionalManagementInterface
{
    /**
     * @var Session
     */
    private $checkoutSession;

    /**
     * @param Session $checkoutSession
     */
    public function __construct(Session $checkoutSession)
    {
        $this->checkoutSession = $checkoutSession;
    }

    /**
     * @inheritdoc
     */
    public function saveAdditionalInformation($additionInformation)
    {
        $this->checkoutSession->setAdditionalInformation($additionInformation);

        return true;
    }
}
