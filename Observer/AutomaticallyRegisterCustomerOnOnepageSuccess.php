<?php

namespace GoMage\LightCheckout\Observer;

use GoMage\LightCheckout\Model\Config\CheckoutConfigurationsProvider;
use GoMage\LightCheckout\Model\IsEnableLightCheckout;
use Magento\Checkout\Model\Session;
use Magento\Customer\Api\CustomerRepositoryInterface;
use Magento\Customer\Api\Data\CustomerInterface;
use Magento\Framework\Event\Observer;
use Magento\Framework\Event\ObserverInterface;
use Magento\Framework\Exception\NoSuchEntityException;
use Magento\Sales\Api\Data\OrderInterface;
use Magento\Sales\Api\OrderCustomerManagementInterface;
use Magento\Sales\Api\OrderRepositoryInterface;

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
     * @var CustomerRepositoryInterface
     */
    private $customerRepository;

    /**
     * @var OrderRepositoryInterface
     */
    private $orderRepository;

    /**
     * @var IsEnableLightCheckout
     */
    private $isEnableLightCheckout;

    /**
     * @param CheckoutConfigurationsProvider $checkoutConfigurationsProvider
     * @param OrderCustomerManagementInterface $orderCustomerManagement
     * @param Session $checkoutSession
     * @param CustomerRepositoryInterface $customerRepository
     * @param OrderRepositoryInterface $orderRepository
     * @param IsEnableLightCheckout $isEnableLightCheckout
     */
    public function __construct(
        CheckoutConfigurationsProvider $checkoutConfigurationsProvider,
        OrderCustomerManagementInterface $orderCustomerManagement,
        Session $checkoutSession,
        CustomerRepositoryInterface $customerRepository,
        OrderRepositoryInterface $orderRepository,
        IsEnableLightCheckout $isEnableLightCheckout
    ) {
        $this->checkoutConfigurationsProvider = $checkoutConfigurationsProvider;
        $this->orderCustomerService = $orderCustomerManagement;
        $this->checkoutSession = $checkoutSession;
        $this->customerRepository = $customerRepository;
        $this->orderRepository = $orderRepository;
        $this->isEnableLightCheckout = $isEnableLightCheckout;
    }

    /**
     * @param Observer $observer
     *
     * @return void
     */
    public function execute(Observer $observer)
    {
        if ($this->isEnableLightCheckout->execute()) {
            $order = $this->checkoutSession->getLastRealOrder();
            $orderId = $observer->getEvent()->getOrderIds()[0];
            if ($this->checkoutConfigurationsProvider->getIsAutoRegistration()
                && $order
                && $order->getId() == $orderId
                && !$order->getCustomerId()
            ) {
                try {
                    $customer = $this->customerRepository->get($order->getCustomerEmail());
                    $this->assignCustomerToOrder($customer, $order);
                } catch (NoSuchEntityException $e) {
                    //need to do only if customer does not exists.
                    $customer = $this->orderCustomerService->create($orderId);
                    $this->assignCustomerToOrder($customer, $order);
                }
            }
        }
    }

    /**
     * @param CustomerInterface $customer
     * @param OrderInterface $order
     *
     * @return void
     */
    private function assignCustomerToOrder(CustomerInterface $customer, OrderInterface $order)
    {
        $order->setCustomerId($customer->getId());
        $order->setCustomerIsGuest(false);
        $order->setCustomerGroupId(1);
        $order->setCustomerEmail($customer->getEmail());
        $order->setCustomerFirstname($customer->getFirstname());
        $order->setCustomerLastname($customer->getLastname());

        $this->orderRepository->save($order);
    }
}
