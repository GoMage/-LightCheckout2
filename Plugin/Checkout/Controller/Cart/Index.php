<?php

namespace GoMage\LightCheckout\Plugin\Checkout\Controller\Cart;

use GoMage\LightCheckout\Model\Config\CheckoutConfigurationsProvider;
use Magento\Checkout\Controller\Cart\Index as CartIndex;
use Magento\Framework\Controller\ResultFactory;
use Magento\Framework\Controller\ResultInterface;

class Index
{
    /**
     * @var ResultFactory
     */
    private $resultFactory;

    /**
     * @var CheckoutConfigurationsProvider
     */
    private $checkoutConfigurationsProvider;

    /**
     * @param ResultFactory $resultFactory
     * @param CheckoutConfigurationsProvider $checkoutConfigurationsProvider
     */
    public function __construct(
        ResultFactory $resultFactory,
        CheckoutConfigurationsProvider $checkoutConfigurationsProvider
    ) {
        $this->resultFactory = $resultFactory;
        $this->checkoutConfigurationsProvider = $checkoutConfigurationsProvider;
    }

    /**
     * Redirect from cart to checkout according to configuration.
     *
     * @param CartIndex $subject
     * @param \Closure $proceed
     *
     * @return ResultInterface
     * @SuppressWarnings(PHPMD.UnusedFormalParameter)
     */
    public function aroundExecute(CartIndex $subject, \Closure $proceed)
    {
        if ($this->checkoutConfigurationsProvider->isLightCheckoutEnabled()
            && $this->checkoutConfigurationsProvider->getIsDisabledCart()
        ) {
            $resultForward = $this->resultFactory->create(ResultFactory::TYPE_FORWARD);
            $result = $resultForward
                ->setModule('lightcheckout')
                ->setController('index')
                ->forward('index');
        } else {
            $result = $proceed($subject);
        }

        return $result;
    }
}
