<?php

namespace GoMage\LightCheckout\Plugin\Checkout\Controller\Index;

use GoMage\LightCheckout\Model\Config\CheckoutConfigurationsProvider;
use \GoMage\LightCheckout\Model\IsEnableLightCheckout;
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
     * @var IsEnableLightCheckout
     */
    private $isEnableLightCheckout;

    /**
     * Index constructor.
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
     * @param CheckoutIndex $subject
     * @param \Closure $proceed
     * @return mixed
     */
    public function aroundExecute(CheckoutIndex $subject, \Closure $proceed)
    {
        if ($this->isEnableLightCheckout->execute()) {
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
