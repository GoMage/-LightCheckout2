<?php

namespace GoMage\LightCheckout\Plugin\Checkout\Controller\Index;

use GoMage\LightCheckout\Model\Configuration\Config;
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
     * @var Config
     */
    private $config;

    /**
     * @param ResultFactory $resultFactory
     * @param Config $config
     */
    public function __construct(ResultFactory $resultFactory, Config $config)
    {
        $this->resultFactory = $resultFactory;
        $this->config = $config;
    }

    /**
     * Perform forward to one step checkout action if needed
     *
     * @param CheckoutIndex $subject
     * @param \Closure $proceed
     *
     * @return ResultInterface
     * @SuppressWarnings(PHPMD.UnusedFormalParameter)
     */
    public function aroundExecute(CheckoutIndex $subject, \Closure $proceed)
    {
        if ($this->config->isLightCheckoutEnabled()) {
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
