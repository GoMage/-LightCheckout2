<?php
namespace GoMage\LightCheckout\Plugin\Checkout\Controller\Index;

use Magento\Framework\Controller\ResultInterface;
use Magento\Framework\Controller\ResultFactory;
use Magento\Framework\Controller\Result\Forward;
use Magento\Checkout\Controller\Index\Index as CheckoutIndex;
use Magento\Framework\Module\Manager;

class Index
{
    /**
     * @var ResultFactory
     */
    private $resultFactory;

    /**
     * @param ResultFactory $resultFactory
     */
    public function __construct(ResultFactory $resultFactory)
    {
        $this->resultFactory = $resultFactory;
    }

    /**
     * Perform forward to one step checkout action if needed
     *
     * @param CheckoutIndex $subject
     * @param \Closure $proceed
     * @return ResultInterface
     * @SuppressWarnings(PHPMD.UnusedFormalParameter)
     */
    public function aroundExecute(CheckoutIndex $subject, \Closure $proceed)
    {
//todo check if light checkout is enabled
        $resultForward = $this->resultFactory->create(ResultFactory::TYPE_FORWARD);
        $result = $resultForward
            ->setModule('lightcheckout')
            ->setController('index')
            ->forward('index');

        return $result;
    }

}
