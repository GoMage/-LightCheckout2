<?php

namespace GoMage\LightCheckout\Observer;

use GoMage\LightCheckout\Model\Config\CheckoutConfigurationsProvider;
use Magento\Checkout\Model\Session;
use Magento\Framework\Event\Observer;
use Magento\Framework\Event\ObserverInterface;
use Magento\Sales\Api\OrderCustomerManagementInterface;

class AutomaticallyRegisterCustomerOnOnepageSuccess implements ObserverInterface
{
    /**
     * @var CheckoutConfigurationsProvider
     */
    private $checkoutConfigurationsProvider;

    /**
     * @var OrderCustomerManagementInterface
     */
    protected $orderCustomerService;

    /**
     * @var Session
     */
    private $checkoutSession;

    /**
     * @param CheckoutConfigurationsProvider $checkoutConfigurationsProvider
     * @param OrderCustomerManagementInterface $orderCustomerManagement
     * @param Session $checkoutSession
     */
    public function __construct(
        CheckoutConfigurationsProvider $checkoutConfigurationsProvider,
        OrderCustomerManagementInterface $orderCustomerManagement,
        Session $checkoutSession
    ) {
        $this->checkoutConfigurationsProvider = $checkoutConfigurationsProvider;
        $this->orderCustomerService = $orderCustomerManagement;
        $this->checkoutSession = $checkoutSession;
    }

    /**
     * @param Observer $observer
     *
     * @return void
     */
    public function execute(Observer $observer)
    {
        $order = $this->checkoutSession->getLastRealOrder();
        $orderId = $observer->getEvent()->getOrderIds()[0];
        if ($this->checkoutConfigurationsProvider->getIsAutoRegistration()
            && $order
            && $order->getId() == $orderId
            && !$order->getCustomerId()
        ) {
            $this->orderCustomerService->create($orderId);
        }
    }
}
