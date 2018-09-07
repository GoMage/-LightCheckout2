<?php

namespace GoMage\LightCheckout\Plugin\Checkout\Controller\Index;

use GoMage\LightCheckout\Model\Config\CheckoutConfigurationsProvider;
use GoMage\LightCheckout\Model\IsEnableLightCheckoutForDevice;
use Magento\Checkout\Controller\Index\Index as CheckoutIndex;
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
     * @var IsEnableLightCheckoutForDevice
     */
    private $isEnableLightCheckoutForDevice;

    /**
     * @param ResultFactory $resultFactory
     * @param CheckoutConfigurationsProvider $checkoutConfigurationsProvider
     * @param IsEnableLightCheckoutForDevice $isEnableLightCheckoutForDevice
     */
    public function __construct(
        ResultFactory $resultFactory,
        CheckoutConfigurationsProvider $checkoutConfigurationsProvider,
        IsEnableLightCheckoutForDevice $isEnableLightCheckoutForDevice
    ) {
        $this->resultFactory = $resultFactory;
        $this->checkoutConfigurationsProvider = $checkoutConfigurationsProvider;
        $this->isEnableLightCheckoutForDevice = $isEnableLightCheckoutForDevice;
    }

    /**
     * Forward to Light Checkout if it is needed.
     *
     * @param CheckoutIndex $subject
     * @param \Closure $proceed
     *
     * @return ResultInterface
     * @SuppressWarnings(PHPMD.UnusedFormalParameter)
     */
    public function aroundExecute(CheckoutIndex $subject, \Closure $proceed)
    {
        if ($this->checkoutConfigurationsProvider->isLightCheckoutEnabled()
            && $this->isEnableLightCheckoutForDevice->execute()
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
