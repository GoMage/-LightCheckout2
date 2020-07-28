<?php

namespace GoMage\LightCheckout\Plugin\Checkout\Controller\Cart;

use GoMage\LightCheckout\Model\Config\CheckoutConfigurationsProvider;
use GoMage\LightCheckout\Model\IsEnableLightCheckout;
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
     * @var IsEnableLightCheckout
     */
    private $isEnableLightCheckout;

    /**
     * @param ResultFactory $resultFactory
     * @param CheckoutConfigurationsProvider $checkoutConfigurationsProvider
     * @param IsEnableLightCheckout $isEnableLightCheckout
     */
    public function __construct(
        ResultFactory $resultFactory,
        CheckoutConfigurationsProvider $checkoutConfigurationsProvider,
        IsEnableLightCheckout $isEnableLightCheckout
    ) {
        $this->resultFactory = $resultFactory;
        $this->checkoutConfigurationsProvider = $checkoutConfigurationsProvider;
        $this->isEnableLightCheckout = $isEnableLightCheckout;
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
        if ($this->isEnableLightCheckout->execute()
            && $this->checkoutConfigurationsProvider->getIsDisabledCart()
        ) {
            $resultForward = $this->resultFactory->create(ResultFactory::TYPE_FORWARD);
            $result = $resultForward
                ->setModule('lightcheckout')
                ->setController('index')
                ->forward('index');
        } else {
            $result = $proceed();
        }

        return $result;
    }
}
