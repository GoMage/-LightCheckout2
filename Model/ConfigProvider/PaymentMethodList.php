<?php

namespace GoMage\LightCheckout\Model\ConfigProvider;

use Magento\Quote\Api\PaymentMethodManagementInterface;

class PaymentMethodList
{
    /**
     * @var PaymentMethodManagementInterface
     */
    private $paymentMethodManagement;

    /**
     * @param PaymentMethodManagementInterface $paymentMethodManagement
     */
    public function __construct(PaymentMethodManagementInterface $paymentMethodManagement)
    {
        $this->paymentMethodManagement = $paymentMethodManagement;
    }

    /**
     * Get payment methods config data.
     *
     * @param int $cartId
     * @return array
     */
    public function getPaymentMethods($cartId)
    {
        $result = [];
        foreach ($this->paymentMethodManagement->getList($cartId) as $method) {
            $result[] = [
                'code' => $method->getCode(),
                'title' => $method->getTitle()
            ];
        }
        return $result;
    }
}
